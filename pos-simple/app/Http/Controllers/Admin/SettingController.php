<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private $settings = [
        'store_name' => 'CloudPOS Store',
        'store_address' => '',
        'store_phone' => '',
        'store_email' => '',
        'currency_symbol' => '$',
        'currency_code' => 'USD',
        'tax_rate' => '0.00',
        'tax_name' => 'Tax',
        'receipt_header' => 'Thank you for your business!',
        'receipt_footer' => 'Please come again!',
        'low_stock_threshold' => '10',
        'backup_frequency' => 'daily',
        'enable_notifications' => '1',
        'enable_email_receipts' => '0',
        'default_payment_method' => 'cash'
    ];

    public function index()
    {
        $currentSettings = $this->getCurrentSettings();
        return view('admin.settings.index', compact('currentSettings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'currency_symbol' => 'required|string|max:5',
            'currency_code' => 'required|string|max:3',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_name' => 'required|string|max:50',
            'receipt_header' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:500',
            'low_stock_threshold' => 'required|integer|min:0',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'enable_notifications' => 'boolean',
            'enable_email_receipts' => 'boolean',
            'default_payment_method' => 'required|in:cash,card,digital'
        ]);

        foreach ($validated as $key => $value) {
            Cache::forever("setting_{$key}", $value);
        }

        // Handle logo upload if present
        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('logos', 'public');
            Cache::forever('setting_store_logo', $logoPath);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    private function getCurrentSettings()
    {
        $currentSettings = [];
        foreach ($this->settings as $key => $defaultValue) {
            $currentSettings[$key] = Cache::get("setting_{$key}", $defaultValue);
        }
        
        // Add logo path if exists
        $currentSettings['store_logo'] = Cache::get('setting_store_logo', null);
        
        return $currentSettings;
    }

    // Helper method to get a specific setting (can be used throughout the app)
    public static function get($key, $default = null)
    {
        return Cache::get("setting_{$key}", $default);
    }

    public function export()
    {
        $settings = $this->getCurrentSettings();
        $filename = 'pos-settings-' . date('Y-m-d-H-i-s') . '.json';
        
        $content = json_encode($settings, JSON_PRETTY_PRINT);
        
        return response($content)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json'
        ]);

        $content = file_get_contents($request->file('settings_file')->path());
        $importedSettings = json_decode($content, true);

        if (!$importedSettings) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Invalid settings file format.');
        }

        $imported = 0;
        foreach ($importedSettings as $key => $value) {
            if (array_key_exists($key, $this->settings)) {
                Cache::forever("setting_{$key}", $value);
                $imported++;
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', "Successfully imported {$imported} settings.");
    }

    public function backup()
    {
        // This would integrate with your backup system
        // For now, just return a success message
        return redirect()->route('admin.settings.index')
            ->with('success', 'Backup initiated successfully.');
    }
}