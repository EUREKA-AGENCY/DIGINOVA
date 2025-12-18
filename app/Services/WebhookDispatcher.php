<?php

namespace App\Services;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    /**
     * Dispatch a webhook event to all active webhooks subscribed to this event.
     *
     * @param  string  $event   Logical event name (e.g. "forum.thread.created")
     * @param  array<string, mixed>  $payload Structured payload data
     */
    public static function dispatch(string $event, array $payload): void
    {
        $webhooks = Webhook::query()
            ->where('is_active', true)
            ->get();

        if ($webhooks->isEmpty()) {
            return;
        }

        $timestamp = now()->toIso8601String();

        foreach ($webhooks as $webhook) {
            $events = $webhook->events ?? [];

            if (! in_array($event, $events, true) && ! in_array('*', $events, true)) {
                continue;
            }

            $body = [
                'event' => $event,
                'data' => $payload,
                'timestamp' => $timestamp,
                'webhook_id' => $webhook->id,
            ];

            try {
                Http::timeout(3)->post($webhook->url, $body);
            } catch (\Throwable $e) {
                Log::warning('Webhook dispatch failed', [
                    'webhook_id' => $webhook->id,
                    'event' => $event,
                    'url' => $webhook->url,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

