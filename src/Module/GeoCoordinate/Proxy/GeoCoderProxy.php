<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Proxy;

use App\Module\GeoCoordinate\Entity\ResolvedAddress;
use App\Module\GeoCoordinate\Factory\CoordinatesFactory;
use App\Module\GeoCoordinate\Repository\ResolvedAddressRepositoryInterface;
use App\Module\GeoCoordinate\Service\CompositeGeocoderService;
use App\Module\GeoCoordinate\Service\GeocoderInterface;
use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;

class GeoCoderProxy implements GeocoderInterface
{
    public function __construct(
        private readonly CompositeGeocoderService $geocoderService,
        private readonly ResolvedAddressRepositoryInterface $resolvedAddressRepository,
        private readonly CoordinatesFactory $coordinatesFactory
    ) {
    }

    public function geocode(Address $address): ?Coordinates
    {
        /** @var ResolvedAddress|null $row */
        if ($row = $this->resolvedAddressRepository->findByAddress($address)) {
            return $this->coordinatesFactory->create($row->getLat(), $row->getLng());
        }

        return $this->geocoderService->geocode($address);
    }
}
