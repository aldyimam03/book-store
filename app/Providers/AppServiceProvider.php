<?php

namespace App\Providers;

use App\Models\Rating;
use App\Observers\RatingObserver;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Interfaces\BookInterface;
use App\Contracts\Interfaces\AuthorInterface;
use App\Contracts\Interfaces\RatingInterface;
use App\Contracts\Repositories\BookRepositories;
use App\Contracts\Repositories\AuthorRepositories;
use App\Contracts\Repositories\RatingRepositories;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookInterface::class, BookRepositories::class);
        $this->app->bind(AuthorInterface::class, AuthorRepositories::class);
        $this->app->bind(RatingInterface::class, RatingRepositories::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Rating::observe(RatingObserver::class);
    }
}
