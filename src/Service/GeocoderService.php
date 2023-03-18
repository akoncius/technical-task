<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\GeocoderChainInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderService implements GeocoderInterface
{
    private GeocoderChainInterface $geocodeChain;

    private ?GeocodeCacheInterface $cache = null;

    public function __construct(GeocoderChainInterface $geocodeChain)
    {
        $this->geocodeChain = $geocodeChain;
    }

    public function useGeocodeCache(GeocodeCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function geocode(Address $address): ?Coordinates
    {
        if ($this->cache) {
            $coordinates = $this->cache->getCoordinates($address);

            if ($coordinates) {
                return $coordinates;
            }
        }

        $geocoder = $this->geocodeChain->first();

        if (null === $geocoder) {
            return null;
        }

        do {
            $coordinates = $geocoder->geocode($address);
        } while (null === $coordinates && ($geocoder = $this->geocodeChain->next()));

        if ($this->cache && $coordinates) {
            $this->cache->saveResolvedAddress($address, $coordinates);
        }

        return $coordinates;
    }
}