<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List</title>
    @vite('resources/css/app.css')

    <link rel="preload" href="{{ route('ratings.create') }}" as="document">
    <link rel="preload" href="{{ route('authors.top') }}" as="document">
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4 text-center">Books List</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-500 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-500 p-4 rounded mb-4">
                {{ session('error') }}
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

                <!-- Search dengan debouncing -->
                <input type="text" name="search" value="{{ $search }}" class="border p-2 rounded"
                    placeholder="Search books or authors..." minlength="2" maxlength="100">

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Search</button>

                @if ($search)
                    <a href="{{ route('books.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Clear</a>
                @endif
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

        <!-- Loading indicator -->
        <div id="loading" class="hidden text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <p class="mt-2">Loading...</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 w-16">No</th>
                        <th class="border p-2">Book Name</th>
                        <th class="border p-2 w-32">Category</th>
                        <th class="border p-2 w-48">Author</th>
                        <th class="border p-2 w-24">Rating</th>
                        <th class="border p-2 w-20">Voters</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $index => $book)
                        <tr class="hover:bg-gray-50">
                            {{-- no --}}
                            <td class="border p-2 text-center">{{ $books->firstItem() + $index }}</td>

                            {{-- judul --}}
                            <td class="border p-2">
                                <div class="max-w-xs truncate" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </div>
                            </td>

                            {{-- kategori --}}
                            <td class="border p-2 text-center">
                                {{ $book->category_name ?? 'N/A' }}
                            </td>

                            {{-- penulis --}}
                            <td class="border p-2">
                                <div class="max-w-xs truncate" title="{{ $book->author_name }}">
                                    {{ $book->author_name ?? 'N/A' }}
                                </div>
                            </td>

                            {{-- avg_rating --}}
                            <td class="border p-2 text-center">
                                @if ($book->avg_rating > 0)
                                    <span
                                        class="inline-flex items-center px-2 py-1
                                        @if ($book->avg_rating >= 4) @elseif($book->avg_rating >= 3) 
                                        @else @endif">
                                        {{ number_format($book->avg_rating, 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- voters --}}
                            <td class="border p-2 text-center">
                                {{ $book->voters ?: 0 }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border p-8 text-center text-gray-500">
                                @if ($search)
                                    No books found for "{{ $search }}"
                                @else
                                    No books available
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($books->hasPages())
            <div class="mt-6">
                {{ $books->appends(['per_page' => $perPage, 'search' => $search])->links() }}
            </div>
        @endif

        <!-- Info -->
        <div class="mt-4 text-sm text-gray-600 text-center">
            Showing {{ $books->firstItem() ?? 0 }} to {{ $books->lastItem() ?? 0 }}
            of {{ $books->total() }} results
            @if ($search)
                for "{{ $search }}"
            @endif
        </div>
    </div>

    <!-- Optimasi: JavaScript untuk UX yang lebih baik -->
    <script>
        // Show loading indicator saat form submit
        document.getElementById('filterForm').addEventListener('submit', function() {
            document.getElementById('loading').classList.remove('hidden');
        });

        // Auto-submit search dengan delay
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const value = this.value.trim();

                // Auto submit jika kosong (untuk clear search)
                if (value === '') {
                    document.getElementById('filterForm').submit();
                    return;
                }

                // Auto submit setelah 1 detik jika minimal 2 karakter
                if (value.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 1000);
                }
            });
        }
    </script>

</body>

</html>
