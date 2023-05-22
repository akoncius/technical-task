<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Service;

use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;

interface GeocoderInterface
{
    public function geocode(Address $address): ?Coordinates;
}
