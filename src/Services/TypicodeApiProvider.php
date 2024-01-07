<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TypicodeApiProvider implements ApiProviderInterface
{
    const API_URL = 'https://jsonplaceholder.typicode.com/';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getApiData(string $endPoint): array {
        try {
            $response = $this->client->request(
                'GET',
                self::API_URL . $endPoint
            );
        }  catch (\Exception|TransportExceptionInterface $e) {
            return [];
        }

        return $response->toArray();
    }
}
