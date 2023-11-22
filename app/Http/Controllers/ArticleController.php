<?php

namespace App\Http\Controllers;

use App\Services\ArticleImport;
use App\Services\ArticleService;
use App\Services\ArticleExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService,
        private readonly Excel $excel,
    ) {
    }

    public function getExcel(Request $request): BinaryFileResponse
    {
        return $this->export($this->getArticles($request), 'articles.xlsx');
    }

    public function getCSV(Request $request): BinaryFileResponse
    {
        return $this->export($this->getArticles($request), 'articles.csv', Excel::CSV);
    }

    private function getArticles(Request $request): ArticleExport
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $articles = ArticleExport::prepareDataFromFile($this->excel->toArray(new ArticleImport, $file));
        } else {
            $articles = ArticleExport::prepareDataFromApi($this->articleService->get());
        }
        return $articles;
    }

    private function export(ArticleExport $export, string $name, string $format = Excel::XLSX): BinaryFileResponse
    {
        return $this->excel->download($export, $name, $format);
    }
}
