<?php

namespace App\Tests\Unit\Service\Geocoder;

use App\Entity\ResolvedAddress;
use App\Repository\AddressResolverInterface;
use App\Service\Geocoder\DbCachedGeocoder;
use App\Service\Geocoder\GeocoderInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;

class DbCachedGeocoderTest extends TestCase
{
    private DbCachedGeocoder $service;

    /**
     * @var GeocoderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $geocoder;

    /**
     * @var AddressResolverInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(AddressResolverInterface::class);
        $this->geocoder = $this->createMock(GeocoderInterface::class);

        $this->service = new DbCachedGeocoder(
            $this->repository,
            $this->geocoder
        );
    }

    public function testGeocodeReturnFromDb()
    {
        $address = $this->createMock(Address::class);
        $coordinates = $this->createMock(Coordinates::class);

        $resolvedAddress = $this->createMock(ResolvedAddress::class);
        $resolvedAddress->method('getCoordinates')->willReturn($coordinates);

        $this->repository->method('getByAddress')->willReturn($resolvedAddress);
        $this->repository->expects($this->never())->method('saveResolvedAddress');
        $this->geocoder->expects($this->never())->method('geocode');

        $this->assertEquals($coordinates, $this->service->geocode($address));
    }

    public function testGeocodeReturnFromExternalAndSave()
    {
        $address = $this->createMock(Address::class);
        $coordinates = $this->createMock(Coordinates::class);

        $resolvedAddress = $this->createMock(ResolvedAddress::class);
        $resolvedAddress->method('getCoordinates')->willReturn($coordinates);

        $this->repository->method('getByAddress')->willReturn(null);
        $this->repository->expects($this->once())->method('saveResolvedAddress')->willReturn($resolvedAddress);
        $this->geocoder->expects($this->once())->method('geocode');

        $this->assertEquals($coordinates, $this->service->geocode($address));
    }
}
