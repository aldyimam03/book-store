<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    /** @use HasFactory<\Database\Factories\RatingFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'score'
    ];

    protected $casts = [
        'score' => 'integer'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
