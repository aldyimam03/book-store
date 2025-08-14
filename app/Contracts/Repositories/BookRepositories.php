<?php

namespace App\Contracts\Repositories;

use App\Models\Book;
use App\Contracts\Interfaces\BookInterface;


class BookRepositories implements BookInterface
{
    public function __construct(protected Book $book) {}
    public function listWithStats(int $perPage, ?string $search)
    {
        $q = $this->book->query()
            ->select([
                'books.id',
                'books.title',
                'authors.name as author_name',
                'categories.name as category_name'
            ])
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->leftJoin('ratings', 'ratings.book_id', '=', 'books.id')
            ->when($search, function ($qq, $s) {
                $s = "%" . strtolower($s) . "%";
                $qq->where(function ($w) use ($s) {
                    $w->whereRaw('LOWER(books.title) like ?', [$s])
                        ->orWhereRaw('LOWER(authors.name) like ?', [$s]);
                });
            })
            ->groupBy([
                'books.id',
                'books.title',
                'books.author_id',  
                'books.category_id', 
                'authors.name',
                'categories.name'
            ])
            ->selectRaw('ROUND(COALESCE(AVG(ratings.score), 0), 2) as avg_rating')
            ->selectRaw('COUNT(ratings.id) as voters')
            ->orderByDesc('avg_rating')
            ->orderByDesc('voters');

        return $q->paginate($perPage <= 0 ? 10 : $perPage);
    }
}
