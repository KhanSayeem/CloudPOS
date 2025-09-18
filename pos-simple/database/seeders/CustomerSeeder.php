<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test customer user
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('password'),
            ]
        );

        // Assign Customer role if not already assigned
        $customerRole = Role::where('name', 'Customer')->first();
        if ($customerRole && !$customer->hasRole('Customer')) {
            $customer->assignRole($customerRole);
        }
    }
}