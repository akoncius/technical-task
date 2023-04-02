<?php

declare(strict_types=1);

namespace App\Geocoder\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\Geocoder\Infrastructure\Entity\ResolvedAddress;

class DbCachedCoordinatesProvider
{
    public static function fromDbEntity(ResolvedAddress $address): ?Coordinates
    {
        if ($address->getLat() !== null && $address->getLng() !== null) {
            return new Coordinates((float)$address->getLat(), (float)$address->getLng());
        }

        return null;
    }
}
