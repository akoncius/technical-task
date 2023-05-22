<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Service;

use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;
use GuzzleHttp\Client;

class HereMapsService implements GeocoderInterface
{
    public function geocode(Address $address): ?Coordinates
    {
        $apiKey = $_ENV['HEREMAPS_GEOCODING_API_KEY'];

        $params = [
            'query' => [
                'qq' => implode(';', [
                    "country={$address->getCountry()}",
                    "city={$address->getCity()}",
                    "street={$address->getStreet()}",
                    "postalCode={$address->getPostcode()}",
                ]),
                'apiKey' => $apiKey,
            ],
        ];

        $client = new Client();

        $response = $client->get('https://geocode.search.hereapi.com/v1/geocode', $params);

        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (0 === count($data['items'])) {
            return null;
        }

        $firstItem = $data['items'][0];

        if ('houseNumber' !== $firstItem['resultType']) {
            return null;
        }

        return new Coordinates(
            $firstItem['position']['lat'],
            $firstItem['position']['lng']
        );
    }
}
