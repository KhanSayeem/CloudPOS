<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ __('POS') }}</h2></x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded p-4">
            <livewire:pos.cart />
        </div>
    </div>
</x-app-layout>
