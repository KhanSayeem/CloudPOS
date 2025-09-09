<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Settings Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button active" data-tab="store">
                                Store Information
                            </button>
                            <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button" data-tab="financial">
                                Financial Settings
                            </button>
                            <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button" data-tab="system">
                                System Settings
                            </button>
                            <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button" data-tab="backup">
                                Backup & Export
                            </button>
                        </nav>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Store Information Tab -->
                        <div class="tab-content" id="store-tab">
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="store_name" :value="__('Store Name')" />
                                    <x-text-input id="store_name" class="block mt-1 w-full" type="text" name="store_name" :value="old('store_name', $currentSettings['store_name'])" required />
                                    <x-input-error :messages="$errors->get('store_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="store_address" :value="__('Store Address')" />
                                    <textarea id="store_address" name="store_address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('store_address', $currentSettings['store_address']) }}</textarea>
                                    <x-input-error :messages="$errors->get('store_address')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="store_phone" :value="__('Phone Number')" />
                                        <x-text-input id="store_phone" class="block mt-1 w-full" type="text" name="store_phone" :value="old('store_phone', $currentSettings['store_phone'])" />
                                        <x-input-error :messages="$errors->get('store_phone')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="store_email" :value="__('Store Email')" />
                                        <x-text-input id="store_email" class="block mt-1 w-full" type="email" name="store_email" :value="old('store_email', $currentSettings['store_email'])" />
                                        <x-input-error :messages="$errors->get('store_email')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="store_logo" :value="__('Store Logo')" />
                                    <input id="store_logo" type="file" name="store_logo" accept="image/*" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @if($currentSettings['store_logo'])
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($currentSettings['store_logo']) }}" alt="Store Logo" class="h-20 w-20 object-cover rounded">
                                            <p class="text-sm text-gray-500 mt-1">Current logo</p>
                                        </div>
                                    @endif
                                    <x-input-error :messages="$errors->get('store_logo')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Financial Settings Tab -->
                        <div class="tab-content hidden" id="financial-tab">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="currency_symbol" :value="__('Currency Symbol')" />
                                        <x-text-input id="currency_symbol" class="block mt-1 w-full" type="text" name="currency_symbol" :value="old('currency_symbol', $currentSettings['currency_symbol'])" required />
                                        <x-input-error :messages="$errors->get('currency_symbol')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="currency_code" :value="__('Currency Code')" />
                                        <x-text-input id="currency_code" class="block mt-1 w-full" type="text" name="currency_code" :value="old('currency_code', $currentSettings['currency_code'])" required />
                                        <x-input-error :messages="$errors->get('currency_code')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="tax_rate" :value="__('Tax Rate (%)')" />
                                        <x-text-input id="tax_rate" class="block mt-1 w-full" type="number" step="0.01" name="tax_rate" :value="old('tax_rate', $currentSettings['tax_rate'])" required />
                                        <x-input-error :messages="$errors->get('tax_rate')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="tax_name" :value="__('Tax Name')" />
                                        <x-text-input id="tax_name" class="block mt-1 w-full" type="text" name="tax_name" :value="old('tax_name', $currentSettings['tax_name'])" required />
                                        <x-input-error :messages="$errors->get('tax_name')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="default_payment_method" :value="__('Default Payment Method')" />
                                    <select id="default_payment_method" name="default_payment_method" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="cash" {{ old('default_payment_method', $currentSettings['default_payment_method']) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{ old('default_payment_method', $currentSettings['default_payment_method']) == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="digital" {{ old('default_payment_method', $currentSettings['default_payment_method']) == 'digital' ? 'selected' : '' }}>Digital</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('default_payment_method')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="receipt_header" :value="__('Receipt Header Text')" />
                                    <textarea id="receipt_header" name="receipt_header" rows="2" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('receipt_header', $currentSettings['receipt_header']) }}</textarea>
                                    <x-input-error :messages="$errors->get('receipt_header')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="receipt_footer" :value="__('Receipt Footer Text')" />
                                    <textarea id="receipt_footer" name="receipt_footer" rows="2" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('receipt_footer', $currentSettings['receipt_footer']) }}</textarea>
                                    <x-input-error :messages="$errors->get('receipt_footer')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- System Settings Tab -->
                        <div class="tab-content hidden" id="system-tab">
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="low_stock_threshold" :value="__('Low Stock Alert Threshold')" />
                                    <x-text-input id="low_stock_threshold" class="block mt-1 w-full" type="number" name="low_stock_threshold" :value="old('low_stock_threshold', $currentSettings['low_stock_threshold'])" required />
                                    <p class="mt-1 text-sm text-gray-600">Alert when product stock falls below this number</p>
                                    <x-input-error :messages="$errors->get('low_stock_threshold')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="backup_frequency" :value="__('Backup Frequency')" />
                                    <select id="backup_frequency" name="backup_frequency" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="daily" {{ old('backup_frequency', $currentSettings['backup_frequency']) == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('backup_frequency', $currentSettings['backup_frequency']) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('backup_frequency', $currentSettings['backup_frequency']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('backup_frequency')" class="mt-2" />
                                </div>

                                <div class="flex items-center">
                                    <input id="enable_notifications" type="checkbox" name="enable_notifications" value="1" {{ old('enable_notifications', $currentSettings['enable_notifications']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="enable_notifications" class="ml-2 block text-sm text-gray-900">
                                        Enable system notifications
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input id="enable_email_receipts" type="checkbox" name="enable_email_receipts" value="1" {{ old('enable_email_receipts', $currentSettings['enable_email_receipts']) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="enable_email_receipts" class="ml-2 block text-sm text-gray-900">
                                        Enable email receipts
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Backup & Export Tab -->
                        <div class="tab-content hidden" id="backup-tab">
                            <div class="space-y-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Export Settings</h3>
                                    <p class="text-sm text-gray-600 mb-4">Download your current settings as a JSON file for backup or transfer purposes.</p>
                                    <a href="{{ route('admin.settings.export') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Export Settings
                                    </a>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Import Settings</h3>
                                    <p class="text-sm text-gray-600 mb-4">Upload a previously exported settings file to restore configuration.</p>
                                    <input type="file" name="import_file" accept=".json" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">System Backup</h3>
                                    <p class="text-sm text-gray-600 mb-4">Create a full system backup including database and files.</p>
                                    <button type="button" onclick="initiateBackup()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Create Backup
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Update Settings') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-indigo-500', 'text-indigo-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    tabContents.forEach(content => content.classList.add('hidden'));
                    
                    // Add active class to clicked button and show content
                    this.classList.add('border-indigo-500', 'text-indigo-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    document.getElementById(targetTab + '-tab').classList.remove('hidden');
                });
            });
            
            // Set first tab as active by default
            tabButtons[0].classList.add('border-indigo-500', 'text-indigo-600');
            tabButtons[0].classList.remove('border-transparent', 'text-gray-500');
        });
        
        function initiateBackup() {
            if (confirm('Are you sure you want to create a system backup? This may take several minutes.')) {
                fetch('{{ route("admin.settings.backup") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    if (response.ok) {
                        alert('Backup initiated successfully!');
                    } else {
                        alert('Error initiating backup. Please try again.');
                    }
                });
            }
        }
    </script>
</x-app-layout>