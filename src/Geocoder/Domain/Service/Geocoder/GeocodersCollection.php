<?php

declare(strict_types=1);

namespace App\Geocoder\Domain\Service\Geocoder;

use App\Contracts\Geocoder\GeocoderInterface;
use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\Geocoder\Domain\Exception\UnsupportedGeocoderException;

class GeocodersCollection implements GeocoderInterface
{
    /**
     * @var GeocoderInterface[]
     */
    private array $geoCoders;

    public function __construct(iterable $geoCoders)
    {
        foreach ($geoCoders as $geoCoder) {
            if (!$geoCoder instanceof GeocoderInterface) {
                throw new UnsupportedGeocoderException('Unsupported geo coder');
            }

            $this->geoCoders[] = $geoCoder;
        }
    }

    public function geocode(Address $address): ?Coordinates
    {
        $coordinates = null;

        foreach ($this->geoCoders as $geoCoder) {
            $coordinates = $geoCoder->geocode($address);
            if ($coordinates !== null) {
                break;
            }
        }

        return $coordinates;
    }
}
