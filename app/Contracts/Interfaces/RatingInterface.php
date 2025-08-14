<?php

namespace App\Contracts\Interfaces;

interface RatingInterface
{
    public function create(int $bookId, int $score);
    public function booksByAuthor(int $authorId);
}
