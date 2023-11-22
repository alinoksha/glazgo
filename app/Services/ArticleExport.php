<?php

namespace App\Services;

use Maatwebsite\Excel\Concerns\FromArray;

class ArticleExport implements FromArray
{
    public function __construct(
        private readonly array $articles,
    ) {
    }

    public function array(): array
    {
        return $this->articles;
    }

    public static function prepareDataFromFile(array $articles): self
    {
        $sortedArticles = $articles[0];
        usort($sortedArticles, function (array $a, array $b): int {
            return date_create($a[2]) < date_create($b[2]);
        });
        $res = [[],[]];
        foreach ($sortedArticles as $article) {
            $res[0][] = $article[0];
            $res[1][] = $article[1];

        }
        return new ArticleExport($res);
    }

    public static function prepareDataFromApi(array $articles): self
    {
        $res = [[],[]];
        foreach ($articles as $article) {
            $res[0][] = html_entity_decode(strip_tags($article['title']['rendered']));
            $res[1][] = html_entity_decode(strip_tags($article['content']['rendered']));
        }
        return new ArticleExport($res);
    }
}
