<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Factory;

use App\Module\GeoCoordinate\ValueObject\Coordinates;

class CoordinatesFactory
{
    public function create(string $lat, string $lng): Coordinates
    {
        return new Coordinates(
            (float) $lat,
            (float) $lng
        );
    }
}
