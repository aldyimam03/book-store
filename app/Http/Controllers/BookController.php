<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    public function __construct(protected BookService $bookService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:10|max:100',
            'search' => 'nullable|string|max:100|min:2'
        ]);
        
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');
        
        
        try {
            $books = $this->bookService->list($perPage, $search);
        } catch (\Exception $e) {

            Log::error('Books list query error: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat memuat data buku. Silakan coba lagi.');
        }

        $limits = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100]; 

        return view('books.index', compact('books', 'perPage', 'search', 'limits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }

    public function byAuthor($authorId)
    {
        $books = Book::where('author_id', $authorId)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        return response()->json($books);
    }
}
