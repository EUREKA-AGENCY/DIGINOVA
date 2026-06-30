<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceCompany;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminInvoiceController extends Controller
{
    public function companies(): JsonResponse
    {
        return response()->json(
            InvoiceCompany::query()->orderBy('name')->get(['id', 'name', 'email', 'phone', 'bp', 'siege_social', 'address'])
        );
    }

    public function storeManual(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name'         => 'required|string|max:255',
            'client_email'        => 'required|email|max:255',
            'client_phone'        => 'nullable|string|max:50',
            'client_company'      => 'nullable|string|max:255',
            'client_bp'           => 'nullable|string|max:100',
            'client_siege_social' => 'nullable|string|max:255',
            'client_address'      => 'nullable|string|max:500',
            'save_company'        => 'boolean',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.unit_price'  => 'required|integer|min:0',
            'items.*.years'       => 'required|integer|min:1|max:10',
            'items.*.discount'    => 'required|integer|min:0|max:100',
        ]);

        // Calcul du total à partir des lignes
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += InvoiceItem::computeTotal($item['unit_price'], $item['years'], $item['discount']);
        }

        $invoice = DB::transaction(function () use ($validated, $total) {
            $inv = Invoice::create([
                'invoice_number'      => Invoice::generateNumber(),
                'client_name'         => $validated['client_name'],
                'client_email'        => $validated['client_email'],
                'client_phone'        => $validated['client_phone'] ?? null,
                'client_company'      => $validated['client_company'] ?? null,
                'client_bp'           => $validated['client_bp'] ?? null,
                'client_siege_social' => $validated['client_siege_social'] ?? null,
                'client_address'      => $validated['client_address'] ?? null,
                'item_name'           => $validated['items'][0]['description'],
                'unit_price'          => $validated['items'][0]['unit_price'],
                'years'               => $validated['items'][0]['years'],
                'discount_percent'    => $validated['items'][0]['discount'],
                'total_amount'        => $total,
            ]);

            foreach ($validated['items'] as $i => $item) {
                InvoiceItem::create([
                    'invoice_id'       => $inv->id,
                    'description'      => $item['description'],
                    'unit_price'       => $item['unit_price'],
                    'years'            => $item['years'],
                    'discount_percent' => $item['discount'],
                    'line_total'       => InvoiceItem::computeTotal($item['unit_price'], $item['years'], $item['discount']),
                    'sort_order'       => $i,
                ]);
            }

            return $inv;
        });

        // Sauvegarde du profil entreprise si demandée
        if ($validated['save_company'] ?? false) {
            InvoiceCompany::updateOrCreate(
                ['email' => $validated['client_email']],
                [
                    'name'         => $validated['client_company'] ?? $validated['client_name'],
                    'phone'        => $validated['client_phone'] ?? null,
                    'bp'           => $validated['client_bp'] ?? null,
                    'siege_social' => $validated['client_siege_social'] ?? null,
                    'address'      => $validated['client_address'] ?? null,
                ]
            );
        }

        $invoice->load('items');
        $this->sendInvoiceEmail($invoice);

        return back()->with('status', "Facture {$invoice->invoice_number} créée et envoyée à {$invoice->client_email}.");
    }

    public function index(): \Inertia\Response
    {
        $invoices = Invoice::query()->with('items')->orderByDesc('id')->get();

        return Inertia::render('Admin/Invoices', [
            'invoices' => $invoices->map(fn (Invoice $invoice) => [
                'id'                  => $invoice->id,
                'invoice_number'      => $invoice->invoice_number,
                'client_name'         => $invoice->client_name,
                'client_email'        => $invoice->client_email,
                'client_phone'        => $invoice->client_phone,
                'client_company'      => $invoice->client_company,
                'client_bp'           => $invoice->client_bp,
                'client_siege_social' => $invoice->client_siege_social,
                'client_address'      => $invoice->client_address,
                'item_name'           => $invoice->item_name,
                'years'               => $invoice->years,
                'total_amount'        => $invoice->total_amount,
                'status'              => $invoice->status,
                'created_at'          => $invoice->created_at->format('d/m/Y H:i'),
                'items'               => $invoice->items->map(fn ($item) => [
                    'id'               => $item->id,
                    'description'      => $item->description,
                    'unit_price'       => $item->unit_price,
                    'years'            => $item->years,
                    'discount_percent' => $item->discount_percent,
                    'line_total'       => $item->line_total,
                ]),
            ]),
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'client_name'         => 'required|string|max:255',
            'client_email'        => 'required|email|max:255',
            'client_phone'        => 'nullable|string|max:50',
            'client_company'      => 'nullable|string|max:255',
            'client_bp'           => 'nullable|string|max:100',
            'client_siege_social' => 'nullable|string|max:255',
            'client_address'      => 'nullable|string|max:500',
            'status'              => 'required|in:pending,paid,cancelled',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.unit_price'  => 'required|integer|min:0',
            'items.*.years'       => 'required|integer|min:1|max:10',
            'items.*.discount'    => 'required|integer|min:0|max:100',
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += InvoiceItem::computeTotal($item['unit_price'], $item['years'], $item['discount']);
        }

        DB::transaction(function () use ($invoice, $validated, $total) {
            $invoice->update([
                'client_name'         => $validated['client_name'],
                'client_email'        => $validated['client_email'],
                'client_phone'        => $validated['client_phone'] ?? null,
                'client_company'      => $validated['client_company'] ?? null,
                'client_bp'           => $validated['client_bp'] ?? null,
                'client_siege_social' => $validated['client_siege_social'] ?? null,
                'client_address'      => $validated['client_address'] ?? null,
                'item_name'           => $validated['items'][0]['description'],
                'unit_price'          => $validated['items'][0]['unit_price'],
                'years'               => $validated['items'][0]['years'],
                'discount_percent'    => $validated['items'][0]['discount'],
                'total_amount'        => $total,
                'status'              => $validated['status'],
                'paid_at'             => $validated['status'] === Invoice::STATUS_PAID ? ($invoice->paid_at ?? now()) : null,
            ]);

            $invoice->items()->delete();

            foreach ($validated['items'] as $i => $item) {
                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'description'      => $item['description'],
                    'unit_price'       => $item['unit_price'],
                    'years'            => $item['years'],
                    'discount_percent' => $item['discount'],
                    'line_total'       => InvoiceItem::computeTotal($item['unit_price'], $item['years'], $item['discount']),
                    'sort_order'       => $i,
                ]);
            }
        });

        return back()->with('status', "Facture {$invoice->invoice_number} mise à jour.");
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
        $invoice->load('items');
        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

        return $pdf->download("{$invoice->invoice_number}.pdf");
    }

    public function resend(Invoice $invoice): RedirectResponse
    {
        try {
            $invoice->load('items');
            $this->sendInvoiceEmail($invoice);

            return back()->with('status', "Facture {$invoice->invoice_number} renvoyée à {$invoice->client_email}.");
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec renvoi facture', ['invoice' => $invoice->invoice_number, 'error' => $e->getMessage()]);

            return back()->withErrors(['email' => "Échec de l'envoi : {$e->getMessage()}"]);
        }
    }

    private function sendInvoiceEmail(Invoice $invoice): void
    {
        try {
            $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

            Mail::send([], [], function ($message) use ($invoice, $pdf) {
                $message->to($invoice->client_email)
                    ->subject("Votre facture Diginova {$invoice->invoice_number}")
                    ->html(
                        "Bonjour {$invoice->client_name},<br><br>".
                        "Veuillez trouver ci-joint votre facture {$invoice->invoice_number} ".
                        "d'un montant total de ".number_format($invoice->total_amount, 0, ',', ' ').' F CFA.<br><br>'.
                        'Les modalités de paiement figurent sur la facture. Une fois le règlement effectué, merci de nous transmettre '.
                        'votre preuve de paiement via WhatsApp : <a href="https://wa.me/237655065494">+237 655 065 494</a>.<br><br>'.
                        "Cordialement,<br>L'équipe Diginova"
                    )
                    ->attachData($pdf->output(), "{$invoice->invoice_number}.pdf", ['mime' => 'application/pdf']);
            });
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Échec envoi facture', ['invoice' => $invoice->invoice_number, 'error' => $e->getMessage()]);
        }
    }
}
