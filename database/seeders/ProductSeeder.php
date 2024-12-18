<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private const NUMBER_OF_RECORDS_TO_BE_CREATED = 20;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(self::NUMBER_OF_RECORDS_TO_BE_CREATED)->create();
    }
}
