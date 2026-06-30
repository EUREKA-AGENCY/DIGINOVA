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
        .client-boxes { display: table; width: 100%; margin-bottom: 20px; }
        .client-box-left { display: table-cell; vertical-align: top; width: 55%; padding-right: 16px; }
        .client-box-right { display: table-cell; vertical-align: top; background: #f8fafc; border-radius: 8px; padding: 14px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background: #0D2B29; color: #ffffff; text-align: left; padding: 10px 12px; font-size: 11px; }
        table.items th.right { text-align: right; }
        table.items td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        table.items td.right { text-align: right; }
        table.items tr.subtotal td { border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; }
        table.items tr.total td { font-weight: bold; font-size: 14px; border-bottom: none; color: #0A2422; background: #f8fafc; }
        .badge { display: inline-block; background: #30998A; color: #ffffff; font-size: 10px; font-weight: bold; padding: 3px 10px; border-radius: 10px; text-transform: uppercase; }
        .footer { margin-top: 40px; font-size: 10px; color: #94a3b8; text-align: center; }
        .payment-row { display: table; width: 100%; margin-bottom: 6px; }
        .payment-label { display: table-cell; font-weight: bold; color: #0A2422; width: 50%; }
        .payment-value { display: table-cell; text-align: right; }
        .label { font-size: 10px; color: #94a3b8; margin-bottom: 1px; }
    </style>
</head>
<body>
    <!-- En-tête -->
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

    <!-- Bloc client -->
    <div class="client-boxes">
        <div class="client-box-left">
            <div class="section-title">Facturé à</div>
            <div><strong>{{ $invoice->client_name }}</strong></div>
            @if($invoice->client_company)
                <div style="font-weight:600;">{{ $invoice->client_company }}</div>
            @endif
            @if($invoice->client_siege_social)
                <div class="muted" style="font-size:11px;">Siège : {{ $invoice->client_siege_social }}</div>
            @endif
            @if($invoice->client_address)
                <div class="muted" style="font-size:11px;">{{ $invoice->client_address }}</div>
            @endif
            @if($invoice->client_bp)
                <div class="muted" style="font-size:11px;">{{ $invoice->client_bp }}</div>
            @endif
            <div class="muted" style="margin-top:6px;">{{ $invoice->client_email }}</div>
            @if($invoice->client_phone)
                <div class="muted">{{ $invoice->client_phone }}</div>
            @endif
        </div>
        <div class="client-box-right">
            <div class="section-title">Détails de la facture</div>
            <div class="label">Numéro</div>
            <div style="font-weight:600; margin-bottom:8px;">{{ $invoice->invoice_number }}</div>
            <div class="label">Date d'émission</div>
            <div style="margin-bottom:8px;">{{ $invoice->created_at->format('d/m/Y') }}</div>
            <div class="label">Statut</div>
            <div><span class="badge">{{ $invoice->status === 'paid' ? 'Payée' : 'En attente' }}</span></div>
        </div>
    </div>

    <!-- Tableau des lignes -->
    @php
        $items = $invoice->displayItems();
    @endphp
    <table class="items">
        <thead>
            <tr>
                <th style="width:45%;">Description</th>
                <th>Durée</th>
                <th class="right">Prix unit./an</th>
                <th class="right">Remise</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->years }} an{{ $item->years > 1 ? 's' : '' }}</td>
                <td class="right">{{ number_format($item->unit_price, 0, ',', ' ') }} F</td>
                <td class="right">{{ $item->discount_percent }}%</td>
                <td class="right">{{ number_format($item->line_total, 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="4" style="text-align:right; padding-right:12px;">Total à payer</td>
                <td class="right">{{ number_format($invoice->total_amount, 0, ',', ' ') }} F CFA</td>
            </tr>
        </tbody>
    </table>

    <!-- Modalités de paiement -->
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
