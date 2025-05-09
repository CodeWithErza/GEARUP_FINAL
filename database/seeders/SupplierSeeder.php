<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the supplier seeder.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'supplier_code' => 'MAP-001',
                'name' => 'Metro Auto Parts Inc.',
                'contact_person' => 'Carlos Rodriguez',
                'position' => 'Sales Manager',
                'phone' => '+63 912 345 6789',
                'email' => 'carlos@metroautoparts.com',
                'address' => '123 Main Avenue, Makati City, Metro Manila, Philippines',
                'payment_terms' => '30days',
                'status' => 'active',
                'notes' => 'Reliable supplier for engine components and brake systems. Offers volume discounts on orders above ₱50,000.'
            ],
            [
                'supplier_code' => 'GT-231',
                'name' => 'GearTech Solutions',
                'contact_person' => 'Maria Santos',
                'position' => 'Account Executive',
                'phone' => '+ 63 917 987 6543',
                'email' => 'maria@geartechsolutions.ph',
                'address' => '456 Technology Drive, BGC, Taguig City, Philippines',
                'payment_terms' => '15days',
                'status' => 'active',
                'notes' => 'Premium supplier for electronic components and sensors. Fast delivery and excellent quality control.'
            ],
            [
                'supplier_code' => 'PPM-123',
                'name' => 'Pinoy Parts Manufacturing',
                'contact_person' => 'Juan dela Cruz',
                'position' => 'Owner',
                'phone' => '+63 908 765 4321',
                'email' => 'juan@pinoymade.ph',
                'address' => '567 Industrial Zone, Calamba City, Laguna, Philippines',
                'payment_terms' => '30days',
                'status' => 'on_hold',
                'notes' => 'Local manufacturer of aftermarket parts. Currently experiencing production delays. Expected to resume normal operations next month.'
            ],
            [
                'supplier_code' => 'JIAP-88',
                'name' => 'Japanese Import Auto Parts',
                'contact_person' => 'Hiroshi Yamada',
                'position' => 'International Sales Director',
                'phone' => '+63 922 567 8901',
                'email' => 'hiroshi@japanesepartsimport.com',
                'address' => '321 Asia Avenue, Ortigas Center, Pasig City, Philippines',
                'payment_terms' => 'cod',
                'status' => 'active',
                'notes' => 'Specializes in genuine and OEM parts for Japanese car brands. Direct import from Japan with 1-2 weeks lead time.'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
} 