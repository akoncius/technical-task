<?php

namespace App\Tests\Unit\Service\Geocoder\HttpDecorator;

use App\Service\Geocoder\HttpDecorator\HereapiHttpDecorator;
use App\ValueObject\Address;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HereapiHttpDecoratorTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|RequestFactoryInterface
     */
    private mixed $requestFactory;
    private HereapiHttpDecorator $httpDecorator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);

        $this->httpDecorator = new HereapiHttpDecorator(
            $this->requestFactory,
            'apiKey'
        );
    }

    public function testDecorateRequest()
    {
        $address = $this->createMock(Address::class);
        $address->method('getStreet')->willReturn('street1');
        $address->method('getCountry')->willReturn('country1');
        $address->method('getCity')->willReturn('city1');
        $address->method('getPostcode')->willReturn('zipcode');

        $request = $this->createMock(RequestInterface::class);

        $this->requestFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with('GET', 'https://geocode.search.hereapi.com/v1/geocode?qq=country%3Dcountry1%3Bcity%3Dcity1%3Bstreet%3Dstreet1%3BpostalCode%3Dzipcode&apiKey=apiKey')
            ->willReturn($request);

        $this->httpDecorator->decorateRequest($address);
    }

    public function testDecorateResponse()
    {
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode([
            'items' => [
                [
                    'resultType' => 'houseNumber',
                    'position' => [
                        'lat' => 1,
                        'lng' => 2,
                    ],
                ],
            ],
        ]));

        $coordinates = $this->httpDecorator->decorateResponse($response);
        $this->assertNotNull($coordinates);
        $this->assertEquals(1, $coordinates->getLat());
        $this->assertEquals(2, $coordinates->getLng());
    }
}
