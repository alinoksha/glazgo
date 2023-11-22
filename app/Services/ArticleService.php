<?php

namespace App\Services;

class ArticleService
{
    private const ARTICLES_LIMIT = 140;

    public function __construct(
        private readonly TechcrunchApi $techcrunchApi,
    ) {
    }

    public function get(): array
    {
        return iterator_to_array($this->techcrunchApi->iteratePosts(self::ARTICLES_LIMIT));
    }
}
