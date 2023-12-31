<?php

namespace App\Console\Commands;

use App\Models\Article;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\helpers;

class getNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:get-newsapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch NEWS from NewsApi';
    private string $HOST='https://newsapi.org/v2';
    private string $ENPOINT='top-headlines';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         //log that the command has been called by a cron or schedule job
         LogCommandCall($this->signature);
         
        $key = config('app.newsapi_key');
        $categories = config('categories');
        $articles_count = 20;

        foreach ($categories as $category)
        {
            $url = "$this->HOST/$this->ENPOINT?language=en&apiKey=$key&sortBy=publishedAt&pageSize=$articles_count&category=$category";
            // echo($url);
            $response = Http::timeout(120)->get($url);
            
            if ($response)
            {
                $data = $response->json();
                $articles = $data['articles'];
                foreach($articles as $article)
                {
                    //rule(ignore removed articles): check if the article is "removed" or "[removed]"
                    if (strtolower($article['source']['name']) == '[removed]' || strtolower($article['source']['name']) == 'removed')
                    {
                        continue; //skip this article and continue to the next one
                    }
                    $articleModel = new Article();

                    //rule(ignore duplicate articles): if news url exists skip the article
                    $isDuplicate = duplicateArticles($article['url']);
                    if($isDuplicate) continue;

                    $dateTime = new DateTime($article['publishedAt']);
                    $formattedDate = $dateTime->format('Y-m-d H:i:s');
                    $createArticles = $articleModel->create([
                        'api'         => $url,
                        'source'      => $article['source']['name']? $article['source']['name']: "no source",
                        'author'      => $article['author']?$article['author']:"no author",
                        'title'       => $article['title'],
                        'description' => $article['description']?$article['description']:"no description",
                        'image_url'   => $article['urlToImage']?$article['urlToImage']:"no image",
                        'article_url' => $article['url'],
                        'publishedAt' => $formattedDate,
                        'category'    => $category,
                        'lang'        =>'en',
                    ]);
                }
                Log::info("$category news is fetched from $this->HOST and saved successfully");
            } 
            else 
            {
                Log::error("Error: Unable to retrieve JSON data");
            }
        }
    }
}
