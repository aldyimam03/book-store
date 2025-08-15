<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorIds   = Author::pluck('id')->all();
        $categoryIds = Category::pluck('id')->all();

        $totalAuthor = count($authorIds);
        $totalCategory = count($categoryIds);

        $totalFakeBooks = 100000;
        $chunk = 5000; 

        for ($i = 0; $i < $totalFakeBooks; $i += $chunk) {
            $batch = [];
            for ($j = 0; $j < $chunk && ($i + $j) < $totalFakeBooks; $j++) {
                $batch[] = [
                    'title'       => fake()->sentence(rand(2, 5)), 
                    'author_id'   => $authorIds[random_int(0, $totalAuthor - 1)],
                    'category_id' => $categoryIds[random_int(0, $totalCategory - 1)],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            DB::table('books')->insert($batch);
        }
    }
}
