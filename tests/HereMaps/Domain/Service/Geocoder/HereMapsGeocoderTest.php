<?php

declare(strict_types=1);

namespace App\Tests\HereMaps\Domain\Service\Geocoder;

use App\Contracts\Geocoder\ValueObject\Address;
use App\HereMaps\Domain\Service\Geocoder\HereMapsGeocoder;
use App\HereMaps\Domain\Service\HereMapsRequestProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class HereMapsGeocoderTest extends TestCase
{
    private HttpClientInterface $httpClient;

    private LoggerInterface $logger;

    private HereMapsGeocoder $heremapsGeocoder;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $requestProvider = new HereMapsRequestProvider('test');
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->address = new Address(
            'test_country',
            'test_city',
            'test_street',
            'test_postcode'
        );
        $this->heremapsGeocoder = new HereMapsGeocoder(
            $this->httpClient,
            $this->logger,
            $requestProvider
        );
    }

    public function testGeocodeEmptyResult(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())->method('getContent')
            ->willReturn(json_encode(['items' => []]));
        $this->httpClient->expects($this->once())->method('request')->willReturn($response);

        $this->assertNull($this->heremapsGeocoder->geocode($this->address));
    }

    public function testGeocodeOkStatus(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $lat = '55.555';
        $lng = '55.556';
        $responseContent = [
            'items' => [
                [
                    'position' => [
                        'lat' => $lat,
                        'lng' => $lng,
                    ]
                ]
            ]
        ];
        $response->expects($this->once())->method('getContent')
            ->willReturn(json_encode($responseContent));
        $this->httpClient->expects($this->once())->method('request')->willReturn($response);

        $coordinates = $this->heremapsGeocoder->geocode($this->address);
        $this->assertEquals((float)$lat, $coordinates->getLat());
        $this->assertEquals((float)$lng, $coordinates->getLng());
    }

    public function testGeocodeWillThrowAnException(): void
    {
        $exception = $this->createMock(Throwable::class);
        $this->httpClient->expects($this->once())->method('request')->willThrowException($exception);
        $this->logger->expects($this->once())->method('error')->with(
            'Could not get information from remote hereapi',
            [
                'exception' => $exception
            ]
        );
        $this->assertNull($this->heremapsGeocoder->geocode($this->address));
    }
}
