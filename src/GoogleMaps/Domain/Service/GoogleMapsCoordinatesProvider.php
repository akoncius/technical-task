<?php

declare(strict_types=1);

namespace App\GoogleMaps\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Coordinates;
use GuzzleHttp\Utils;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GoogleMapsCoordinatesProvider
{
    private const OK_STATUS = 'OK';

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public static function fromResponse(ResponseInterface $response): ?Coordinates
    {
        $data = Utils::jsonDecode($response->getContent(), true);

        if ($data['status'] !== self::OK_STATUS) {
            return null;
        }

        return new Coordinates(
            $data['results'][0]['geometry']['location']['lat'],
            $data['results'][0]['geometry']['location']['lng']
        );
    }
}
