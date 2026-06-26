<?php

namespace App\Services;

use App\Exceptions\SmsGatewayException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Bridge to the ObitSMS gateway (same account used across Diginova's
 * projects, e.g. BH CONNECT). The sender ID is sent per request, not fixed
 * for the account, so callers can use their own sender name.
 */
class SmsGatewayManager
{
    /**
     * @return array{success: bool, raw: array}
     */
    public function send(string $sender, string $destination, string $message): array
    {
        try {
            $response = Http::timeout(15)->get(config('sms.base_url'), [
                'key_api' => config('sms.api_key'),
                'sender' => $sender,
                'destination' => $destination,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Passerelle SMS inaccessible', ['error' => $e->getMessage()]);

            throw new SmsGatewayException("Passerelle SMS inaccessible : {$e->getMessage()}");
        }

        $raw = $response->json() ?? ['success' => false, 'message' => $response->body()];

        return [
            'success' => (bool) ($raw['success'] ?? false),
            'raw' => $raw,
        ];
    }
}
