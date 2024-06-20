<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsApiService;
use App\Models\Article;
use Carbon\Carbon;

class ArticleController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService)
    {
        $this->newsApiService = $newsApiService;
    }

    public function index(Request $request)
    {
        $searchQuery = $request->input('query', '');
        $filters = [
            'source' => $request->input('source', ''),
            'publishedAt' => $request->input('publishedAt', ''),
            'author' => $request->input('author', ''),
        ];
        $sort = $request->input('sort', 'published_at');
        $direction = $request->input('direction', 'desc');
        $page = $request->input('page', 1);

        $apiResponse = $this->newsApiService->fetchArticles($searchQuery, $filters, $sort, $page);
        $articles = $apiResponse['articles'] ?? [];

        foreach ($articles as $articleData) {
            $publishedAt = Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s');

            Article::updateOrCreate(
                ['title' => $articleData['title']],
                [
                    'source' => $articleData['source']['name'],
                    'published_at' => $publishedAt,
                    'author' => $articleData['author'],
                    'description' => $articleData['description'],
                    'url' => $articleData['url'],
                    'url_to_image' => $articleData['urlToImage'],
                ]
            );
        }

        $paginatedArticles = Article::where(function ($query) use ($filters) {
            if ($filters['source']) {
                $query->where('source', $filters['source']);
            }
            if ($filters['publishedAt']) {
                $query->whereDate('published_at', $filters['publishedAt']);
            }
            if ($filters['author']) {
                $query->where('author', 'like', '%' . $filters['author'] . '%');
            }
        })
        ->where(function ($query) use ($searchQuery) {
            if ($searchQuery) {
                $query->where('title', 'like', '%' . $searchQuery . '%')
                      ->orWhere('description', 'like', '%' . $searchQuery . '%');
            }
        })
        ->orderBy($sort, $direction)
        ->paginate(10);

        return view('articles.index', [
            'articles' => $paginatedArticles,
            'query' => $searchQuery,
            'filters' => $filters,
            'sort' => $sort,
            'direction' => $direction,
            'currentPage' => $paginatedArticles->currentPage(),
            'totalResults' => $paginatedArticles->total(),
        ]);
    }
}
