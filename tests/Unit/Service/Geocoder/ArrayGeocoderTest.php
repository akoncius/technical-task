<?php

namespace App\Tests\Unit\Service\Geocoder;

use App\Service\Geocoder\ArrayGeocoder;
use App\Service\Geocoder\GeocoderInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;

class ArrayGeocoderTest extends TestCase
{
    public function testGeocodeEmptyArray()
    {
        $address = $this->createMock(Address::class);

        $service = new ArrayGeocoder([]);
        $this->assertNull($service->geocode($address));
    }

    public function testGeocodeReturnNull()
    {
        $address = $this->createMock(Address::class);

        $geocoder = $this->createMock(GeocoderInterface::class);

        $service = new ArrayGeocoder([$geocoder]);

        $this->assertNull($service->geocode($address));
    }

    public function testGeocodeReturnFirstAndQuit()
    {
        $address = $this->createMock(Address::class);
        $coordinate = $this->createMock(Coordinates::class);

        $geocoder1 = $this->createMock(GeocoderInterface::class);
        $geocoder1->expects($this->once())->method('geocode')->willReturn($coordinate);

        $geocoder2 = $this->createMock(GeocoderInterface::class);
        $geocoder2->expects($this->never())->method('geocode');

        $service = new ArrayGeocoder([$geocoder1, $geocoder2]);

        $this->assertEquals($coordinate, $service->geocode($address));
    }

    public function testGeocodeReturnSecond()
    {
        $address = $this->createMock(Address::class);
        $coordinate = $this->createMock(Coordinates::class);

        $geocoder1 = $this->createMock(GeocoderInterface::class);
        $geocoder1->expects($this->once())->method('geocode')->willReturn(null);

        $geocoder2 = $this->createMock(GeocoderInterface::class);
        $geocoder2->expects($this->once())->method('geocode')->willReturn($coordinate);

        $service = new ArrayGeocoder([$geocoder1, $geocoder2]);

        $this->assertEquals($coordinate, $service->geocode($address));
    }
}
