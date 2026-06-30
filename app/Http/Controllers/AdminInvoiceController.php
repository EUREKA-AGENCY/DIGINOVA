<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminInvoiceController extends Controller
{
    public function storeManual(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name'    => 'required|string|max:255',
            'client_email'   => 'required|email|max:255',
            'client_phone'   => 'nullable|string|max:50',
            'client_company' => 'nullable|string|max:255',
            'item_name'      => 'required|string|max:500',
            'total_amount'   => 'required|integer|min:1000|max:99999999',
        ]);

        $invoice = Invoice::create([
            'invoice_number'   => Invoice::generateNumber(),
            'client_name'      => $validated['client_name'],
            'client_email'     => $validated['client_email'],
            'client_phone'     => $validated['client_phone'] ?? null,
            'client_company'   => $validated['client_company'] ?? null,
            'item_name'        => $validated['item_name'],
            'unit_price'       => $validated['total_amount'],
            'years'            => 1,
            'discount_percent' => 0,
            'total_amount'     => $validated['total_amount'],
        ]);

        try {
            $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

            Mail::send([], [], function ($message) use ($invoice, $pdf) {
                $message->to($invoice->client_email)
                    ->subject("Votre facture Diginova {$invoice->invoice_number}")
                    ->html(
                        "Bonjour {$invoice->client_name},<br><br>".
                        "Veuillez trouver ci-joint votre facture {$invoice->invoice_number} pour : {$invoice->item_name}, ".
                        "d'un montant de ".number_format($invoice->total_amount, 0, ',', ' ').' F CFA.<br><br>'.
                        'Les modalités de paiement figurent sur la facture. Une fois le règlement effectué, merci de nous transmettre '.
                        'votre preuve de paiement via WhatsApp : <a href="https://wa.me/237655065494">+237 655 065 494</a>.<br><br>'.
                        "Cordialement,<br>L'équipe Diginova"
                    )
                    ->attachData($pdf->output(), "{$invoice->invoice_number}.pdf", ['mime' => 'application/pdf']);
            });
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec envoi facture manuelle', ['invoice' => $invoice->invoice_number, 'error' => $e->getMessage()]);
        }

        return back()->with('status', "Facture {$invoice->invoice_number} créée et envoyée à {$invoice->client_email}.");
    }

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
