<?php

namespace App\Console\Commands;

use App\Models\MailDomain;
use App\Services\MailServerManager;
use Illuminate\Console\Command;

class MailSyncFromServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sync-from-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe les domaines/adresses mail existants (Postfix) dans la base, sans toucher au serveur mail';

    public function handle(MailServerManager $mailServer): int
    {
        $accounts = $mailServer->listAccounts();

        if ($accounts === []) {
            $this->warn('Aucun compte mail trouvé via mailctl list.');

            return self::SUCCESS;
        }

        $domainCount = 0;
        $accountCount = 0;

        foreach ($accounts as $entry) {
            $domain = MailDomain::firstOrCreate(['name' => $entry['domain']]);
            if ($domain->wasRecentlyCreated) {
                $domainCount++;
            }

            $account = $domain->accounts()->firstOrCreate(['local_part' => $entry['local_part']]);
            if ($account->wasRecentlyCreated) {
                $accountCount++;
            }
        }

        $this->info("Synchronisation terminée : {$domainCount} domaine(s) et {$accountCount} adresse(s) importé(s).");
        $this->comment('Pense à assigner owner_user_id sur chaque MailDomain (via tinker) pour que les clients y aient accès.');

        return self::SUCCESS;
    }
}
