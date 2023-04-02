<?php

declare(strict_types=1);

namespace App\Tests\GoogleMaps\Domain\Service\Geocoder;

use App\Contracts\Geocoder\ValueObject\Address;
use App\GoogleMaps\Domain\Service\Geocoder\GoogleMapsGeocoder;
use App\GoogleMaps\Domain\Service\GoogleMapsRequestProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class GoogleMapsGeocoderTest extends TestCase
{
    private HttpClientInterface $httpClient;

    private LoggerInterface $logger;

    private GoogleMapsGeocoder $googleMapsGeocoder;

    private Address $address;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $requestProvider = new GoogleMapsRequestProvider('test');
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->address = new Address(
            'test_country',
            'test_city',
            'test_street',
            'test_postcode'
        );

        $this->googleMapsGeocoder = new GoogleMapsGeocoder(
            $this->httpClient,
            $requestProvider,
            $this->logger
        );
    }

    public function testGeocodeNotOkStatus(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())->method('getContent')
            ->willReturn(json_encode(['status' => 'FAILED']));
        $this->httpClient->expects($this->once())->method('request')->willReturn($response);

        $this->assertNull($this->googleMapsGeocoder->geocode($this->address));
    }

    public function testGeocodeOkStatus(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $lat = '55.555';
        $lng = '55.556';
        $responseContent = [
            'status' => 'OK',
            'results' => [
                [
                    'geometry' => [
                        'location' => [
                            'lat' => $lat,
                            'lng' => $lng
                        ]
                    ]
                ]
            ]
        ];
        $response->expects($this->once())->method('getContent')
            ->willReturn(json_encode($responseContent));
        $this->httpClient->expects($this->once())->method('request')->willReturn($response);

        $coordinates = $this->googleMapsGeocoder->geocode($this->address);
        $this->assertEquals((float)$lat, $coordinates->getLat());
        $this->assertEquals((float)$lng, $coordinates->getLng());
    }

    public function testGeocodeWillThrowAnException(): void
    {
        $exception = $this->createMock(Throwable::class);
        $this->httpClient->expects($this->once())->method('request')->willThrowException($exception);
        $this->logger->expects($this->once())->method('error')->with(
            'Could not get information from remote googleapis',
            [
                'exception' => $exception
            ]
        );
        $this->assertNull($this->googleMapsGeocoder->geocode($this->address));
    }
}
