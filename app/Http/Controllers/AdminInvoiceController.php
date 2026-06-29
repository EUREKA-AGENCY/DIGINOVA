<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminInvoiceController extends Controller
{
    public function index(): \Inertia\Response
    {
        $invoices = Invoice::query()->orderByDesc('id')->get();

        return Inertia::render('Admin/Invoices', [
            'invoices' => $invoices->map(fn (Invoice $invoice) => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'client_name' => $invoice->client_name,
                'client_email' => $invoice->client_email,
                'item_name' => $invoice->item_name,
                'years' => $invoice->years,
                'total_amount' => $invoice->total_amount,
                'status' => $invoice->status,
                'created_at' => $invoice->created_at->format('d/m/Y H:i'),
            ]),
        ]);
    }

    public function markPaid(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => Invoice::STATUS_PAID, 'paid_at' => now()]);

        return back()->with('status', "Facture {$invoice->invoice_number} marquée payée.");
    }

    public function markCancelled(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => Invoice::STATUS_CANCELLED]);

        return back()->with('status', "Facture {$invoice->invoice_number} annulée.");
    }

    public function download(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

        return $pdf->download("{$invoice->invoice_number}.pdf");
    }

    public function resend(Invoice $invoice): RedirectResponse
    {
        try {
            $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

            Mail::send([], [], function ($message) use ($invoice, $pdf) {
                $message->to($invoice->client_email)
                    ->subject("Votre facture Diginova {$invoice->invoice_number}")
                    ->html(
                        "Bonjour {$invoice->client_name},<br><br>".
                        "Veuillez trouver ci-joint votre facture {$invoice->invoice_number}.<br><br>".
                        "Cordialement,<br>L'équipe Diginova"
                    )
                    ->attachData($pdf->output(), "{$invoice->invoice_number}.pdf", ['mime' => 'application/pdf']);
            });

            return back()->with('status', "Facture {$invoice->invoice_number} renvoyée à {$invoice->client_email}.");
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec renvoi facture', ['invoice' => $invoice->invoice_number, 'error' => $e->getMessage()]);

            return back()->withErrors(['email' => "Échec de l'envoi : {$e->getMessage()}"]);
        }
    }
}
