@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Edit Product</h1>
  <form method="post" action="{{ route('products.update', $product) }}">
    @csrf @method('PUT')
    <label>SKU <input name="sku" value="{{ old('sku',$product->sku) }}"></label>@error('sku')<div>{{ $message }}</div>@enderror
    <label>Barcode <input name="barcode" value="{{ old('barcode',$product->barcode) }}"></label>
    <label>Name <input name="name" value="{{ old('name',$product->name) }}"></label>@error('name')<div>{{ $message }}</div>@enderror
    <label>Description <textarea name="description">{{ old('description',$product->description) }}</textarea></label>
    <label>Price <input type="number" step="0.01" name="price" value="{{ old('price',$product->price) }}"></label>@error('price')<div>{{ $message }}</div>@enderror
    <label>Stock <input type="number" name="stock" value="{{ old('stock',$product->stock) }}"></label>@error('stock')<div>{{ $message }}</div>@enderror
    <label><input type="checkbox" name="status" {{ old('status', $product->status) ? 'checked' : '' }}> Active</label>
    <button type="submit">Update</button>
    <a href="{{ route('products.index') }}">Cancel</a>
  </form>
</div>
@endsection
