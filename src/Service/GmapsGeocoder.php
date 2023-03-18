<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GmapsGeocoder extends AbstractGuzzleGeocoder implements GeocoderInterface
{
    protected const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected function mapResponse(array $data): ?Coordinates
    {
        if (empty($data)) {
            return null;
        }

        if (count($data['results']) === 0) {
            return null;
        }

        if ($data['results'][0]['geometry']['location_type'] !== 'ROOFTOP') {
            return null;
        }

        $lat = $data['results'][0]['geometry']['location']['lat'];
        $lng = $data['results'][0]['geometry']['location']['lng'];

        return new Coordinates($lat, $lng);
    }

    public function geocode(Address $address): ?Coordinates
    {
        $params = [
            'query' => [
                'address' => $address->getStreet(),
                'components' => implode('|', [
                    "country:{$address->getCountry()}",
                    "locality:{$address->getCity()}",
                    "postal_code:{$address->getPostcode()}"
                ]),
                'key' => $this->apiKey
            ]
        ];

        $data = $this->fetch($params);

        return $this->mapResponse($data);
    }
}
