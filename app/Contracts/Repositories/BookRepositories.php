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

            ->addSelect([
                'avg_rating' => function ($query) {
                    $query->selectRaw('ROUND(COALESCE(AVG(ratings.score), 0), 2)')
                        ->from('ratings')
                        ->whereColumn('ratings.book_id', 'books.id');
                },
                'voters' => function ($query) {
                    $query->selectRaw('COUNT(ratings.id)')
                        ->from('ratings')
                        ->whereColumn('ratings.book_id', 'books.id');
                }
            ])
            ->when($search, function ($qq, $s) {
                $s = "%" . strtolower(trim($s)) . "%";
                $qq->where(function ($w) use ($s) {
                    $w->whereRaw('LOWER(books.title) like ?', [$s])
                        ->orWhereRaw('LOWER(authors.name) like ?', [$s]);
                });
            })

            ->orderByRaw('(SELECT ROUND(COALESCE(AVG(ratings.score), 0), 2) FROM ratings WHERE ratings.book_id = books.id) DESC')
            ->orderByRaw('(SELECT COUNT(ratings.id) FROM ratings WHERE ratings.book_id = books.id) DESC')
            ->orderBy('books.title'); 

        return $q->paginate($perPage <= 0 ? 10 : $perPage);
    }
}
