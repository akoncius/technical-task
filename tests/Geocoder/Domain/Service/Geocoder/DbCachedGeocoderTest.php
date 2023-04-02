<?php

declare(strict_types=1);

namespace App\Tests\Geocoder\Domain\Service\Geocoder;

use App\Contracts\Geocoder\GeocoderInterface;
use App\Contracts\Geocoder\ValueObject\Address;
use App\Geocoder\Domain\Service\Geocoder\DbCachedGeocoder;
use App\Geocoder\Infrastructure\Entity\ResolvedAddress;
use App\Geocoder\Infrastructure\Repository\ResolvedAddressRepository;
use PHPUnit\Framework\TestCase;

class DbCachedGeocoderTest extends TestCase
{
    private ResolvedAddressRepository $resolvedAddressRepository;

    private GeocoderInterface $geocoder;

    private DbCachedGeocoder $dbCachedGeocoder;

    private Address $address;

    protected function setUp(): void
    {
        $this->address = new Address(
            'test',
            'test',
            'test',
            'test'
        );
        $this->resolvedAddressRepository = $this->createMock(ResolvedAddressRepository::class);
        $this->geocoder = $this->createMock(GeocoderInterface::class);
        $this->dbCachedGeocoder = new DbCachedGeocoder($this->resolvedAddressRepository, $this->geocoder);
    }

    public function testGeocodeWithNotEmptyDataFromDb(): void
    {
        $lat = '55.5555';
        $lng = '55.5556';
        $resolvedAddress = $this->createMock(ResolvedAddress::class);
        $resolvedAddress->method('getLng')->willReturn($lng);
        $resolvedAddress->method('getLat')->willReturn($lat);

        $this->resolvedAddressRepository
            ->expects($this->once())
            ->method('getByAddress')
            ->willReturn($resolvedAddress);

        $coordinates = $this->dbCachedGeocoder->geocode($this->address);
        $this->assertEquals($lat, $coordinates->getLat());
        $this->assertEquals($lng, $coordinates->getLng());
    }

    public function testGeocodeWithEmptyDb(): void
    {
        $this->resolvedAddressRepository
            ->expects($this->once())
            ->method('getByAddress')
            ->willReturn(null);

        $this->geocoder->expects($this->once())->method('geocode');
        $this->resolvedAddressRepository->expects($this->once())->method('saveResolvedAddress');

        $this->dbCachedGeocoder->geocode($this->address);
        $this->assertTrue(true);
    }
}
