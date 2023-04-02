<?php

declare(strict_types=1);

namespace App\Tests\Geocoder\Domain\Service\Geocoder;

use App\Contracts\Geocoder\GeocoderInterface;
use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\Geocoder\Domain\Exception\UnsupportedGeocoderException;
use App\Geocoder\Domain\Service\Geocoder\GeocodersCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

class GeocodersCollectionTest extends TestCase
{
    private GeocoderInterface $geoCoder1;

    private GeocoderInterface $geoCoder2;

    private GeocodersCollection $geocoderCollection;

    protected function setUp(): void
    {
        $this->geoCoder1 = $this->createMock(GeocoderInterface::class);
        $this->geoCoder2 = $this->createMock(GeocoderInterface::class);
        $this->geocoderCollection = new GeocodersCollection([$this->geoCoder1, $this->geoCoder2]);
    }

    public function testCreatingGeocoderCollectionWithUnsupportedGeoCoder(): void
    {
        $this->expectException(UnsupportedGeocoderException::class);
        $testObject = new stdClass();
        new GeocodersCollection([$testObject]);
    }

    public function testGeocodeWithResultFromTheSecondOne(): void
    {
        $coordinates = new Coordinates(55.555, 55.556);
        $this->geoCoder1->expects($this->once())->method('geocode')->willReturn(null);
        $this->geoCoder2->expects($this->once())->method('geocode')->willReturn($coordinates);

        $result = $this->geocoderCollection->geocode($this->createMock(Address::class));

        $this->assertSame($coordinates, $result);
    }
}
