<?php

namespace App\Services;

use App\Contracts\Interfaces\AuthorInterface;

class AuthorService
{
    public function __construct(protected AuthorInterface $authorInterface) {}
    public function getTop10ByVotersAbove5()
    {
        return $this->authorInterface->top10ByVotersAbove5();
    }
}
