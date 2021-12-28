<?php

namespace App\Service\Geocoder\HttpDecorator;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HereapiHttpDecorator implements HttpDecoratorInterface
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private string $apiKey
    ) {
    }

    public function decorateRequest(Address $address): RequestInterface
    {
        $params = [
            'qq' => implode(';', ["country={$address->getCountry()}", "city={$address->getCity()}", "street={$address->getStreet()}", "postalCode={$address->getPostcode()}"]),
            'apiKey' => $this->apiKey
        ];

        return $this->requestFactory->createRequest(
            'GET',
            'https://geocode.search.hereapi.com/v1/geocode?' . http_build_query($params)
        );
    }

    public function decorateResponse(ResponseInterface $response): ?Coordinates
    {
        $coordinates = null;

        try {
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $resultType = $data['items'][0]['resultType'] ?? null;
            if ($resultType === 'houseNumber') {
                $coordinates = new Coordinates(
                    $data['items'][0]['position']['lat'],
                    $data['items'][0]['position']['lng']
                );
            }
        } catch (\Throwable) {

        }

        return $coordinates;
    }
}
