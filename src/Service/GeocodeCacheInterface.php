<?php
declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

interface GeocodeCacheInterface
{
    public function getCoordinates(Address $address): ?Coordinates;

    public function saveResolvedAddress(Address $address, Coordinates $coordinates): void;
}