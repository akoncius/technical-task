<?php

namespace App\Helper;

use App\Service\DummyGeocoder;
use PHPUnit\Framework\TestCase;

class DefaultGeocoderChainTest extends TestCase
{
    public function testCount()
    {
        $geocoder = $this->createMock(DummyGeocoder::class);
        $chain = new DefaultGeocoderChain();

        $chain->add($geocoder);

        $this->assertEquals(1, $chain->count());
    }

    public function testFirst()
    {
        $geocoder = $this->createMock(DummyGeocoder::class);
        $chain = new DefaultGeocoderChain();

        $chain->add($geocoder);

        $this->assertEquals($geocoder, $chain->first());
    }

    public function testAdd()
    {
        $geocoder = $this->createMock(DummyGeocoder::class);
        $chain = new DefaultGeocoderChain();

        $chain->add($geocoder);

        $this->assertEquals($geocoder, $chain->first());
    }

    public function testRemove()
    {
        $geocoder = $this->createMock(DummyGeocoder::class);
        $chain = new DefaultGeocoderChain();

        $chain->add($geocoder);
        $chain->remove($geocoder);

        $this->assertEquals(0, $chain->count());
    }

    public function testNext()
    {
        $geocoder = $this->createMock(DummyGeocoder::class);
        $chain = new DefaultGeocoderChain();

        $chain->add($geocoder);
        $chain->add($geocoder);

        $chain->first();
        $this->assertNotEquals(null, $chain->next());
    }

    public function testNoNext()
    {
        $geocoder = $this->createMock(DummyGeocoder::class);
        $chain = new DefaultGeocoderChain();

        $chain->add($geocoder);

        $chain->first();
        $this->assertEquals(null, $chain->next());
    }

}
