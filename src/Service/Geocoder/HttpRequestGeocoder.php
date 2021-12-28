<?php

namespace App\Service\Geocoder;

use App\Service\Geocoder\HttpDecorator\HttpDecoratorInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Psr\Http\Client\ClientInterface;

class HttpRequestGeocoder implements GeocoderInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private HttpDecoratorInterface $geocoderDecorator
    ) {
    }

    public function geocode(Address $address): ?Coordinates
    {
        try {
            $request = $this->geocoderDecorator->decorateRequest($address);

            $response = $this->httpClient->sendRequest($request);

            $coordinates = $this->geocoderDecorator->decorateResponse($response);
        } catch (\Throwable) {
            return null;
        }

        return $coordinates;
    }
}
