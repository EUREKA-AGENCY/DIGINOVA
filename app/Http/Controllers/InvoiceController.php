<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * Prix catalogue (jamais fournis par le client) — doit rester synchronisé
     * avec les tarifs affichés sur resources/js/pages/Hebergement.vue.
     */
    private const CATALOG_PRICES = [
        'VPS Basic Unmanaged' => 62500,
        'VPS Premium Unmanaged' => 140000,
        'VPS Business Unmanaged' => 205000,
        'VPS Ultimate Unmanaged' => 376000,
    ];

    public function create(Request $request): Response
    {
        $itemName = $request->query('plan');
        $years = max(1, (int) $request->query('years', 1));

        $unitPrice = self::CATALOG_PRICES[$itemName] ?? null;

        return Inertia::render('Hebergement/Commander', [
            'plan' => $unitPrice ? [
                'item_name' => $itemName,
                'unit_price' => $unitPrice,
                'years' => $years,
            ] : null,
            'invoiceSent' => $request->session()->get('invoiceSent'),
        ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $unitPrice = self::CATALOG_PRICES[$validated['item_name']];
        $years = $validated['years'];

        ['discount_percent' => $discountPercent, 'total_amount' => $totalAmount] = Invoice::calculateTotal($unitPrice, $years);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'] ?? null,
            'client_company' => $validated['client_company'] ?? null,
            'item_name' => $validated['item_name'],
            'unit_price' => $unitPrice,
            'years' => $years,
            'discount_percent' => $discountPercent,
            'total_amount' => $totalAmount,
        ]);

        $this->emailInvoice($invoice);
        $this->notifyDiginova($invoice);

        return redirect()->route('hebergement.commander.create', [
            'plan' => $invoice->item_name,
            'years' => $invoice->years,
        ])->with('invoiceSent', [
            'number' => $invoice->invoice_number,
            'email' => $invoice->client_email,
            'total' => $invoice->total_amount,
        ]);
    }

    private function emailInvoice(Invoice $invoice): void
    {
        try {
            $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

            Mail::send([], [], function ($message) use ($invoice, $pdf) {
                $message->to($invoice->client_email)
                    ->subject("Votre facture Diginova {$invoice->invoice_number}")
                    ->html(
                        "Bonjour {$invoice->client_name},<br><br>".
                        "Veuillez trouver ci-joint votre facture {$invoice->invoice_number} pour {$invoice->item_name} ".
                        "({$invoice->years} an(s)) d'un montant de ".number_format($invoice->total_amount, 0, ',', ' ').' F CFA.<br><br>'.
                        'Les modalités de paiement figurent sur la facture. Une fois le règlement effectué, merci de nous transmettre '.
                        'votre preuve de paiement via WhatsApp : <a href="https://wa.me/237655065494">+237 655 065 494</a>.<br><br>'.
                        "Cordialement,<br>L'équipe Diginova"
                    )
                    ->attachData($pdf->output(), "{$invoice->invoice_number}.pdf", ['mime' => 'application/pdf']);
            });
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec envoi facture au client', ['invoice' => $invoice->invoice_number, 'error' => $e->getMessage()]);
        }
    }

    private function notifyDiginova(Invoice $invoice): void
    {
        $body = "Nouvelle commande hébergement sur diginova.cm\n\n"
            ."Facture : {$invoice->invoice_number}\n"
            ."Client : {$invoice->client_name} ({$invoice->client_email})\n"
            ."Téléphone : {$invoice->client_phone}\n"
            ."Entreprise : {$invoice->client_company}\n"
            ."Offre : {$invoice->item_name} — {$invoice->years} an(s)\n"
            .'Montant : '.number_format($invoice->total_amount, 0, ',', ' ').' F CFA';

        try {
            Mail::raw($body, function ($message) {
                $message->to('contact@diginova.cm')->subject('Nouvelle commande hébergement VPS');
            });
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec notification commande hébergement', ['error' => $e->getMessage()]);
        }
    }
}
