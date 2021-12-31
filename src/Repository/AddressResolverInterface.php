<?php

namespace App\Repository;

use App\Entity\ResolvedAddress;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

interface AddressResolverInterface
{
    /**
     * Return ResolvedAddress by $address if it exists in database, otherwise null
     * @param Address $address
     * @return ResolvedAddress|null
     */
    public function getByAddress(Address $address): ?ResolvedAddress;

    /**
     * Save and return ResolvedAddress
     *
     * @param Address $address
     * @param Coordinates|null $coordinates
     * @return ResolvedAddress
     */
    public function saveResolvedAddress(Address $address, ?Coordinates $coordinates): ResolvedAddress;
}
