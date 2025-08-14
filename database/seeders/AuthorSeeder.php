<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalFakeAuthors = 1000;
        $authors = Author::factory($totalFakeAuthors)->make()->toArray();
        Author::insert($authors);
    }
}
