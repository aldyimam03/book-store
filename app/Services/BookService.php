<?php

namespace App\Services;

use App\Contracts\Interfaces\BookInterface;

class BookService
{
    public function __construct(protected BookInterface $bookInterface) {}

    public function list(?int $perPage, ?string $search)
    {
        $pp = $perPage && $perPage >= 10 && $perPage <= 100 ? $perPage : 10;
        return $this->bookInterface->listWithStats($pp, $search);
    }
}
