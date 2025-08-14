<?php

namespace App\Contracts\Repositories;

use App\Models\Author;
use App\Contracts\Interfaces\AuthorInterface;

class AuthorRepositories implements AuthorInterface
{
    public function __construct(protected Author $author) {}
    public function top10ByVotersAbove5()
    {
        return $this->author->query()
            ->select('authors.id', 'authors.name')
            ->join('books', 'books.author_id', '=', 'authors.id')
            ->join('ratings', 'ratings.book_id', '=', 'books.id')
            ->groupBy('authors.id', 'authors.name')
            ->selectRaw('COUNT(ratings.id) as voters')
            ->having('voters', '>=', 5)
            ->orderByDesc('voters')
            ->take(10)
            ->get();
    }
}
