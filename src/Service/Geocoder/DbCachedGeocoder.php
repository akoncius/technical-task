<?php

namespace App\Service\Geocoder;

use App\Repository\AddressResolverInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class DbCachedGeocoder implements GeocoderInterface
{
    public function __construct(
        private AddressResolverInterface $addressResolver,
        private GeocoderInterface $geocoder
    ) {
    }

    public function geocode(Address $address): ?Coordinates
    {
        $resolvedAddress = $this->addressResolver->getByAddress($address);
        if ($resolvedAddress !== null) {
            return $resolvedAddress->getCoordinates();
        }

        $resolvedAddress = $this->addressResolver->saveResolvedAddress(
            $address,
            $this->geocoder->geocode($address)
        );

        return $resolvedAddress->getCoordinates();
    }
}
