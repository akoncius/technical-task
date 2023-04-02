<?php

declare(strict_types=1);

namespace App\HereMaps\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Coordinates;
use GuzzleHttp\Utils;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function count;

class HereMapsCoordinatesProvider
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public static function fromResponse(ResponseInterface $response): ?Coordinates
    {
        $data = Utils::jsonDecode($response->getContent(), true);

        if (count($data['items']) === 0) {
            return null;
        }

        return new Coordinates(
            $data['items'][0]['position']['lat'],
            $data['items'][0]['position']['lng']
        );
    }
}
