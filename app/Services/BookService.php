<?php

namespace App\Services;

use App\Contracts\Interfaces\BookInterface;

class BookService
{
    public function __construct(protected BookInterface $bookInterface) {}

    public function list(?int $perPage, ?string $search)
    {

        $pp = $this->validatePerPage($perPage);
        $cleanSearch = $this->sanitizeSearch($search);

        return $this->bookInterface->listWithStats($pp, $cleanSearch);
    }

    private function validatePerPage(?int $perPage): int
    {
        if (!$perPage || $perPage < 10 || $perPage > 100) {
            return 10;
        }

        return (int) (ceil($perPage / 10) * 10);
    }

    private function sanitizeSearch(?string $search): ?string
    {
        if (!$search) {
            return null;
        }

        $cleaned = trim($search);

        if (strlen($cleaned) < 2) {
            return null;
        }

        if (strlen($cleaned) > 100) {
            $cleaned = substr($cleaned, 0, 100);
        }

        return $cleaned;
    }
}
