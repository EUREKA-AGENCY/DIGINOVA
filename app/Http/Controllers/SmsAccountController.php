<?php

namespace App\Http\Controllers;

use App\Exceptions\SmsGatewayException;
use App\Http\Requests\Sms\SendSmsRequest;
use App\Http\Requests\Sms\StoreSmsCampaignRequest;
use App\Http\Requests\Sms\StoreSmsContactRequest;
use App\Models\SmsCampaign;
use App\Models\SmsContact;
use App\Models\SmsMessage;
use App\Services\SmsGatewayManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SmsAccountController extends Controller
{
    public function __construct(private SmsGatewayManager $gateway) {}

    public function index(Request $request): Response
    {
        $account = $request->user()->smsAccount;

        $recent = $account
            ? $account->messages()->latest()->limit(10)->get()
            : collect();

        $totalSent = $account ? $account->messages()->where('status', SmsMessage::STATUS_SENT)->count() : 0;
        $totalFailed = $account ? $account->messages()->where('status', SmsMessage::STATUS_FAILED)->count() : 0;
        $totalAttempts = $totalSent + $totalFailed;

        return Inertia::render('Sms/Dashboard', [
            'account' => $account ? [
                'balance' => $account->balance,
                'sender_name' => $account->sender_name,
            ] : null,
            'stats' => [
                'total_sent' => $totalSent,
                'delivery_rate' => $totalAttempts > 0 ? round($totalSent / $totalAttempts * 100) : 100,
            ],
            'recent' => $recent->map(fn (SmsMessage $m) => [
                'id' => $m->id,
                'date' => $m->created_at->format('d/m/Y H:i'),
                'sender' => $m->sender,
                'destination' => $m->destination,
                'message' => $m->message,
                'status' => $m->status,
            ]),
        ]);
    }

    public function showSend(Request $request): Response
    {
        $account = $request->user()->smsAccount;

        return Inertia::render('Sms/Send', [
            'account' => $account ? [
                'balance' => $account->balance,
                'sender_name' => $account->sender_name,
            ] : null,
            'contacts' => $account
                ? $account->contacts()->orderBy('name')->get(['id', 'name', 'phone'])
                : collect(),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function send(SendSmsRequest $request): RedirectResponse
    {
        $account = $request->user()->smsAccount;
        $validated = $request->validated();
        $segments = (int) ceil(mb_strlen($validated['message']) / 160);

        if ($account->balance < $segments) {
            return back()->withErrors(['message' => "Solde insuffisant ({$account->balance} crédit(s) restant(s) pour {$segments} requis)."]);
        }

        try {
            $result = $this->gateway->send($validated['sender'], $validated['destination'], $validated['message']);
        } catch (SmsGatewayException $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }

        $status = $result['success'] ? SmsMessage::STATUS_SENT : SmsMessage::STATUS_FAILED;

        $account->messages()->create([
            'sender' => $validated['sender'],
            'destination' => $validated['destination'],
            'message' => $validated['message'],
            'segments' => $segments,
            'status' => $status,
            'provider_response' => json_encode($result['raw']),
        ]);

        if ($result['success']) {
            $account->decrement('balance', $segments);

            return back()->with('status', 'SMS envoyé.');
        }

        return back()->withErrors(['message' => $result['raw']['message'] ?? 'Échec de l\'envoi (passerelle SMS).']);
    }

    public function history(Request $request): Response
    {
        $account = $request->user()->smsAccount;

        $query = $account ? $account->messages()->latest() : SmsMessage::query()->whereRaw('1 = 0');

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }
        if ($request->filled('phone')) {
            $query->where('destination', 'like', '%'.$request->input('phone').'%');
        }

        $messages = $query->paginate(20)->withQueryString();

        return Inertia::render('Sms/History', [
            'messages' => $messages->through(fn (SmsMessage $m) => [
                'id' => $m->id,
                'date' => $m->created_at->format('d/m/Y H:i'),
                'sender' => $m->sender,
                'destination' => $m->destination,
                'message' => $m->message,
                'segments' => $m->segments,
                'status' => $m->status,
            ]),
            'filters' => $request->only(['from', 'to', 'phone']),
            'total' => $account ? $account->messages()->count() : 0,
        ]);
    }

    public function contacts(Request $request): Response
    {
        $account = $request->user()->smsAccount;

        return Inertia::render('Sms/Contacts', [
            'contacts' => $account
                ? $account->contacts()->orderBy('name')->get(['id', 'name', 'phone'])
                : collect(),
        ]);
    }

    public function storeContact(StoreSmsContactRequest $request): RedirectResponse
    {
        $request->user()->smsAccount->contacts()->create($request->validated());

        return back()->with('status', 'Contact ajouté.');
    }

    public function destroyContact(Request $request, SmsContact $contact): RedirectResponse
    {
        abort_if($contact->sms_account_id !== $request->user()->smsAccount?->id, 404);

        $contact->delete();

        return back()->with('status', 'Contact supprimé.');
    }

    public function campaigns(Request $request): Response
    {
        $account = $request->user()->smsAccount;

        return Inertia::render('Sms/Campaigns', [
            'account' => $account ? ['balance' => $account->balance, 'sender_name' => $account->sender_name] : null,
            'contacts' => $account
                ? $account->contacts()->orderBy('name')->get(['id', 'name', 'phone'])
                : collect(),
            'campaigns' => $account
                ? $account->campaigns()->latest()->limit(20)->get()->map(fn (SmsCampaign $c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'sender' => $c->sender,
                    'message' => $c->message,
                    'recipients_count' => $c->recipients_count,
                    'sent_count' => $c->sent_count,
                    'failed_count' => $c->failed_count,
                    'sent_at' => $c->sent_at?->format('d/m/Y H:i'),
                ])
                : collect(),
        ]);
    }

    public function storeCampaign(StoreSmsCampaignRequest $request): RedirectResponse
    {
        $account = $request->user()->smsAccount;
        $validated = $request->validated();
        $segments = (int) ceil(mb_strlen($validated['message']) / 160);
        $contacts = $account->contacts()->whereIn('id', $validated['contact_ids'])->get();
        $cost = $segments * $contacts->count();

        if ($account->balance < $cost) {
            return back()->withErrors(['contact_ids' => "Solde insuffisant ({$account->balance} crédit(s) restant(s) pour {$cost} requis)."]);
        }

        $campaign = $account->campaigns()->create([
            'name' => $validated['name'],
            'sender' => $validated['sender'],
            'message' => $validated['message'],
            'status' => 'sent',
            'recipients_count' => $contacts->count(),
            'sent_at' => now(),
        ]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($contacts as $contact) {
            try {
                $result = $this->gateway->send($validated['sender'], $contact->phone, $validated['message']);
            } catch (SmsGatewayException $e) {
                $result = ['success' => false, 'raw' => ['message' => $e->getMessage()]];
            }

            $status = $result['success'] ? SmsMessage::STATUS_SENT : SmsMessage::STATUS_FAILED;

            $campaign->messages()->create([
                'sms_account_id' => $account->id,
                'sender' => $validated['sender'],
                'destination' => $contact->phone,
                'message' => $validated['message'],
                'segments' => $segments,
                'status' => $status,
                'provider_response' => json_encode($result['raw']),
            ]);

            if ($result['success']) {
                $sentCount++;
                $account->decrement('balance', $segments);
            } else {
                $failedCount++;
            }

            usleep(300_000);
        }

        $campaign->update(['sent_count' => $sentCount, 'failed_count' => $failedCount]);

        return back()->with('status', "Campagne envoyée : {$sentCount} réussi(s), {$failedCount} échec(s).");
    }

    public function developers(Request $request): Response
    {
        $account = $request->user()->smsAccount;
        $account?->ensureApiKey();

        return Inertia::render('Sms/Developers', [
            'account' => $account ? [
                'api_key' => $account->api_key,
                'sender_name' => $account->sender_name,
            ] : null,
            'endpoint' => url('/api/sms/send'),
        ]);
    }

    public function regenerateApiKey(Request $request): RedirectResponse
    {
        $account = $request->user()->smsAccount;
        abort_if(! $account, 404);

        $account->regenerateApiKey();

        return back()->with('status', 'Nouvelle clé API générée.');
    }
}
