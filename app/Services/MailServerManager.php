<?php

namespace App\Services;

use App\Exceptions\MailServerException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

/**
 * Bridge to the privileged /usr/local/sbin/mailctl script on the mail server.
 *
 * PHP-FPM runs as www-data and cannot edit Postfix/Dovecot config directly;
 * mailctl is a root-owned, argument-validating wrapper invoked via a narrow
 * sudoers rule. Commands are always passed as arrays so no shell is involved.
 */
class MailServerManager
{
    private const BIN = '/usr/local/sbin/mailctl';

    private const LOCAL_PART_REGEX = '/^[a-z0-9][a-z0-9._-]{0,62}$/';

    public function createAccount(string $domain, string $localPart, string $password): void
    {
        $this->assertLocalPart($localPart);
        $this->run(['add', $domain, $localPart], $password);
    }

    public function deleteAccount(string $domain, string $localPart): void
    {
        $this->assertLocalPart($localPart);
        $this->run(['delete', $domain, $localPart]);
    }

    public function updatePassword(string $domain, string $localPart, string $password): void
    {
        $this->assertLocalPart($localPart);
        $this->run(['passwd', $domain, $localPart], $password);
    }

    public function createAlias(string $domain, string $localPart, string $forwardTo): void
    {
        $this->assertLocalPart($localPart);
        $this->run(['alias-add', $domain, $localPart], $forwardTo);
    }

    public function deleteAlias(string $domain, string $localPart): void
    {
        $this->assertLocalPart($localPart);
        $this->run(['alias-delete', $domain, $localPart]);
    }

    public function enableCatchAll(string $domain, string $targetLocalPart): void
    {
        $this->assertLocalPart($targetLocalPart);
        $this->run(['catchall-enable', $domain, $targetLocalPart]);
    }

    public function disableCatchAll(string $domain): void
    {
        $this->run(['catchall-disable', $domain]);
    }

    /**
     * @return array<int, array{domain: string, local_part: string}>
     */
    public function listAccounts(): array
    {
        $result = Process::timeout(30)->run(['sudo', self::BIN, 'list']);

        if (! $result->successful()) {
            throw new MailServerException("mailctl list a échoué : {$result->errorOutput()}");
        }

        $accounts = [];
        foreach (explode("\n", trim($result->output())) as $line) {
            $line = trim($line);
            if ($line === '' || ! str_contains($line, '@')) {
                continue;
            }
            [$localPart, $domain] = explode('@', $line, 2);
            $accounts[] = ['domain' => $domain, 'local_part' => $localPart];
        }

        return $accounts;
    }

    private function assertLocalPart(string $localPart): void
    {
        if (! preg_match(self::LOCAL_PART_REGEX, $localPart)) {
            throw new MailServerException("Partie locale invalide : {$localPart}");
        }
    }

    /**
     * Run mailctl. The password (if any) is piped via stdin rather than passed
     * as an argument, so it never appears in `ps aux` output on the server.
     */
    private function run(array $args, ?string $stdin = null): void
    {
        $process = Process::timeout(30);
        if ($stdin !== null) {
            $process = $process->input($stdin);
        }

        $result = $process->run(['sudo', self::BIN, ...$args]);

        if (! $result->successful()) {
            Log::channel('daily')->error('mailctl a échoué', [
                'args' => array_slice($args, 0, 2), // jamais le mot de passe
                'error' => $result->errorOutput(),
            ]);

            throw new MailServerException("Opération mail échouée : {$result->errorOutput()}");
        }
    }
}
