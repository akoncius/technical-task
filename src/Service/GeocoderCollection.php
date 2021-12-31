<?php

namespace App\Service;

use App\Service\Geocoder\GeocoderInterface;

class GeocoderCollection implements GeocoderCollectionInterface
{
    public function __construct(
        private iterable $geocoders
    ) {
        $this->geocoders = $geocoders instanceof \Traversable ? iterator_to_array($geocoders) : $geocoders;
    }

    public function getByName(string $name): GeocoderInterface
    {
        $geocoder = $this->geocoders[$name] ?? null;

        if (!$geocoder instanceof GeocoderInterface) {
            throw new \RuntimeException('Geocoder is not found');
        }

        return $geocoder;
    }
}
