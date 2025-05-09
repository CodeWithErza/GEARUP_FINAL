<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Interior',
                'description' => 'Interior parts and accessories for vehicles'
            ],
            [
                'name' => 'Exterior',
                'description' => 'Exterior parts and accessories for vehicles'
            ],
            [
                'name' => 'Engine',
                'description' => 'Engine parts and components'
            ],
            [
                'name' => 'Under Chassis',
                'description' => 'Under chassis parts and components'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 