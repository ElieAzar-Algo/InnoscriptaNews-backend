<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $key = config('app.newsapi_key');
        $data = Http::get("$this->HOST/$this->ENPOINT?language=en&apiKey=$key&sortBy=publishedAt&pageSize=100&category=general");
        echo $data;
    }
}
