<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdjustSmsCreditsRequest;
use App\Http\Requests\Admin\AssignMailDomainRequest;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Models\MailDomain;
use App\Models\SmsAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(Request $request): Response
    {
        $clients = User::query()
            ->where('is_admin', false)
            ->with(['mailDomains', 'smsAccount'])
            ->orderBy('name')
            ->get();

        $domains = MailDomain::query()->orderBy('name')->get(['id', 'name', 'owner_user_id']);

        return Inertia::render('Admin/Index', [
            'clients' => $clients->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'verified' => $u->email_verified_at !== null,
                'mail_domains' => $u->mailDomains->pluck('name'),
                'sms_balance' => $u->smsAccount?->balance,
            ]),
            'domains' => $domains->map(fn (MailDomain $d) => [
                'id' => $d->id,
                'name' => $d->name,
                'owner_user_id' => $d->owner_user_id,
            ]),
            'status' => $request->session()->get('status'),
            'tempPassword' => $request->session()->get('tempPassword'),
        ]);
    }

    public function storeClient(StoreClientRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $temp = Str::random(14);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($temp),
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();

        return back()
            ->with('status', "Client {$user->email} créé. Email de vérification envoyé.")
            ->with('tempPassword', $temp);
    }

    public function assignDomain(AssignMailDomainRequest $request, User $client): RedirectResponse
    {
        $domain = MailDomain::findOrFail($request->validated('domain_id'));
        $domain->update(['owner_user_id' => $client->id]);

        return back()->with('status', "Domaine {$domain->name} assigné à {$client->email}.");
    }

    public function unassignDomain(MailDomain $domain): RedirectResponse
    {
        $domain->update(['owner_user_id' => null]);

        return back()->with('status', "Domaine {$domain->name} désassigné.");
    }

    public function adjustSmsCredits(AdjustSmsCreditsRequest $request, User $client): RedirectResponse
    {
        $amount = $request->validated('amount');

        $account = $client->smsAccount;
        if (! $account) {
            $account = SmsAccount::create([
                'user_id' => $client->id,
                'balance' => max(0, $amount),
            ]);
        } else {
            $account->update(['balance' => max(0, $account->balance + $amount)]);
        }

        return back()->with('status', "Solde SMS de {$client->email} mis à jour ({$account->balance} crédits).");
    }
}
