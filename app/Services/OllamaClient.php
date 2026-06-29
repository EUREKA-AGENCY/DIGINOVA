<?php

namespace App\Services;

use App\Exceptions\OllamaException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Bridge to the Ollama instance already running on this server
 * (127.0.0.1:11434, behind the sat-and-buy-apps Flowise/Ollama stack).
 *
 * Used to turn a prospect's diagnostic answers into a plain-language
 * recommendation. Runs a reasoning model (deepseek-r1:7b) on CPU only,
 * so a single call can take anywhere from a few seconds (model already
 * loaded) to a couple of minutes (cold start + long "thinking" phase) —
 * callers must budget for that.
 */
class OllamaClient
{
    private const ENDPOINT = 'http://127.0.0.1:11434/api/generate';

    private const MODEL = 'deepseek-r1:7b';

    private const PRICING_CONTEXT = <<<'TXT'
Grilles tarifaires Diginova (à utiliser telles quelles, ne jamais inventer d'autres prix) :

Messagerie professionnelle (par boîte mail, par mois) :
- Essentiel : 2 000 F — adresse @domaine, webmail + IMAP/POP, anti-spam de base
- Pro (le plus choisi) : 3 500 F — + synchronisation mobile push, calendrier/contacts partagés, anti-spam avancé
- Business : 6 000 F — + archivage légal, authentification 2FA, SLA garanti 99,9%

SMS (par SMS envoyé) :
- Pack Start : 17 F/SMS — de 1 000 à 9 999 SMS/mois
- Pack Pro (le plus choisi) : 15 F/SMS — de 10 000 à 49 999 SMS/mois
- Pack Elite : 14 F/SMS — 50 000 SMS/mois et plus, accès API complet, SLA garanti
TXT;

    public function analyzeDiagnostic(array $answers): string
    {
        $prompt = $this->buildPrompt($answers);

        try {
            $result = Http::timeout(280)->post(self::ENDPOINT, [
                'model' => self::MODEL,
                'prompt' => $prompt,
                'stream' => false,
            ]);
        } catch (ConnectionException $e) {
            Log::channel('daily')->error('Ollama injoignable/timeout', ['error' => $e->getMessage()]);

            throw new OllamaException("Ollama injoignable ou trop lent : {$e->getMessage()}");
        }

        if ($result->failed()) {
            Log::channel('daily')->error('Ollama a échoué', ['status' => $result->status()]);

            throw new OllamaException("Appel Ollama échoué (HTTP {$result->status()})");
        }

        $response = trim((string) $result->json('response'));

        if ($response === '') {
            throw new OllamaException('Réponse Ollama vide');
        }

        // Le modèle ignore parfois la consigne "sans markdown" : on retire
        // au moins les marqueurs de gras/titres pour un texte brut propre.
        $response = preg_replace('/\*\*(.*?)\*\*/u', '$1', $response);
        $response = preg_replace('/^#{1,6}\s*/mu', '', $response);

        return $response;
    }

    private function buildPrompt(array $answers): string
    {
        return <<<PROMPT
Tu es un conseiller commercial pour Diginova, agence basée à Yaoundé, Cameroun, qui vend des solutions de messagerie professionnelle et de SMS Pro.

{$this->pricingContext()}

Un prospect vient de répondre à un court formulaire de diagnostic. Voici ses réponses :
- Nom : {$answers['name']}
- Entreprise : {$answers['company']}
- Situation email actuelle : {$answers['mail_situation']}
- Nombre de boîtes mail souhaitées : {$answers['mail_boxes_needed']}
- Besoin SMS : {$answers['sms_need']}
- Volume SMS estimé par mois : {$answers['sms_volume_monthly']}
- Besoin principal exprimé : {$answers['main_need']}
- Budget approximatif indiqué : {$answers['budget_range']}

Rédige une réponse en français, directement adressée au prospect (tutoiement professionnel "vous"), structurée en 4 courts paragraphes, sans titres ni markdown :
1. Le ou les packs Diginova recommandés (mail et/ou SMS) selon ses réponses, en te basant strictement sur les grilles ci-dessus.
2. Pourquoi ce choix correspond à son besoin (2-3 phrases maximum, concret).
3. Une fourchette de prix mensuelle indicative basée sur les grilles fournies, en précisant explicitement qu'il s'agit d'une estimation à confirmer.
4. Une prochaine étape claire (rappel par l'équipe Diginova sous 24h, ou contact WhatsApp direct).

Ne donne aucun prix qui ne figure pas dans les grilles ci-dessus. Réponds uniquement avec ce texte, sans préambule ni note finale.
PROMPT;
    }

    private function pricingContext(): string
    {
        return self::PRICING_CONTEXT;
    }
}
