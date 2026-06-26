<?php

namespace App\Http\Controllers;

use App\Exceptions\MailServerException;
use App\Http\Requests\Mail\StoreMailAccountRequest;
use App\Http\Requests\Mail\UpdateMailAccountPasswordRequest;
use App\Models\MailAccount;
use App\Models\MailDomain;
use App\Services\MailServerManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MailAccountController extends Controller
{
    public function __construct(private MailServerManager $mailServer) {}

    /**
     * List the domain(s) owned by the current user, with their mail accounts.
     */
    public function index(Request $request): Response
    {
        $domains = $request->user()->mailDomains()->with('accounts')->orderBy('name')->get();

        return Inertia::render('Mail/Index', [
            'domains' => $domains->map(fn (MailDomain $domain) => [
                'id' => $domain->id,
                'name' => $domain->name,
                'accounts' => $domain->accounts->map(fn (MailAccount $account) => [
                    'id' => $account->id,
                    'local_part' => $account->local_part,
                    'address' => "{$account->local_part}@{$domain->name}",
                ]),
            ]),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(StoreMailAccountRequest $request, MailDomain $domain): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->mailServer->createAccount($domain->name, $validated['local_part'], $validated['password']);
        } catch (MailServerException $e) {
            return back()->withErrors(['local_part' => $e->getMessage()]);
        }

        $domain->accounts()->create(['local_part' => $validated['local_part']]);

        return back()->with('status', "Adresse {$validated['local_part']}@{$domain->name} créée.");
    }

    public function destroy(MailDomain $domain, MailAccount $account): RedirectResponse
    {
        abort_if($account->mail_domain_id !== $domain->id, 404);
        $this->authorize('manage', $domain);

        try {
            $this->mailServer->deleteAccount($domain->name, $account->local_part);
        } catch (MailServerException $e) {
            return back()->withErrors(['local_part' => $e->getMessage()]);
        }

        $account->delete();

        return back()->with('status', 'Adresse supprimée.');
    }

    public function updatePassword(UpdateMailAccountPasswordRequest $request, MailDomain $domain, MailAccount $account): RedirectResponse
    {
        abort_if($account->mail_domain_id !== $domain->id, 404);

        try {
            $this->mailServer->updatePassword($domain->name, $account->local_part, $request->validated('password'));
        } catch (MailServerException $e) {
            return back()->withErrors(['password' => $e->getMessage()]);
        }

        return back()->with('status', 'Mot de passe mis à jour.');
    }
}
