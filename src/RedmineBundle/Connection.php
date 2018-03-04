<?php

namespace RedmineBundle;

use Redmine\Client;

class Connection
{
    /** @var Client  */
    private $client;

    public function __construct(string $url, string $apiKey)
    {
        $this->client = new Client($url, $apiKey);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
