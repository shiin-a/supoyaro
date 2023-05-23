<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use PhpParser\Node\Expr\Print_;

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

    protected $clubName = 'START';

    protected $clubPlace = '東町スポーツセンター';

    /**
     * アクセス数を調査するページ(STARTの募集)のURL
     *
     * @var string
     */
    protected $clubUrl = 'https://www.net-menber.com/look/data/157312.html';

    /**
     * 検索順位を調査するページ（神奈川県で卓球の条件で絞る）のURL
     *
     * @var string
     */
    protected $seaechUrl = 'https://www.net-menber.com/list/tabletennis/index.html?ken=9';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        print date('Y-m-d') . ":\t";
        print $this->searchRank() . "\t";
        print $this->accessCount() . PHP_EOL;

        return Command::SUCCESS;
    }

    /**
     * 検索画面にRequestを送り、検索順位を取得する
     *
     * @return string
     */
    public function searchRank()
    {

        $count = 0;
        $searchRank = 0;
        $client = new Client();
        $crawler = $client->request('GET', $this->seaechUrl);
        $crawler->filter('dl.clearfix')->each(function ($node) use (&$count, &$searchRank) {
            $count++;
            $text = $node->text();

            //クラブ名と活動場所でSTARTかを判定
            if ((preg_match('/' . $this->clubName . '/', $text))  && (preg_match('/' . $this->clubPlace . '/', $text))) {
                $searchRank = $count;
            }
        });

        if ($searchRank < 10) {
            return $searchRank . ' 位';
        } else {
            return '10 位以下';
        }
    }

    /**
     * 募集のページにRequestを送り、アクセス数を取得する
     *
     * @return string
     */
    public function accessCount()
    {
        $accessCount = 0;
        $client = new Client();
        $crawler = $client->request('GET', $this->clubUrl);

        $crawler->filter('td')->each(function ($node) use (&$accessCount) {
            $text = $node->text();

            //「td」要素の中でviewで終わるものを抽出（=表示回数）
            if (preg_match('/view/', $text)) {
                $accessCount = $text;
                return;
            }
        });

        return $accessCount;
    }
}
