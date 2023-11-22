<?php

namespace App\Services;

use Maatwebsite\Excel\Concerns\ToArray;

class ArticleImport implements ToArray
{
    public function array(array $array): array
    {
        return $array;
    }
}
