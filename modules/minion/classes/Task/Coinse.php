<?php 
use Statsd\Client;
use Statsd\Connection\Socket;

class Task_Coinse extends Minion_Task
{
    protected $_options = array(
        'coins' => NULL,
    );
 
    /**
     * mysql_thread_id()s is a demo task
     *
     * @return null
     */
    protected function _execute(array $params)
    {
        $statsSocket = new Socket("localhost", 8125);
        $statsClient = new Client($statsSocket);
        $coinsChecked = array();

        $multipliter = array("DOGE_BTC" => 1000000000, "LTC_BTC"=> 1000000);
        if(empty($params['coins']))
        {
            $coinsChecked = array("DOGE_BTC", "LTC_BTC");
        } else {
            $coinsChecked = explode("/", $params['coins']);
        }

        $request = Request::factory("https://www.coins-e.com/api/v2/markets/data/");
        $data = json_decode($request->execute(), true);

        if(!empty($data['status']))
        {
            $coins = $data['markets'];
            //foreach ($coins as $coinPair=>$coin) {
             //   echo $coinPair . "\n";
            //}
            foreach ($coinsChecked as $coinPair) {
                $coinData = $coins[$coinPair];
                $ltp = $coinData['marketstat']['ltp'];

                $ltq = $coinData['marketstat']['ltq'];
                $statsClient->gauge($coinPair . "_ltp", $ltp * $multipliter[$coinPair]);
                $statsClient->gauge($coinPair . "_ltq", $ltq );
                echo "$coinPair.ltp:$ltp \n";
                echo "$coinPair.ltq:$ltq     \n";

            }
        
        }

        $request = Request::factory("https://coinbase.com/api/v1/prices/spot_rate");
        $data = json_decode($request->execute(), true);

        $price = !empty($data['amount']) ? $data['amount'] : 0; 
        $statsClient->gauge("coinbase_btc", $price);
        echo "coinbase_btc :". $price;
        sleep(10);
    }
}