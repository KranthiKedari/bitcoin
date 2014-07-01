<?php 
use Statsd\Client;
use Statsd\Connection\Socket;

class Task_Demo extends Minion_Task
{
    protected $_options = array(
        'foo' => 'bar',
        'bar' => NULL,
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
        for($i =0;$i<2000;$i++)
        {
            echo $i;
            $statsClient->increment("sampleData", 10);
        }
        echo 'foobar';
    }
}