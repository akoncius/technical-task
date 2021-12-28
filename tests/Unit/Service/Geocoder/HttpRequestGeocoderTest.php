<?php

namespace App\Tests\Unit\Service\Geocoder;

use App\Service\Geocoder\HttpDecorator\HttpDecoratorInterface;
use App\Service\Geocoder\HttpRequestGeocoder;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class HttpRequestGeocoderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|ClientInterface
     */
    private mixed $client;

    private HttpRequestGeocoder $service;

    /**
     * @var HttpDecoratorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $decorator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(ClientInterface::class);
        $this->decorator = $this->createMock(HttpDecoratorInterface::class);

        $this->service = new HttpRequestGeocoder(
            $this->client,
            $this->decorator
        );
    }

    public function testGeocodeSuccessfully()
    {
        $address = $this->createMock(Address::class);
        $coordinates = $this->createMock(Coordinates::class);

        $this->decorator->method('decorateRequest')
            ->willReturn($this->createMock(RequestInterface::class));
        $this->decorator->method('decorateResponse')
            ->willReturn($coordinates);

        $this->assertEquals($coordinates, $this->service->geocode($address));
    }

    public function testGeocodeThrowExceptionSendRequest()
    {
        $address = $this->createMock(Address::class);
        $coordinates = $this->createMock(Coordinates::class);

        $this->decorator->method('decorateRequest')
            ->willReturn($this->createMock(RequestInterface::class));
        $this->decorator->method('decorateResponse')
            ->willReturn($coordinates);

        $this->client->method('sendRequest')->willThrowException($this->createMock(ClientExceptionInterface::class));

        $this->assertNull($this->service->geocode($address));
    }

    public function testGeocodeThrowExceptionCreateRequest()
    {
        $address = $this->createMock(Address::class);
        $coordinates = $this->createMock(Coordinates::class);

        $this->decorator->method('decorateRequest')
            ->willThrowException($this->createMock(\Throwable::class));
        $this->decorator->method('decorateResponse')
            ->willReturn($coordinates);

        $this->client->method('sendRequest')
            ->willThrowException($this->createMock(ClientExceptionInterface::class));

        $this->assertNull($this->service->geocode($address));
    }

    public function testGeocodeThrowExceptionCreateResponse()
    {
        $address = $this->createMock(Address::class);

        $this->decorator->method('decorateRequest')
            ->willReturn($this->createMock(RequestInterface::class));
        $this->decorator->method('decorateResponse')
            ->willThrowException($this->createMock(\Throwable::class));

        $this->assertNull($this->service->geocode($address));
    }

}
