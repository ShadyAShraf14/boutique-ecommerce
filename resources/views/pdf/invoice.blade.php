<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#111; }
        .container { padding: 18px; }
        .top { display:flex; justify-content:space-between; align-items:flex-start; }
        .brand { font-size: 18px; font-weight: 700; letter-spacing: .5px; }
        .muted { color:#666; }
        .box { border:1px solid #eee; padding:12px; border-radius:8px; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th, td { border-bottom:1px solid #eee; padding:8px 6px; text-align:left; }
        th { background:#fafafa; font-weight:700; }
        .right { text-align:right; }
        .total { font-size: 13px; font-weight:700; }
        .badge { display:inline-block; padding:3px 8px; border-radius:999px; background:#f5f5f5; font-size:11px; }
        .mt { margin-top:12px; }
    </style>
</head>
<body>
<div class="container">

    <div class="top">
        <div>
            <div class="brand">BOUTIQUE</div>
            <div class="muted">Invoice</div>
        </div>

        <div class="right">
            <div><strong>Invoice #</strong> {{ $invoice->invoice_number }}</div>
            <div><strong>Version</strong> {{ $invoice->version }}</div>
            <div><strong>Date</strong> {{ $invoice->issued_at?->format('Y-m-d H:i') }}</div>
            <div class="mt">
                <span class="badge">Order #{{ $order->id }}</span>
                <span class="badge">{{ strtoupper($order->payment_status) }}</span>
            </div>
        </div>
    </div>

    <div class="mt box">
        <strong>Billed To</strong><br>
        {{ $invoice->snapshot['customer']['name'] ?? '' }}<br>
        <span class="muted">{{ $invoice->snapshot['customer']['email'] ?? '' }}</span><br><br>

        <strong>Shipping Address</strong><br>
        {{ $invoice->snapshot['address']['line1'] ?? '' }}<br>
        @if(!empty($invoice->snapshot['address']['line2']))
            {{ $invoice->snapshot['address']['line2'] ?? '' }}<br>
        @endif
        {{ $invoice->snapshot['address']['city'] ?? '' }},
        {{ $invoice->snapshot['address']['state'] ?? '' }},
        {{ $invoice->snapshot['address']['country'] ?? '' }}<br>
        <span class="muted">{{ $invoice->snapshot['address']['phone'] ?? '' }}</span>
    </div>

    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th class="right">Price</th>
            <th class="right">Qty</th>
            <th class="right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $it)
            <tr>
                <td>{{ $it->product_name }}</td>
                <td class="right">{{ number_format((float)$it->price, 2) }}</td>
                <td class="right">{{ (int)$it->quantity }}</td>
                <td class="right">{{ number_format((float)$it->total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="mt">
        <tbody>
        <tr>
            <td class="right muted">Subtotal</td>
            <td class="right">{{ number_format((float)$order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="right muted">Discount</td>
            <td class="right">- {{ number_format((float)$order->discount, 2) }}</td>
        </tr>
        <tr>
            <td class="right muted">Tax</td>
            <td class="right">{{ number_format((float)$order->tax, 2) }}</td>
        </tr>
        <tr>
            <td class="right muted">Shipping</td>
            <td class="right">{{ number_format((float)$order->shipping_cost, 2) }}</td>
        </tr>
        <tr>
            <td class="right total">Grand Total</td>
            <td class="right total">{{ number_format((float)$order->total, 2) }}</td>
        </tr>
        </tbody>
    </table>

    <div class="mt muted" style="font-size:11px;">
        This invoice is generated electronically and is valid without signature.
    </div>

</div>
</body>
</html>
