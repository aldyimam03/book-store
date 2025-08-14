<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalFakeCategory = 3000;
        $category = Category::factory($totalFakeCategory)->make()->toArray();
        Category::insert($category);
    }
}
