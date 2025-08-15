<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4 text-center">Books List</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-500 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <!-- Form Filter -->
            <form method="GET" id="filterForm" class="flex gap-2">
                <!-- Dropdown -->
                <select name="per_page" class="border p-2 rounded"
                    onchange="document.getElementById('filterForm').submit()">
                    @foreach ($limits as $limit)
                        <option value="{{ $limit }}" {{ $perPage == $limit ? 'selected' : '' }}>
                            {{ $limit }}
                        </option>
                    @endforeach
                </select>

                <!-- Search -->
                <input type="text" name="search" value="{{ $search }}" class="border p-2 rounded"
                    placeholder="Search...">

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Search</button>
            </form>

            <!-- Tombol di kanan -->
            <div class="flex gap-2">
                <a href="{{ route('ratings.create') }}">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Insert Rating</button>
                </a>

                <a href="{{ route('authors.top') }}">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Top 10</button>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">No</th>
                        <th class="border p-2">Book Name</th>
                        <th class="border p-2">Category Name</th>
                        <th class="border p-2">Author</th>
                        <th class="border p-2">Average Rating</th>
                        <th class="border p-2">Voter</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $index => $book)
                        <tr>
                            {{-- no --}}
                            <td class="border p-2 text-center">{{ $books->firstItem() + $index }}</td>

                            {{-- judul --}}
                            <td class="border p-2">{{ $book->title }}</td>

                            {{-- kategori --}}
                            @if (isset($book->category))
                                <td class="border p-2 text-center">{{ $book->category->name }}</td>
                            @else
                                <td class="border p-2 text-center">{{ $book->category_name ?? 'N/A' }}</td>
                            @endif

                            {{-- penulis --}}
                            @if (isset($book->author))
                                <td class="border p-2">{{ $book->author->name }}</td>
                            @else
                                <td class="border p-2">{{ $book->author_name ?? 'N/A' }}</td>
                            @endif

                            {{-- avg_rating --}}
                            <td class="border p-2 text-center">
                                @if (isset($book->ratings_avg_score))
                                    {{ rtrim(rtrim(number_format($book->ratings_avg_score, 2))) }}
                                @else
                                    {{ rtrim(rtrim(number_format($book->avg_rating, 2))) }}
                                @endif
                            </td>

                            {{-- voters --}}
                            <td class="border p-2 text-center">
                                @if (isset($book->ratings_count))
                                    {{ $book->ratings_count }}
                                @else
                                    {{ $book->voters }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $books->appends(['per_page' => $perPage, 'search' => $search])->links() }}
        </div>
    </div>

</body>

</html>
