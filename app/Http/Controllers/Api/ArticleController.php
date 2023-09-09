<?php

namespace App\Http\Controllers\Api;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ArticleController extends BaseController
{
    protected ArticleService $articleService;
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        return $this->articleService->getArticles($request);
    }
}
