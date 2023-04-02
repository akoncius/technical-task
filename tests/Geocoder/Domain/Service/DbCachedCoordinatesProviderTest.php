<?php

declare(strict_types=1);

namespace App\Tests\Geocoder\Domain\Service;

use App\Geocoder\Domain\Service\DbCachedCoordinatesProvider;
use App\Geocoder\Infrastructure\Entity\ResolvedAddress;
use PHPUnit\Framework\TestCase;

class DbCachedCoordinatesProviderTest extends TestCase
{
    public function testFromDbEntityWithEmptyLat(): void
    {
        $address = $this->createMock(ResolvedAddress::class);
        $address->expects($this->once())->method('getLat')->willReturn(null);
        $this->assertNull(DbCachedCoordinatesProvider::fromDbEntity($address));
    }

    public function testFromDbEntityWithEmptyLng(): void
    {
        $address = $this->createMock(ResolvedAddress::class);
        $address->expects($this->once())->method('getLng')->willReturn(null);
        $address->expects($this->once())->method('getLat')->willReturn('55.556');
        $this->assertNull(DbCachedCoordinatesProvider::fromDbEntity($address));
    }

    public function testFromDbEntity(): void
    {
        $address = $this->createMock(ResolvedAddress::class);
        $lat = '55.555';
        $lng = '55.556';
        $address->method('getLat')->willReturn($lat);
        $address->method('getLng')->willReturn($lng);

        $coordinates = DbCachedCoordinatesProvider::fromDbEntity($address);
        $this->assertSame((float)$lat, $coordinates->getLat());
        $this->assertSame((float)$lng, $coordinates->getLng());
    }
}
