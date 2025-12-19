<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequestStore;
use App\Models\QuoteRequest as QuoteRequestModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteRequestReceived;

class QuoteController extends Controller
{
    public function store(QuoteRequestStore $request): RedirectResponse
    {
        $data = $request->validated();

        $quote = QuoteRequestModel::create([
            'service_id' => $data['service_id'] ?? null,
            'project_type' => $data['project_type'] ?? null,
            'project_name' => $data['project_name'] ?? null,
            'budget_range' => $data['budget_range'] ?? null,
            'deadline' => $data['deadline'] ?? null,
            'details' => $data['details'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'contact_name' => $data['contact_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => 'new',
            'meta' => $data['meta'] ?? null,
        ]);

        $to = config('mail.notifications.to', config('mail.from.address'));
        if ($to) {
            Mail::to($to)->send(new QuoteRequestReceived($quote));
        }

        return redirect()->route('quote.confirmation')->with('success', 'Votre demande de devis a bien été envoyée.');
    }
}
