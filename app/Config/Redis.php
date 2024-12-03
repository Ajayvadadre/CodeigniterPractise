<?php

namespace Config;

use Predis\Client;

class Redis
{
    public $host = '172.19.0.2';
    public $port = 6379;
    public $timeout = 30;

    public function getClient()
    {
        $client = new Client([
            'scheme' => 'tcp',
            'host' => $this->host,
            'port' => $this->port,
            'timeout' => $this->timeout,
        ]);

        return $client;
    }
}