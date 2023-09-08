<?php

namespace App\Console\Commands;

use App\Models\Article;
use DateInterval;
use DateTime;
use Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class getNytimesNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:get-nytimes-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch The most viewed NEWS from NYTIMES';
    private string $HOST='https://api.nytimes.com';
    private string $ENPOINT='svc/mostpopular/v2/viewed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = config('app.nytimme_api_key');
        $categories = config('categories');
        $categoryToBeStored ='general';
        $days ='1.json';
        $url = "$this->HOST/$this->ENPOINT/$days?api-key=$key";
        // Log::info("URL  -->  $url ");

        $response = Http::timeout(120)->get($url);
        if ($response['status'] == 'OK' && count($response['results']) > 0)
        {
            foreach($response['results'] as $article)
            {
                //rule(ignore duplicate articles): if news url exists skip the article
                $isDuplicate = duplicateArticles($article['url']);
                if($isDuplicate) continue;
                
                $articleCategory = $article['nytdsection'];

                // if nytdsection matches one of the categories
                if (in_array($articleCategory, $categories)) 
                {
                   $categoryToBeStored = $articleCategory;
                }
                //handling the well category and set it to health
                if($articleCategory == 'well') $categoryToBeStored = $categories['HEALTH'];

                $dateTime = new DateTime($article['published_date']);
                $formattedDate = $dateTime->format('Y-m-d H:i:s');
                $imageUrl = 'no image';

                foreach($article['media'] as $media)
                {
                    if($media['type'] == 'image')
                    {
                        $imageUrl = end($media['media-metadata'])['url'];
                        // Log::info("imageUrl --> $imageUrl");
                    }
                }

                $articleModel = new Article();
                $createArticles = $articleModel->create([
                    'api'         => $url,
                    'source'      => $article['source']? $article['source']: "New York Times",
                    'author'      => $article['byline']?$article['byline']:"no author",
                    'title'       => $article['title'],
                    'description' => $article['abstract']?$article['abstract']:"no description",
                    'image_url'   => $imageUrl,
                    'article_url' => $article['url'],
                    'publishedAt' => $formattedDate,
                    'category'    => $categoryToBeStored,
                    'lang'        =>'en',
                ]);
                Log::info("$categoryToBeStored news is fetched from $this->HOST and saved successfully");
            }
        }
        else
        {
            Log::error("JOB IS DONE! Fetching and Storing news data from $this->HOST is completed");
        }
    }
}
