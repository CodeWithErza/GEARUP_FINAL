<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the customer seeder.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Maria Santos',
                'phone' => '+63 912 345 6789',
                'email' => 'maria@example.com',
                'address' => null
            ],
            [
                'name' => 'Juan dela Cruz',
                'phone' => '+63 923 456 7890',
                'email' => 'juan@example.com',
                'address' => null
            ],
            [
                'name' => 'Eraj',
                'phone' => '+63 923 295 235-9',
                'email' => 'eraj@snlna',
                'address' => null
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 