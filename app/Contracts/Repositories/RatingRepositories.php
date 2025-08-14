<?php

namespace App\Contracts\Repositories;

use App\Models\Book;
use App\Models\Rating;
use App\Contracts\Interfaces\RatingInterface;

class RatingRepositories implements RatingInterface
{
    public function __construct(protected Rating $rating, protected Book $book) {}

    public function create(int $bookId, int $score): Rating
    {
        return $this->rating->create(['book_id' => $bookId, 'score' => $score]);
    }

    /**
     * Get books of a given author, sorted by title.
     *
     * @param int $authorId
     * @return \Illuminate\Support\Collection<int, \App\Models\Book>
     */
    public function booksByAuthor(int $authorId)
    {
        return $this->book->where('author_id', $authorId)->orderBy('title')->get(['id', 'title']);
    }
}
