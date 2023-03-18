<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ResolvedAddressRepository;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocodeDbCache implements GeocodeCacheInterface
{
    private ResolvedAddressRepository $repository;

    public function __construct(ResolvedAddressRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCoordinates(Address $address): ?Coordinates
    {
        $resolvedAddress = $this->repository->getByAddress($address);

        if (!$resolvedAddress) {
            return null;
        }

        return new Coordinates((float) $resolvedAddress->getLat(), (float) $resolvedAddress->getLng());
    }

    public function saveResolvedAddress(Address $address, Coordinates $coordinates): void
    {
        $this->repository->saveResolvedAddress($address, $coordinates);
    }
}
