<?php

namespace App\Http\Controllers\Api;
use App\Services\ArticleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    protected ArticleService $articleService;
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request) : Collection
    {
        return $this->articleService->getArticles($request);
    }
}
