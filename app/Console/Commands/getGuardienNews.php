<?php

namespace App\Console\Commands;

use App\Models\Article;
use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class getGuardienNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:get-guardien-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch NEWS from The Guardien';
    private string $HOST='https://content.guardianapis.com';
    private string $ENPOINT='search';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //log that the command has been called by a cron or schedule job
        LogCommandCall($this->signature);
        
        $key = config('app.theguardien_api_key');
        $categories = config('categories');
        $articles_count = 20;

        $currentDate = (new DateTime())->format('Y-m-d');
        //substract from the current data 1 day P1D (period 1 day)
        $previousDate = (new DateTime())->sub(new DateInterval('P1D'))->format('Y-m-d');

        foreach ($categories as $category)
        {
            $url = "$this->HOST/$this->ENPOINT?total=49&q=$category&page-size=$articles_count&api-key=$key&order-by=newest&from-date=$previousDate&to-date=$currentDate";
            $result = Http::timeout(120)->get($url);

            if($result->successful()) 
            {
                $res = $result->json();
                $response = $res['response'];
                if(count($response['results']) > 0)
                {
                    $articles = $response['results'];
                    foreach($articles as $article)
                    {   
                        //rule(ignore duplicate articles): if news url exists skip the article
                        $isDuplicate = duplicateArticles($article['webUrl']);
                        if($isDuplicate) continue;
                        
                        $articleModel = new Article();
                        $dateTime = new DateTime($article['webPublicationDate']);
                        $formattedDate = $dateTime->format('Y-m-d H:i:s');
                        $createArticles = $articleModel->create([
                            'api'         => $url,
                            'source'      => "The Guardien",
                            'author'      => "The Guardien",
                            'title'       => $article['webTitle'],
                            'description' => $article['webTitle']?$article['webTitle']:"no description",
                            'image_url'   => "https://gocycle.com/wp-content/uploads/2019/02/theguardian.jpg",
                            'article_url' => $article['webUrl'],
                            'publishedAt' => $formattedDate,
                            'category'    => $category,
                            'lang'        =>'en',
                        ]);
                    }
                    Log::info("$category news is fetched from $this->HOST and saved successfully");
                }
                Log::info("JOB IS DONE! Fetching and Storing news data from $this->HOST is completed");
            }
            else
            {
                Log::error("Error: Unable to retrieve JSON data");
            }
        }

    }
}
