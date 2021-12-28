<?php

declare(strict_types=1);

namespace App\Service\Geocoder;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

interface GeocoderInterface
{
    /**
     * Converts $address to Coordinates using external services if possible, otherwise returns null
     *
     * @param Address $address
     * @return Coordinates|null
     */
    public function geocode(Address $address): ?Coordinates;
}
