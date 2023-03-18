<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractGuzzleGeocoder
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    abstract protected function mapResponse(array $data): ?Coordinates;

    public function fetch(array $params): array
    {
        $client = new Client();

        try {
            $response = $client->get($this::API_URL, $params);
        } catch (GuzzleException $exception) {
            // log this situation
            return [];
        }

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
