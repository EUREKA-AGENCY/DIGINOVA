<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SmsGatewayException;
use App\Http\Controllers\Controller;
use App\Models\SmsAccount;
use App\Models\SmsMessage;
use App\Services\SmsGatewayManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsApiController extends Controller
{
    public function __construct(private SmsGatewayManager $gateway) {}

    public function send(Request $request): JsonResponse
    {
        $apiKey = $request->bearerToken() ?? $request->input('api_key');
        $account = $apiKey ? SmsAccount::with('user')->where('api_key', $apiKey)->first() : null;

        if (! $account) {
            return response()->json(['success' => false, 'message' => 'Clé API invalide.'], 401);
        }

        if ($account->user->is_blocked) {
            return response()->json(['success' => false, 'message' => 'Ce compte a été bloqué.'], 403);
        }

        $validated = $request->validate([
            'sender' => ['nullable', 'string', 'max:11'],
            'destination' => ['required', 'string', 'regex:/^[0-9]{8,15}$/'],
            'message' => ['required', 'string', 'max:480'],
        ]);

        $sender = $validated['sender'] ?? $account->sender_name ?? config('sms.default_sender');
        $segments = (int) ceil(mb_strlen($validated['message']) / 160);

        if ($account->balance < $segments) {
            return response()->json(['success' => false, 'message' => 'Solde insuffisant.'], 402);
        }

        try {
            $result = $this->gateway->send($sender, $validated['destination'], $validated['message']);
        } catch (SmsGatewayException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        $status = $result['success'] ? SmsMessage::STATUS_SENT : SmsMessage::STATUS_FAILED;

        $account->messages()->create([
            'sender' => $sender,
            'destination' => $validated['destination'],
            'message' => $validated['message'],
            'segments' => $segments,
            'status' => $status,
            'provider_response' => json_encode($result['raw']),
        ]);

        if ($result['success']) {
            $account->decrement('balance', $segments);

            return response()->json(['success' => true, 'segments' => $segments]);
        }

        return response()->json(['success' => false, 'message' => $result['raw']['message'] ?? 'Échec de l\'envoi.'], 502);
    }
}
