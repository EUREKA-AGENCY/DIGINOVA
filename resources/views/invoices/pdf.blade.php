<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; margin: 0; padding: 40px; }
        .header { display: table; width: 100%; margin-bottom: 30px; }
        .header-left { display: table-cell; vertical-align: top; }
        .header-right { display: table-cell; vertical-align: top; text-align: right; }
        .brand { font-size: 20px; font-weight: bold; color: #0A2422; }
        .muted { color: #64748b; }
        .invoice-title { font-size: 22px; font-weight: bold; color: #0A2422; margin-bottom: 4px; }
        .section-title { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #1D5457; font-weight: bold; margin-bottom: 6px; }
        .box { background: #f8fafc; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background: #0D2B29; color: #ffffff; text-align: left; padding: 10px 12px; font-size: 11px; }
        table.items td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        table.items tr.total td { font-weight: bold; font-size: 14px; border-bottom: none; color: #0A2422; }
        .badge { display: inline-block; background: #30998A; color: #ffffff; font-size: 10px; font-weight: bold; padding: 3px 10px; border-radius: 10px; text-transform: uppercase; }
        .footer { margin-top: 40px; font-size: 10px; color: #94a3b8; text-align: center; }
        .payment-row { display: table; width: 100%; margin-bottom: 6px; }
        .payment-label { display: table-cell; font-weight: bold; color: #0A2422; width: 50%; }
        .payment-value { display: table-cell; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="brand">Diginova</div>
            <div class="muted">Yaoundé, Cameroun</div>
            <div class="muted">contact@diginova.cm · +237 655 065 494</div>
        </div>
        <div class="header-right">
            <div class="invoice-title">FACTURE</div>
            <div class="muted">{{ $invoice->invoice_number }}</div>
            <div class="muted">{{ $invoice->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="box">
        <div class="section-title">Facturé à</div>
        <div><strong>{{ $invoice->client_name }}</strong></div>
        @if($invoice->client_company)
            <div>{{ $invoice->client_company }}</div>
        @endif
        <div class="muted">{{ $invoice->client_email }}</div>
        @if($invoice->client_phone)
            <div class="muted">{{ $invoice->client_phone }}</div>
        @endif
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th>Durée</th>
                <th>Prix unitaire / an</th>
                <th>Remise</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->item_name }}</td>
                <td>{{ $invoice->years }} an{{ $invoice->years > 1 ? 's' : '' }}</td>
                <td>{{ number_format($invoice->unit_price, 0, ',', ' ') }} F</td>
                <td>{{ $invoice->discount_percent }}%</td>
                <td>{{ number_format($invoice->total_amount, 0, ',', ' ') }} F</td>
            </tr>
            <tr class="total">
                <td colspan="4" style="text-align: right;">Total à payer</td>
                <td>{{ number_format($invoice->total_amount, 0, ',', ' ') }} F CFA</td>
            </tr>
        </tbody>
    </table>

    <div class="box">
        <div class="section-title">Modalités de paiement</div>
        <div class="payment-row">
            <div class="payment-label">Virement bancaire AFB</div>
            <div class="payment-value">10005 000040 06924451051 04</div>
        </div>
        <div class="payment-row">
            <div class="payment-label">Orange Money</div>
            <div class="payment-value">655065494</div>
        </div>
        <div class="payment-row">
            <div class="payment-label">MTN MoMo</div>
            <div class="payment-value">682267026</div>
        </div>
        <p class="muted" style="margin-top: 12px; margin-bottom: 0;">
            Paiement à effectuer au nom de <strong>PENLAP KAMDEM SATURIN</strong>. Merci de nous transmettre votre preuve de paiement par WhatsApp (+237 655 065 494) une fois le règlement effectué.
        </p>
    </div>

    <div class="footer">
        Diginova — Agence de développement web et solutions SaaS, Yaoundé, Cameroun.<br>
        Cette facture est générée automatiquement et reste valable jusqu'au règlement.
    </div>
</body>
</html>
