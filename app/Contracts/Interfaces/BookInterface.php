<?php

namespace App\Contracts\Interfaces;

interface BookInterface
{
    public function listWithStats(int $perPage, ?string $search);
}
