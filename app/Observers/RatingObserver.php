<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Rating;

class RatingObserver
{
    /**
     * Handle the Rating "created" event.
     */
    public function created(Rating $rating): void
    {
        $rating->book()->increment('voters');

        $this->updateAverageRating($rating->book);
    }

    /**
     * Handle the Rating "updated" event.
     */
    public function updated(Rating $rating): void
    {
        $this->updateBookStats($rating->book_id);
        $this->updateAuthorStats($rating->book->author_id);

        if ($rating->isDirty('book_id')) {
            $this->updateBookStats($rating->getOriginal('book_id'));
            $oldBook = Book::find($rating->getOriginal('book_id'));
            if ($oldBook) {
                $this->updateAuthorStats($oldBook->author_id);
            }
        }
    }

    /**
     * Handle the Rating "deleted" event.
     */
    public function deleted(Rating $rating): void
    {
        //
    }

    /**
     * Handle the Rating "restored" event.
     */
    public function restored(Rating $rating): void
    {
        //
    }

    /**
     * Handle the Rating "force deleted" event.
     */
    public function forceDeleted(Rating $rating): void
    {
        //
    }

    private function updateAverageRating($book): void
    {
        $avgRating = $book->ratings()->avg('score');
        $book->update(['average_rating' => $avgRating ?? 0]);
    }

    private function updateBookStats(int $bookId): void
    {
        $book = \App\Models\Book::find($bookId);
        if ($book) {
            $book->updateRatingStats();
        }
    }

    private function updateAuthorStats(int $authorId): void
    {
        $author = \App\Models\Author::find($authorId);
        if ($author) {
            $author->updateRatingStats();
        }
    }
}
