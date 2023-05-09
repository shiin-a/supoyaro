<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;

class ScrapeMynavi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mynavi:shiina';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Mynavi from shiina';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $client = new Client();
        $crawler = $client->request('GET', 'https://www.net-menber.com/');
        $myLink = $crawler->filter('.header_one_btn')->text();
        var_dump($myLink);
        $crawler = $client->click($crawler->selectLink($myLink)->link());
        $form = $crawler->selectButton('ログイン')->form();
        $crawler = $client->submit($form, ['em' => 'shiinayusuke01@gmail.com', 'pw' => 'password']);
        $crawler->filter('.my_email')->each(function ($node) {
            var_dump($node);
            print $node->text() . "\n";
        });
        $crawler->filter('.flash-error')->each(function ($node) {
            print $node->text() . "\n";
        });

        var_dump($crawler);


        // $client = new Client();
        // $crawler = $client->request('GET', 'https://html.duckduckgo.com/html/?q=dog');
        // $crawler->filter('.result__title .result__a')->each(function ($node) {
        //     dump($node->text());
        // });


        //return view('welcome');
        return Command::SUCCESS;
    }
}
