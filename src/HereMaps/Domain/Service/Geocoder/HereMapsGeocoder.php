<?php

declare(strict_types=1);

namespace App\HereMaps\Domain\Service\Geocoder;

use App\Contracts\Geocoder\GeocoderInterface;
use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\HereMaps\Domain\Service\HereMapsCoordinatesProvider;
use App\HereMaps\Domain\Service\HereMapsRequestProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class HereMapsGeocoder implements GeocoderInterface
{
    private const HEREMAPS_URL = 'https://geocode.search.hereapi.com/v1/geocode';

    private HttpClientInterface $httpClient;

    private LoggerInterface $logger;

    private HereMapsRequestProvider $hereMapsRequestProvider;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, HereMapsRequestProvider $hereMapsRequestProvider)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->hereMapsRequestProvider = $hereMapsRequestProvider;
    }

    public function geocode(Address $address): ?Coordinates
    {
        $requestParams = $this->hereMapsRequestProvider->fromAddress($address);

        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                self::HEREMAPS_URL,
                $requestParams
            );

            return HereMapsCoordinatesProvider::fromResponse($response);
        } catch (Throwable $e) {
            $this->logger->error(
                'Could not get information from remote hereapi',
                [
                    'exception' => $e,
                ]
            );

            return null;
        }
    }
}
