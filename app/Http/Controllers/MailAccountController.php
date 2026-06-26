<?php

namespace App\Http\Controllers;

use App\Exceptions\MailServerException;
use App\Http\Requests\Mail\StoreMailAccountRequest;
use App\Http\Requests\Mail\UpdateCatchAllRequest;
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
                'catch_all_local_part' => $domain->catch_all_local_part,
                'accounts' => $domain->accounts->map(fn (MailAccount $account) => [
                    'id' => $account->id,
                    'local_part' => $account->local_part,
                    'address' => "{$account->local_part}@{$domain->name}",
                    'type' => $account->type,
                    'forward_to' => $account->forward_to,
                ]),
            ]),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(StoreMailAccountRequest $request, MailDomain $domain): RedirectResponse
    {
        $validated = $request->validated();
        $isAlias = $validated['type'] === MailAccount::TYPE_ALIAS;

        try {
            if ($isAlias) {
                $this->mailServer->createAlias($domain->name, $validated['local_part'], $validated['forward_to']);
            } else {
                $this->mailServer->createAccount($domain->name, $validated['local_part'], $validated['password']);
            }
        } catch (MailServerException $e) {
            return back()->withErrors(['local_part' => $e->getMessage()]);
        }

        $domain->accounts()->create([
            'local_part' => $validated['local_part'],
            'type' => $validated['type'],
            'forward_to' => $isAlias ? $validated['forward_to'] : null,
        ]);

        return back()->with('status', "Adresse {$validated['local_part']}@{$domain->name} créée.");
    }

    public function destroy(MailDomain $domain, MailAccount $account): RedirectResponse
    {
        abort_if($account->mail_domain_id !== $domain->id, 404);
        $this->authorize('manage', $domain);

        try {
            if ($account->isMailbox()) {
                $this->mailServer->deleteAccount($domain->name, $account->local_part);
            } else {
                $this->mailServer->deleteAlias($domain->name, $account->local_part);
            }
        } catch (MailServerException $e) {
            return back()->withErrors(['local_part' => $e->getMessage()]);
        }

        $account->delete();

        return back()->with('status', 'Adresse supprimée.');
    }

    public function updatePassword(UpdateMailAccountPasswordRequest $request, MailDomain $domain, MailAccount $account): RedirectResponse
    {
        abort_if($account->mail_domain_id !== $domain->id, 404);
        abort_if(! $account->isMailbox(), 422, 'Une adresse de redirection ne possède pas de mot de passe.');

        try {
            $this->mailServer->updatePassword($domain->name, $account->local_part, $request->validated('password'));
        } catch (MailServerException $e) {
            return back()->withErrors(['password' => $e->getMessage()]);
        }

        return back()->with('status', 'Mot de passe mis à jour.');
    }

    public function enableCatchAll(UpdateCatchAllRequest $request, MailDomain $domain): RedirectResponse
    {
        $localPart = $request->validated('local_part');

        try {
            $this->mailServer->enableCatchAll($domain->name, $localPart);
        } catch (MailServerException $e) {
            return back()->withErrors(['catch_all' => $e->getMessage()]);
        }

        $domain->update(['catch_all_local_part' => $localPart]);

        return back()->with('status', "Catch-all activé vers {$localPart}@{$domain->name}.");
    }

    public function disableCatchAll(MailDomain $domain): RedirectResponse
    {
        $this->authorize('manage', $domain);

        try {
            $this->mailServer->disableCatchAll($domain->name);
        } catch (MailServerException $e) {
            return back()->withErrors(['catch_all' => $e->getMessage()]);
        }

        $domain->update(['catch_all_local_part' => null]);

        return back()->with('status', 'Catch-all désactivé.');
    }
}
