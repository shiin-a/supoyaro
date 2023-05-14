<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;

class SupoyaroCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supoyaro:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'スポーツやろうよのアクセス数と検索順位をチェックする';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clubName = 'START';
        $clubUrl = 'https://www.net-menber.com/look/data/157312.html';

        $result = array();
        $client = new Client();
        $crawler = $client->request('GET', $clubUrl);
        $crawler->filter('td')->each(function ($node) use (&$result) {

            $text = $node->text();
            if (substr($text, -4) === 'view') {
                array_push($result, $text);
                print $text . "\n";
            }
        });

        print_r($result);
        return Command::SUCCESS;
    }
}
