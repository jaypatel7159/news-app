<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.8.2/alpine.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">Latest News</h1>

        <form method="GET" action="{{ route('articles.index') }}" class="mb-6 flex flex-wrap">
            <input type="text" name="query" value="{{ request('query') }}" placeholder="Search..." class="flex-1 p-2 border border-gray-300 rounded mr-2 mb-2">
            <input type="text" name="source" value="{{ request('source') }}" placeholder="Source..." class="flex-1 p-2 border border-gray-300 rounded mr-2 mb-2">
            <input type="date" name="publishedAt" value="{{ request('publishedAt') }}" class="flex-1 p-2 border border-gray-300 rounded mr-2 mb-2">
            <input type="text" name="author" value="{{ request('author') }}" placeholder="Author..." class="flex-1 p-2 border border-gray-300 rounded mr-2 mb-2">
            <select name="sort" class="flex-1 p-2 border border-gray-300 rounded mr-2 mb-2">
                <option value="published_at" {{ request('sort') == 'published_at' ? 'selected' : '' }}>Published At</option>
                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                <option value="source" {{ request('sort') == 'source' ? 'selected' : '' }}>Source</option>
                <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author</option>
            </select>
            <select name="direction" class="flex-1 p-2 border border-gray-300 rounded mr-2 mb-2">
                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded mb-2">Search</button>
        </form>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <table class="min-w-full bg-white">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="w-1/5 px-4 py-2 text-left">Title</th>
                        <th class="w-1/8 px-4 py-2 text-left">Source</th> <!-- Adjusted width -->
                        <th class="w-1/7 px-4 py-2 text-left">Published At</th> <!-- Adjusted width -->
                        <th class="w-1/5 px-4 py-2 text-left">Author</th>
                        <th class="w-1/3 px-4 py-2 text-left">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="border px-4 py-2">{{ $article->title }}</td>
                            <td class="border px-4 py-2">{{ $article->source }}</td>
                            <td class="border px-4 py-2">{{ $article->published_at }}</td>
                            <td class="border px-4 py-2">{{ $article->author }}</td>
                            <td class="border px-4 py-2">{{ $article->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 flex justify-between">
            {{ $articles->appends(request()->input())->links() }}
        </div>
    </div>
</body>
</html>
