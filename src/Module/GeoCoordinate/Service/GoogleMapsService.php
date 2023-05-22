<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Service;

use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;
use GuzzleHttp\Client;

class GoogleMapsService implements GeocoderInterface
{
    public function geocode(Address $address): ?Coordinates
    {
        $apiKey = $_ENV['GOOGLE_GEOCODING_API_KEY'];
        $params = [
            'query' => [
                'address' => $address->getStreet(),
                'components' => implode('|', [
                    "country:{$address->getCountry()}",
                    "locality:{$address->getCity()}",
                    "postal_code:{$address->getPostcode()}"]),
                'key' => $apiKey,
            ],
        ];

        $client = new Client();

        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json', $params);

        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (0 === count($data['results'])) {
            return null;
        }

        $firstResult = $data['results'][0];

        if ('ROOFTOP' !== $firstResult['geometry']['location_type']) {
            return null;
        }

        return new Coordinates(
            $firstResult['geometry']['location']['lat'],
            $firstResult['geometry']['location']['lng']
        );
    }
}
