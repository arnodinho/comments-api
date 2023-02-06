<?php

namespace App\Handler;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiHandler
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCommentsList()
    {
        return $this->getApi('comments');
    }

    private function getApi(string $var)
    {
        $response = $this->client->request(
            'GET',
            'https://mydomain/' . $var
        );

        return json_decode($response->getContent(), true);
    }
}
