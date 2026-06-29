<?php

namespace App\Http\Controllers;

use App\Exceptions\OllamaException;
use App\Http\Requests\StoreDiagnosticSubmissionRequest;
use App\Models\DiagnosticSubmission;
use App\Services\OllamaClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class DiagnosticController extends Controller
{
    private const MAIL_SITUATION_LABELS = [
        'none' => "Aucune adresse email professionnelle aujourd'hui",
        'personal_email' => 'Utilise une adresse personnelle (Gmail, Yahoo...) pour son activité',
        'own_domain_unmanaged' => 'A déjà un nom de domaine, mais pas de messagerie professionnelle dessus',
    ];

    private const SMS_NEED_LABELS = [
        'none' => 'Pas de besoin SMS identifié',
        'transactional' => 'SMS transactionnels (confirmations, OTP, alertes)',
        'marketing' => 'Campagnes SMS marketing',
        'both' => 'Les deux : transactionnel et campagnes marketing',
    ];

    public function __construct(private OllamaClient $ollama) {}

    public function create(): Response
    {
        return Inertia::render('Diagnostic/Form');
    }

    public function store(StoreDiagnosticSubmissionRequest $request): Response
    {
        $validated = $request->validated();

        $submission = DiagnosticSubmission::create($validated);

        $answers = [
            'name' => $validated['name'],
            'company' => $validated['company'] ?? 'Non précisé',
            'mail_situation' => self::MAIL_SITUATION_LABELS[$validated['mail_situation']],
            'mail_boxes_needed' => $validated['mail_boxes_needed'] ?? 'Non précisé',
            'sms_need' => self::SMS_NEED_LABELS[$validated['sms_need']],
            'sms_volume_monthly' => $validated['sms_volume_monthly'] ?? 'Non précisé',
            'main_need' => $validated['main_need'],
            'budget_range' => $validated['budget_range'] ?? 'Non précisé',
        ];

        try {
            $analysis = $this->ollama->analyzeDiagnostic($answers);
            $submission->update(['ai_analysis' => $analysis, 'ai_status' => DiagnosticSubmission::STATUS_DONE]);
        } catch (OllamaException $e) {
            Log::channel('daily')->error('Échec analyse diagnostic IA', ['id' => $submission->id, 'error' => $e->getMessage()]);
            $analysis = null;
            $submission->update(['ai_status' => DiagnosticSubmission::STATUS_FAILED]);
        }

        $this->notifyDiginova($submission, $analysis);

        return Inertia::render('Diagnostic/Form', [
            'result' => [
                'name' => $validated['name'],
                'analysis' => $analysis,
            ],
        ]);
    }

    private function notifyDiginova(DiagnosticSubmission $submission, ?string $analysis): void
    {
        $body = "Nouveau diagnostic soumis sur diginova.cm\n\n"
            ."Nom : {$submission->name}\n"
            ."Email : {$submission->email}\n"
            ."Téléphone : {$submission->phone}\n"
            ."Entreprise : {$submission->company}\n"
            ."Besoin principal : {$submission->main_need}\n\n"
            .'Analyse IA : '.($analysis ?? "(échec de l'analyse automatique, à traiter manuellement)");

        try {
            Mail::raw($body, function ($message) {
                $message->to('contact@diginova.cm')->subject('Nouveau diagnostic SMS/Messagerie Pro');
            });
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec envoi notification diagnostic', ['error' => $e->getMessage()]);
        }
    }
}
