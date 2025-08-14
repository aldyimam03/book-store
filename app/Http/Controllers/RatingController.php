<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Services\RatingService;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;

class RatingController extends Controller
{
    public function __construct(protected RatingService $ratingService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $authors = Author::orderBy('name')->get(['id', 'name']);
        $authorId = (int) $request->query('author_id');
        $books = $authorId ? $this->ratingService->booksByAuthor($authorId) : collect();

        return view('ratings.create', compact('authors', 'books', 'authorId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        
        $this->ratingService->create(
            $request->book_id,  
            $request->score    
        );

        return redirect()->route('books.index')->with('success', 'Rating berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
