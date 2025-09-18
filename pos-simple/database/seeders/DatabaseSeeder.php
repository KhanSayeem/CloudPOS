<?php

namespace Database\Seeders;

use App\Models\User;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,     // creates Admin, Cashier, Customer
            DemoSeeder::class,     // creates admin@example.com & cashier@example.com, assigns roles
            CustomerSeeder::class, // creates customer@example.com with Customer role
            ProductSeeder::class,  // creates 20 products
            SaleSeeder::class,     // creates 5 demo sales using products & cashier
        ]);
    }
}
