<?php

declare(strict_types=1);

namespace App\Tests\Module\GeoCoordinate;

use App\Module\GeoCoordinate\Repository\ResolvedAddressRepository;
use App\Module\GeoCoordinate\Service\GoogleMapsService;
use App\Module\GeoCoordinate\Service\HereMapsService;
use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;
use App\Tests\Cases\BaseWebTestCase;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;

class CoordinatesControllerTest extends BaseWebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->truncateEntities([
            \App\Module\GeoCoordinate\Entity\ResolvedAddress::class,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     *
     * @dataProvider validTestDataProvider
     */
    public function itCanGETCoordinatesFromGoogleMaps($query): void
    {
        $this->configureMapServices(
            new Coordinates(1.00, 2.00),
            $this->once(),
            new Coordinates(1.11, 2.22),
            $this->never()
        );

        $query = http_build_query($query);

        $response = $this->makeRequest('GET', "/coordinates?$query");
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            '{"data": {"lat":1.00,"lng":2.00}}',
            $response->getContent()
        );
    }

    /**
     * @test
     *
     * @dataProvider validTestDataProvider
     */
    public function itCanGETCoordinatesFromHereMaps($query): void
    {
        $this->configureMapServices(
            null,
            $this->once(),
            new Coordinates(21.33, 30.12),
            $this->once()
        );

        $query = http_build_query($query);

        $response = $this->makeRequest('GET', "/coordinates?$query");
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            '{"data": {"lat":21.33,"lng":30.12}}',
            $response->getContent()
        );
    }

    /**
     * @test
     *
     * @dataProvider validTestDataProvider
     */
    public function itCanGETCoordinatesFromDatabase($query): void
    {
        $this->configureMapServices(
            null,
            $this->never(),
            null,
            $this->never()
        );

        /** @var ResolvedAddressRepository $resolvedAddressRepository */
        $resolvedAddressRepository = $this->getService(ResolvedAddressRepository::class);
        $resolvedAddress = new Address(
            $query['countryCode'],
            $query['city'],
            $query['street'],
            $query['postcode'],
        );
        $coordinates = new Coordinates(60.122, 40.223);

        $resolvedAddressRepository->save($resolvedAddress, $coordinates);

        $query = http_build_query($query);

        $response = $this->makeRequest('GET', "/coordinates?$query");
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            '{"data": {"lat":60.122,"lng":40.223}}',
            $response->getContent()
        );
    }

    public static function validTestDataProvider(): \Generator
    {
        yield [
            'query' => [
                'countryCode' => 'pl',
                'city' => 'foo',
                'street' => 'foo',
                'postcode' => 'foo',
            ],
        ];

        yield [
            'query' => [
                'countryCode' => 'en',
                'city' => 'bar',
                'street' => 'bar',
                'postcode' => 'bar',
            ],
        ];
    }

    private function configureMapServices(
        ?Coordinates $googleMapCoordinates,
        InvokedCount $expectGoogleMapsGeocodeCall,
        ?Coordinates $hereMapsCoordinates,
        InvokedCount $expectHereGeocodeCall
    ): void {
        $gMapMocks = $this->createMock(GoogleMapsService::class);
        $gMapMocks->expects($expectGoogleMapsGeocodeCall)
            ->method('geocode')
            ->willReturn($googleMapCoordinates);

        self::getContainer()->set(GoogleMapsService::class, $gMapMocks);

        $hereMapsMock = $this->createMock(HereMapsService::class);
        $hereMapsMock->expects($expectHereGeocodeCall)
            ->method('geocode')
            ->willReturn($hereMapsCoordinates);

        self::getContainer()->set(HereMapsService::class, $hereMapsMock);
    }
}
