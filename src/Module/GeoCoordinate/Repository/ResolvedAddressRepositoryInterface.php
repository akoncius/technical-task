<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Repository;

use App\Module\GeoCoordinate\Entity\ResolvedAddress;
use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;

interface ResolvedAddressRepositoryInterface
{
    public function findByAddress(Address $address): ?ResolvedAddress;

    public function save(Address $address, Coordinates $coordinates): bool;
}
