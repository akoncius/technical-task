<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class HmapsGeocoder extends AbstractGuzzleGeocoder implements GeocoderInterface
{
    protected const API_URL = 'https://geocode.search.hereapi.com/v1/geocode';

    protected function mapResponse(array $data): ?Coordinates
    {
        if (empty($data)) {
            return null;
        }

        if (count($data['items']) === 0) {
            return null;
        }

        if ($data['items'][0]['resultType'] !== 'houseNumber') {
            return null;
        }

        $lat = $data['items'][0]['position']['lat'];
        $lng = $data['items'][0]['position']['lng'];

        return new Coordinates($lat, $lng);
    }

    public function geocode(Address $address): ?Coordinates
    {
        $params = [
            'query' => [
                'qq' => implode(';', [
                    "country={$address->getCountry()}",
                    "city={$address->getCity()}",
                    "street={$address->getStreet()}",
                    "postalCode={$address->getPostcode()}"
                ]),
                'apiKey' => $this->apiKey
            ]
        ];

        $data = $this->fetch($params);

        return $this->mapResponse($data);
    }
}
