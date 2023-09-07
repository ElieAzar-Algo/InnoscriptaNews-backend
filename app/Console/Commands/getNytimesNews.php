<?php

namespace App\Console\Commands;

use DateInterval;
use DateTime;
use Http;
use Illuminate\Console\Command;

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
        $days ='1.json';
        $data = Http::get("$this->HOST/$this->ENPOINT/$days?api-key=$key");
        echo $data;
    }
}
