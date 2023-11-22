<?php

namespace App\Services;

use App\Exceptions\TechcrunchApiException;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;

class TechcrunchApi
{
    private const BASE_URL = 'https://techcrunch.com/wp-json/wp/v2';
    private const CACHE_TTL = 3600;

    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function getPosts(int $page = 1, int $perPage = 10): array
    {
        return Cache::remember(
            key: sprintf('techcrunchPosts-%d-%d', $page, $perPage),
            ttl: self::CACHE_TTL,
            callback: function () use ($page, $perPage): array {
                $response = $this->client->get(
                    uri: sprintf('%s/posts', self::BASE_URL),
                    options: [
                        RequestOptions::QUERY => [
                            'page' => $page,
                            'per_page' => $perPage,
                            'orderby' => 'date',
                            'order' => 'desc',
                        ],
                    ],
                )->getBody()->getContents();

                $posts = json_decode($response, true);
                if (!is_array($posts)) {
                    throw new TechcrunchApiException('invalid techcrunch api response');
                }
                return $posts;
            }
        );
    }

    public function iteratePosts(int $limit): Generator
    {
        $count = 0;
        for ($page = 1; ; $page++) {
            $posts = $this->getPosts($page);
            if (count($posts) === 0) {
                return;
            }
            foreach ($posts as $post) {
                yield $post;
                $count++;
                if ($count === $limit) {
                    return;
                }
            }
        }
    }
}
