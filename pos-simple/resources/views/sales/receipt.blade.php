<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Receipt #{{ $sale->id }}</title>
  <style>body{font-family: DejaVu Sans, sans-serif; font-size:12px}</style>
</head>
<body>
  <h3>Simple POS</h3>
  <p>Receipt #{{ $sale->id }} — {{ $sale->created_at->format('Y-m-d H:i') }}</p>
  <hr>
  <table width="100%" cellspacing="0" cellpadding="4" border="1">
    <thead>
      <tr><th align="left">Item</th><th align="right">Qty</th><th align="right">Unit</th><th align="right">Disc</th><th align="right">Total</th></tr>
    </thead>
    <tbody>
      @foreach($sale->items as $i)
        <tr>
          <td>{{ $i->product?->name }}</td>
          <td align="right">{{ $i->qty }}</td>
          <td align="right">{{ number_format($i->unit_price,2) }}</td>
          <td align="right">{{ number_format($i->discount,2) }}</td>
          <td align="right">{{ number_format($i->line_total,2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <p align="right">Subtotal: {{ number_format($sale->subtotal,2) }}</p>
  <p align="right">Tax: {{ number_format($sale->tax_total,2) }}</p>
  <h3 align="right">Total: {{ number_format($sale->total,2) }}</h3>
  <p>Thank you!</p>
</body>
</html>
