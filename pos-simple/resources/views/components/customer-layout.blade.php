<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Customer Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('customer.shop.index') }}" class="text-xl font-bold text-gray-800">
                            {{ config('app.name', 'POS Shop') }}
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('customer.shop.index') }}" class="text-gray-700 hover:text-gray-900">
                            Shop
                        </a>

                        <!-- Cart Link -->
                        <a href="{{ route('customer.cart.index') }}" class="relative text-gray-700 hover:text-gray-900" onclick="markCartAsViewed()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v-2a4 4 0 118 0v2"></path>
                            </svg>
                            @if(isset($cartCount) && $cartCount > 0)
                                <span id="cart-notification" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center animate-pulse">
                                    {{ $cartCount }}
                                </span>
                            @endif
                            <!-- New item indicator -->
                            @if(session('cart_updated'))
                                <span id="new-item-indicator" class="absolute -top-1 -right-1 bg-red-600 rounded-full w-3 h-3 animate-bounce"></span>
                            @endif
                        </a>

                        @auth
                            <!-- Customer Dashboard -->
                            <a href="{{ route('customer.dashboard') }}" class="text-gray-700 hover:text-gray-900">
                                Home
                            </a>

                            <!-- Orders -->
                            <a href="{{ route('customer.orders.index') }}" class="text-gray-700 hover:text-gray-900">
                                Orders
                            </a>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        function markCartAsViewed() {
            // Hide the new item indicator when cart is clicked
            const newItemIndicator = document.getElementById('new-item-indicator');
            if (newItemIndicator) {
                newItemIndicator.style.display = 'none';
            }

            // Store in localStorage that cart has been viewed
            localStorage.setItem('cartViewed', 'true');
        }

        // Check if cart was recently updated and show animation
        document.addEventListener('DOMContentLoaded', function() {
            const cartNotification = document.getElementById('cart-notification');
            const newItemIndicator = document.getElementById('new-item-indicator');

            // If there's a new item indicator, auto-hide it after 10 seconds
            if (newItemIndicator) {
                setTimeout(() => {
                    newItemIndicator.style.opacity = '0';
                    setTimeout(() => {
                        newItemIndicator.style.display = 'none';
                    }, 300);
                }, 10000);
            }
        });
    </script>
</body>
</html>