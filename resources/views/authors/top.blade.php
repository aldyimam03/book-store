<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Authors</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Top 10 Most Famous Authors</h1>

        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">No</th>
                    <th class="border p-2">Author Name</th>
                    <th class="border p-2">Voter</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($authors as $author)
                    <tr>
                        <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                        <td class="border p-2">{{ $author->name }}</td>
                        <td class="border p-2 text-center">{{ $author->voters }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-end">
            <a href="{{ route('books.index') }}"
                class="mt-4 w-full bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">
                Back</a>
        </div>
    </div>

</body>

</html>
