<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsApiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('NEWSAPI_KEY');
    }

    public function fetchArticles($query, $filters, $sort, $page)
    {
        $params = [
            'apiKey' => $this->apiKey,
            'q' => $query ?: 'news',
            'sources' => $filters['source'],
            'from' => $filters['publishedAt'],
            'sortBy' => $sort,
            'page' => $page,
        ];

        $response = $this->client->request('GET', 'https://newsapi.org/v2/everything', [
            'query' => array_filter($params) 
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}