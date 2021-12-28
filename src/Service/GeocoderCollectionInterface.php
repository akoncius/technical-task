<?php

namespace App\Service;

use App\Service\Geocoder\GeocoderInterface;

interface GeocoderCollectionInterface
{
    /**
     * Return GeocoderInterface by $name from collection if it exists, otherwise throws exception
     *
     * @param string $name
     * @return GeocoderInterface
     * @throws \RuntimeException
     */
    public function getByName(string $name): GeocoderInterface;
}
