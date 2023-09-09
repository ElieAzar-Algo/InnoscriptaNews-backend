<?php
namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    public function getArticles(Request $request) : Collection
    {

        $articlesQuery = Article::query();

        // Optional query parameter 'q'
        $query = $request->query('q');
        if ($query) 
        {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->orWhere('title', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%');
            });
        }

        // Optional query parameters 'from_date' and 'to_date'
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');
        if ($from_date && $to_date) 
        {
            $articlesQuery->whereBetween('publishedAt', [$from_date, $to_date]);
        }

        // Optional query parameter 'category'
        $category = $request->query('category');
        if ($category) 
        {
            $articlesQuery->where('category', '=', $category);
        }

        // Optional query parameter 'author'
        $author = $request->query('author');
        if ($author) 
        {
            $articlesQuery->where('author', '=', $author);
        }

        // Retrieve the filtered articles
        $filteredArticles = $articlesQuery->get();

        return $filteredArticles;
    }   

    // public function getArticle(int $id) : Article
    // {
    //     return Article::where("id", $id)->first();
    // } 
}