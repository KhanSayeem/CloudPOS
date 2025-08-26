<div class="container py-4">
  <h1 class="mb-3">POS</h1>

  <div class="mb-3">
    <input type="text" class="form-control" placeholder="Search by name/SKU/barcode"
           wire:model.debounce.300ms="search">
  </div>

  @if($products->count())
    <div class="mb-3">
      @foreach($products as $p)
        <button class="btn btn-sm btn-outline-primary me-2 mb-2" wire:click="add({{ $p->id }})">
          + {{ $p->name }} ({{ $p->sku }})
        </button>
      @endforeach
    </div>
  @endif

  <table class="table table-bordered">
    <thead><tr>
      <th>Product</th><th>Price</th><th>Qty</th><th>Discount</th><th>Line total</th><th></th>
    </tr></thead>
    <tbody>
      @forelse($items as $pid => $row)
        <tr>
          <td>{{ $row['name'] }}</td>
          <td>{{ number_format($row['price'],2) }}</td>
          <td><input type="number" min="1" wire:change="updateQty({{ $pid }}, $event.target.value)" value="{{ $row['qty'] }}" style="width:80px"></td>
          <td><input type="number" step="0.01" wire:model.lazy="items.{{ $pid }}.discount" style="width:100px"></td>
          <td>{{ number_format(($row['price']*$row['qty'])-($row['discount'] ?? 0),2) }}</td>
          <td><button class="btn btn-sm btn-danger" wire:click="remove({{ $pid }})">x</button></td>
        </tr>
      @empty
        <tr><td colspan="6">No items yet.</td></tr>
      @endforelse
    </tbody>
  </table>

  @php($t = $totals)
  <div class="d-flex justify-content-end gap-3">
    <div><strong>Subtotal:</strong> {{ number_format($t['subtotal'],2) }}</div>
    <div><strong>Tax:</strong> {{ number_format($t['tax'],2) }}</div>
    <div><strong>Total:</strong> {{ number_format($t['total'],2) }}</div>
  </div>
</div>
