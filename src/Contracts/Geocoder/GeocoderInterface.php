<?php

declare(strict_types=1);

namespace App\Contracts\Geocoder;

use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;

interface GeocoderInterface
{
    public function geocode(Address $address): ?Coordinates;
}
