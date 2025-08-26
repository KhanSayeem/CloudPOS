@role('Admin')
    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
        {{ __('Products') }}
    </x-nav-link>
@endrole

@hasanyrole('Admin|Cashier')
    <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">
        {{ __('POS') }}
    </x-nav-link>

    <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
        {{ __('Sales') }}
    </x-nav-link>
@endhasanyrole
