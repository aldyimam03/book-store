<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating ratings with uniform distribution...');

        $bookIds = Book::pluck('id')->all();
        $totalBooks = count($bookIds);

        if ($totalBooks == 0) {
            $this->command->error('No books found! Please run BookSeeder first.');
            return;
        }

        $totalFakeRating = 500000;
        $chunk = 10000;

        $this->command->info("Total books: {$totalBooks}");
        $this->command->info("Creating {$totalFakeRating} ratings...");

        for ($i = 0; $i < $totalFakeRating; $i += $chunk) {
            $batch = [];

            for ($j = 0; $j < $chunk && ($i + $j) < $totalFakeRating; $j++) {
                $selectedBookId = $bookIds[array_rand($bookIds)];

                $batch[] = [
                    'book_id'    => $selectedBookId,
                    'score'      => $this->generateRealisticScore(),
                    'created_at' => $this->generateRandomDate(),
                    'updated_at' => now(),
                ];
            }

            DB::table('ratings')->insert($batch);
            $this->command->info("Inserted batch: " . ($i + count($batch)) . "/{$totalFakeRating}");
        }

        $this->showRatingStats();
    }

    /**
     * Generate realistic rating score (lebih banyak rating tinggi)
     */
    private function generateRealisticScore(): int
    {
        $rand = rand(1, 100);

        if ($rand <= 30) return rand(8, 10);  // 30% rating tinggi
        if ($rand <= 55) return rand(6, 7);   // 25% rating sedang-tinggi
        if ($rand <= 75) return rand(4, 5);   // 20% rating sedang
        return rand(1, 3);                    // 25% rating rendah
    }

    /**
     * Generate random date 
     */
    private function generateRandomDate()
    {
        return now()->subDays(rand(1, 730));
    }

    /**
     * Show statistics 
     */
    private function showRatingStats()
    {
        $totalRatings = DB::table('ratings')->count();

        $scoreStats = DB::select("
            SELECT 
                score,
                COUNT(*) as count
            FROM ratings 
            GROUP BY score 
            ORDER BY score
        ");

        $this->command->info("\n=== RATING SCORE DISTRIBUTION ===");
        foreach ($scoreStats as $stat) {
            $percentage = round(($stat->count / $totalRatings) * 100, 1);
            $this->command->info("Score {$stat->score}: {$stat->count} ({$percentage}%)");
        }

        $totalBooks = DB::table('books')->count();
        $booksWithRatings = DB::table('ratings')->distinct('book_id')->count();
        $booksWithoutRatings = $totalBooks - $booksWithRatings;

        if ($booksWithoutRatings > 0) {
            $this->command->info("Books with 0 ratings: {$booksWithoutRatings}");
        }

        $topAuthors = DB::select("
            SELECT 
                authors.name,
                COUNT(ratings.id) as total_voters
            FROM authors
            JOIN books ON books.author_id = authors.id
            JOIN ratings ON ratings.book_id = books.id
            GROUP BY authors.id, authors.name
            HAVING total_voters >= 50
            ORDER BY total_voters DESC
            LIMIT 10
        ");

        $this->command->info("\n=== TOP AUTHORS PREVIEW ===");
        if (count($topAuthors) > 0) {
            foreach ($topAuthors as $index => $author) {
                $this->command->info(($index + 1) . ". {$author->name}: {$author->total_voters} voters");
            }
        } else {
            $this->command->warn("No authors found with 50+ voters yet.");
        }
    }
}
