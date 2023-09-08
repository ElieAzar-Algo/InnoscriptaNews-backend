<?php
use App\Models\Article;

function duplicateArticles($url)
{
    $articleModel = new Article();
    
    //check if article exists in the db
    $existedArticle = $articleModel->where("article_url", $url)->first();
    if($existedArticle) return true;
}

function LogCommandCall($cmd)
{
    $currentDateTime = date('Y-m-d H:i:s');
    Log::info("Command called:$cmd - dateTime: $currentDateTime");
}