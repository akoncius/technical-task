<?php

declare(strict_types=1);

namespace App\Geocoder\Domain\Service\Geocoder;

use App\Contracts\Geocoder\GeocoderInterface;
use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\Geocoder\Domain\Service\DbCachedCoordinatesProvider;
use App\Geocoder\Infrastructure\Repository\ResolvedAddressRepository;

class DbCachedGeocoder implements GeocoderInterface
{
    private ResolvedAddressRepository $repository;

    private GeocoderInterface $geocoder;

    public function __construct(
        ResolvedAddressRepository $repository,
        GeocoderInterface $geocoder
    ) {
        $this->repository = $repository;
        $this->geocoder = $geocoder;
    }

    public function geocode(Address $address): ?Coordinates
    {
        $resolvedAddress = $this->repository->getByAddress($address);

        if ($resolvedAddress !== null) {
            return DbCachedCoordinatesProvider::fromDbEntity($resolvedAddress);
        }

        $coordinates = $this->geocoder->geocode($address);

        $this->repository->saveResolvedAddress(
            $address,
            $coordinates
        );

        return $coordinates;
    }
}
