<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::connection()->disableQueryLog();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ini_set('memory_limit', '2G');

        $startTime = microtime(true);
        $this->command->info('Starting mass data seeding with uniform distribution...');

        Model::withoutEvents(function () {
            $this->call([
                AuthorSeeder::class,
                CategorySeeder::class,
                BookSeeder::class,
                RatingSeeder::class
            ]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        $this->command->info("Seeding completed in {$duration} seconds");
        $this->showFinalStats();
    }

    private function showFinalStats()
    {
        $this->command->info("\n=== FINAL DATABASE STATISTICS ===");

        $stats = [
            'Authors' => DB::table('authors')->count(),
            'Categories' => DB::table('categories')->count(),
            'Books' => DB::table('books')->count(),
            'Ratings' => DB::table('ratings')->count(),
        ];

        foreach ($stats as $table => $count) {
            $this->command->info("{$table}: " . number_format($count));
        }

        $expectedRatingsPerBook = $stats['Ratings'] / $stats['Books'];
        $this->command->info("Expected avg ratings per book: " . round($expectedRatingsPerBook, 2));
    }
}
