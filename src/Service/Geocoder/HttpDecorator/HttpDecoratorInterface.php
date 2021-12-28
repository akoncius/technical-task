<?php

namespace App\Service\Geocoder\HttpDecorator;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpDecoratorInterface
{
    /**
     * @param Address $address
     * @return RequestInterface
     */
    public function decorateRequest(Address $address): RequestInterface;

    /**
     * @param ResponseInterface $response
     * @return Coordinates
     */
    public function decorateResponse(ResponseInterface $response): ?Coordinates;
}
