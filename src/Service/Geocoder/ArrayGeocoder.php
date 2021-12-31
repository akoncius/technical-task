<?php

namespace App\Service\Geocoder;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class ArrayGeocoder implements GeocoderInterface
{
    public function __construct(
        private array $geocoders
    ) {
    }

    public function geocode(Address $address): ?Coordinates
    {
        $coordinate = null;

        foreach ($this->geocoders as $geocoder) {
            /** @var GeocoderInterface $geocoder */

            $coordinate = $geocoder->geocode($address);
            if ($coordinate !== null) {
                break;
            }
        }

        return $coordinate;
    }
}
