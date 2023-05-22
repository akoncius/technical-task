<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Service;

use App\Module\GeoCoordinate\Repository\ResolvedAddressRepositoryInterface;
use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;

class CompositeGeoCoderService implements GeocoderInterface
{
    public function __construct(
        private readonly array $geocoderServices,
        private readonly ResolvedAddressRepositoryInterface $resolvedAddressRepository
    ) {
    }

    public function geocode(Address $address): ?Coordinates
    {
        foreach ($this->geocoderServices as $geocoderService) {
            $coordinates = $geocoderService->geocode($address);

            if (null !== $coordinates) {
                $this->resolvedAddressRepository->save($address, $coordinates);

                return $coordinates;
            }
        }

        return null;
    }
}
