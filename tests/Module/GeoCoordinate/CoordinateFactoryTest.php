<?php

declare(strict_types=1);

namespace App\Tests\Module\GeoCoordinate;

use App\Module\GeoCoordinate\Factory\CoordinatesFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Module\GeoCoordinate\Factory\CoordinatesFactory
 */
class CoordinateFactoryTest extends KernelTestCase
{
    /** @test */
    public function itCanCreateCoordinatesObject(): void
    {
        $coordinatesFactory = new CoordinatesFactory();
        $coordinates = $coordinatesFactory->create('1.11', '2.22');

        $this->assertSame(2.22, $coordinates->getLng());
        $this->assertSame(1.11, $coordinates->getLat());
    }
}
