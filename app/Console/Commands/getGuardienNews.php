<?php

namespace App\Console\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $key = config('app.theguardien_api_key');
        $currentDate = (new DateTime())->format('Y-m-d');

        //substract from the current data 1 day P1D (period 1 day)
        $previousDate = (new DateTime())->sub(new DateInterval('P1D'))->format('Y-m-d');

        $data = Http::get("$this->HOST/$this->ENPOINT?total=49&page-size=49&api-key=$key&order-by=newest&from-date=$previousDate&to-date=$currentDate");
        echo $data;
    }
}
