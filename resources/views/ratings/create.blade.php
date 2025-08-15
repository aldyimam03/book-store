<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Rating</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Insert Rating</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ratings.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="author_id" class="block font-medium">Author</label>
                <select name="author_id" id="author_id" class="border rounded w-full px-3 py-2" required>
                    <option value="">-- Pilih Author --</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
                @error('author_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="book_id" class="block font-medium">Book</label>
                <select name="book_id" id="book_id" class="border rounded w-full px-3 py-2" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }}
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="score" class="block font-medium">Rating</label>
                <select name="score" id="score" class="border rounded w-full px-3 py-2" required>
                    <option value="">-- Pilih Rating --</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('score') === (string) $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
                @error('score')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex-1">
                    Submit Rating
                </button>
                <a href="{{ route('books.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('author_id').addEventListener('change', function() {
            let authorId = this.value;
            let bookSelect = document.getElementById('book_id');

            // Clear previous options
            bookSelect.innerHTML = '<option value="">-- Pilih Buku --</option>';

            if (authorId) {
                fetch(`/books/by-author/${authorId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(book => {
                            let option = document.createElement('option');
                            option.value = book.id;
                            option.textContent = book.title;
                            bookSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching books:', error);
                    });
            }
        });
    </script>
</body>

</html>
