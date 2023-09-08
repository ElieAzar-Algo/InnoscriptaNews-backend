<?php
use App\Models\Article;

function duplicateArticles($url)
{
    $articleModel = new Article();
    
    //check if article exists in the db
    $existedArticle = $articleModel->where("article_url", $url)->first();
    if($existedArticle) return true;
}