<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdjustSmsCreditsRequest;
use App\Http\Requests\Admin\AssignMailDomainRequest;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\StoreMailDomainRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Models\MailDomain;
use App\Models\SmsAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
                'is_blocked' => $u->is_blocked,
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

        try {
            $user->sendEmailVerificationNotification();
            $status = "Client {$user->email} créé. Email de vérification envoyé.";
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec envoi email de vérification (admin)', ['email' => $user->email, 'error' => $e->getMessage()]);
            $status = "Client {$user->email} créé, mais l'email de vérification n'a pas pu être envoyé. Transmettez le mot de passe manuellement.";
        }

        return back()
            ->with('status', $status)
            ->with('tempPassword', $temp);
    }

    public function updateClient(UpdateClientRequest $request, User $client): RedirectResponse
    {
        $client->update($request->validated());

        return back()->with('status', "Client {$client->email} mis à jour.");
    }

    public function blockClient(Request $request, User $client): RedirectResponse
    {
        $client->update(['is_blocked' => true]);

        return back()->with('status', "{$client->email} a été bloqué (connexion et API SMS désactivées).");
    }

    public function unblockClient(User $client): RedirectResponse
    {
        $client->update(['is_blocked' => false]);

        return back()->with('status', "{$client->email} a été débloqué.");
    }

    public function destroyClient(User $client): RedirectResponse
    {
        $email = $client->email;
        $client->delete();

        return back()->with('status', "Client {$email} supprimé. Ses domaines mail ont été désassignés.");
    }

    public function storeDomain(StoreMailDomainRequest $request): RedirectResponse
    {
        $domain = MailDomain::create(['name' => $request->validated('name')]);

        return back()->with('status', "Domaine {$domain->name} enregistré. Pensez à le configurer côté serveur mail avant de créer des adresses.");
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
