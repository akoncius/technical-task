<?php

namespace App\Service\Geocoder\HttpDecorator;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GoogleHttpDecorator implements HttpDecoratorInterface
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private string $apiKey
    ) {
    }

    public function decorateRequest(Address $address): RequestInterface
    {
        $params = [
            'address' => $address->getStreet(),
            'components' => implode('|', [
                "country:{$address->getCountry()}",
                "locality:{$address->getCity()}",
                "postal_code:{$address->getPostcode()}"
            ]),
            'key' => $this->apiKey
        ];

        return $this->requestFactory->createRequest(
            'GET',
            'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($params)
        );
    }

    public function decorateResponse(ResponseInterface $response): ?Coordinates
    {
        $coordinates = null;

        try {
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $locationType = $data['results'][0]['geometry']['location_type'] ?? null;
            if ($locationType === 'ROOFTOP') {
                $coordinates = new Coordinates(
                    $data['results'][0]['geometry']['location']['lat'],
                    $data['results'][0]['geometry']['location']['lng']
                );
            }
        } catch (\Throwable) { }

        return $coordinates;
    }
}
