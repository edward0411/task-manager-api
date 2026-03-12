<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ExternalPostService
{
    private string $baseUrl = 'https://jsonplaceholder.typicode.com';

    /**
     * @throws RequestException
     */
    public function getPosts(array $query = []): array
    {
        $response = Http::timeout(10)
            ->acceptJson()
            ->get("{$this->baseUrl}/posts", $query)
            ->throw();

        return $response->json();
    }

    /**
     * @throws RequestException
     */
    public function getPostById(int $id): array
    {
        $response = Http::timeout(10)
            ->acceptJson()
            ->get("{$this->baseUrl}/posts/{$id}");

        if ($response->status() === 404) {
            return [];
        }

        $response->throw();

        return $response->json();
    }
}