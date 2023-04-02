<?php

declare(strict_types=1);

namespace App\GoogleMaps\Domain\Service\Geocoder;

use App\Contracts\Geocoder\GeocoderInterface;
use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\GoogleMaps\Domain\Service\GoogleMapsCoordinatesProvider;
use App\GoogleMaps\Domain\Service\GoogleMapsRequestProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class GoogleMapsGeocoder implements GeocoderInterface
{
    private const GOOGLEMAPS_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    private HttpClientInterface $httpClient;

    private GoogleMapsRequestProvider $googleMapsRequestProvider;

    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $httpClient, GoogleMapsRequestProvider $googleMapsRequestProvider, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->googleMapsRequestProvider = $googleMapsRequestProvider;
        $this->logger = $logger;
    }

    public function geocode(Address $address): ?Coordinates
    {
        $request = $this->googleMapsRequestProvider->fromAddress($address);

        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                self::GOOGLEMAPS_URL,
                $request
            );

            return GoogleMapsCoordinatesProvider::fromResponse($response);
        } catch (Throwable $e) {
            $this->logger->error(
                'Could not get information from remote googleapis',
                [
                    'exception' => $e
                ]
            );

            return null;
        }
    }
}
