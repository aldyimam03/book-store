<?php

namespace App\Services;

use App\Contracts\Interfaces\RatingInterface;

class RatingService
{

    public function __construct(protected RatingInterface $ratingInterface) {}

    public function create(int $bookId, int $score)
    {
        return $this->ratingInterface->create($bookId, $score);
    }

    public function booksByAuthor(int $authorId)
    {
        return $this->ratingInterface->booksByAuthor($authorId);
    }
}
