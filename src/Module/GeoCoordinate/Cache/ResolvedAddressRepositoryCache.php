<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Cache;

use App\Module\GeoCoordinate\Entity\ResolvedAddress;
use App\Module\GeoCoordinate\Repository\ResolvedAddressRepository;
use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ResolvedAddressRepositoryCache extends ResolvedAddressRepository
{
    private const CACHE_PREFIX = 'resolved_address';

    private CacheInterface $resolvedAddressCache;

    #[Required]
    public function setCache(CacheInterface $resolvedAddressCache): void
    {
        $this->resolvedAddressCache = $resolvedAddressCache;
    }

    public function findByAddress(Address $address): ?ResolvedAddress
    {
        return $this->resolvedAddressCache->get(
            $this->getCacheKey($address),
            fn () => parent::findByAddress($address)
        );
    }

    public function save(Address $address, ?Coordinates $coordinates): bool
    {
        if (parent::save($address, $coordinates)) {
            $this->resolvedAddressCache->delete($this->getCacheKey($address));

            return true;
        }

        return false;
    }

    private function getCacheKey(Address $address): string
    {
        return \sprintf('%s.%s', self::CACHE_PREFIX, md5(json_encode([
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'postcode' => $address->getPostcode(),
            'country' => $address->getCountry(),
        ])));
    }
}
