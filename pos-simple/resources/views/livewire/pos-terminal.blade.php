<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow-xl rounded-lg p-6 lg:col-span-2 flex flex-col">
            <div class="flex items-end justify-between mb-4">
                <div class="flex-1">
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Search Products</label>
                    <input wire:model.debounce.400ms="q" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Name / SKU / barcode">
                </div>
                <button wire:click="clear" class="px-4 py-2 bg-red-50 text-red-700 rounded-lg border border-red-200 hover:bg-red-100 transition-colors shadow-md ml-3">
                    Clear Cart
                </button>
            </div>

            <div class="flex-1 overflow-y-auto" style="max-height: 70vh;">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse($products as $product)
                        <div wire:click="addProduct({{ $product->id }})" class="p-4 border border-gray-200 rounded-lg shadow-sm flex flex-col items-center text-center cursor-pointer transition hover:shadow-lg hover:border-indigo-400">
                            {{-- Image tag removed as requested --}}
                            <div class="font-semibold text-gray-800 text-sm truncate w-full">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 mb-2">SKU: {{ $product->sku }}</div>
                            <div class="font-bold text-lg text-green-600">${{ number_format($product->price, 2) }}</div>
                        </div>
                    @empty
                        <div class="col-span-full p-6 text-center text-gray-400 border border-dashed rounded-lg">
                            No products found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-6 lg:col-span-1 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Scan / Enter Code</label>
                    <div class="flex gap-2">
                        <input wire:model.defer="code" wire:keydown.enter="addByCode" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="SKU or Barcode">
                        <button wire:click="addByCode" class="px-5 py-3 bg-indigo-600 text-white rounded-lg transition hover:bg-indigo-700">Add</button>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4 mb-4 overflow-y-auto flex-1" style="max-height: 30vh;">
                <h4 class="font-bold text-gray-700 text-lg mb-2">Items</h4>
                <ul class="text-sm space-y-3">
                    @forelse($cart as $line)
                        <li class="flex justify-between items-center pb-2 border-b border-dashed last:border-b-0 last:pb-0">
                            <span class="truncate text-gray-800">{{ $line['name'] }} <span class="text-gray-500">× {{ $line['qty'] }}</span></span>
                            <span class="font-semibold text-gray-900">${{ number_format($line['line_total'] ?? ($line['qty'] * $line['price']), 2) }}</span>
                        </li>
                    @empty
                        <div class="p-4 text-center text-gray-400">
                            No items in cart.
                        </div>
                    @endforelse
                </ul>
            </div>

            <div class="space-y-3 text-sm pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center"><span class="text-gray-600">Subtotal</span><span class="font-semibold text-gray-800">${{ number_format($totals['subtotal'], 2) }}</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-600">Shipping</span><span class="font-medium text-gray-800">${{ number_format($totals['shipping'], 2) }}</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-600">Tax ({{ (int)($taxRate*100) }}%)</span><span class="font-medium text-gray-800">${{ number_format($totals['tax'], 2) }}</span></div>
                <div class="flex justify-between items-center text-xl font-bold pt-4 border-t border-gray-300 mt-4"><span>Grand Total</span><span class="text-green-600">${{ number_format($totals['total'], 2) }}</span></div>
            </div>

            <div class="mt-5">
                <div class="relative pt-1">
                    <div class="overflow-hidden h-2 mb-2 text-xs flex rounded bg-gray-200">
                        <div style="width: {{ $totals['progress'] }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-emerald-500 transition-all duration-500 ease-in-out"></div>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    @if($totals['progress'] < 100)
                        Add <span class="font-semibold text-indigo-600">${{ number_format(max(0, $minOrderThreshold - $totals['subtotal']), 2) }}</span> more to reach the recommended minimum of ${{ number_format($minOrderThreshold, 2) }}.
                    @else
                        <span class="text-green-600 font-semibold">✅ Cart ready for checkout.</span>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex items-end gap-3 justify-between">
                <div>
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Payment Method</label>
                    <select wire:model="method" class="w-full border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile">Mobile</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <button wire:click="checkout" class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl hover:bg-green-700 transition transform hover:scale-105" @disabled(empty($cart) || $totals['total'] <= 0)>Checkout</button>
            </div>
        </div>
    </div>
</div>