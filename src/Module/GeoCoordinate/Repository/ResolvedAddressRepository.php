<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Repository;

use App\Module\GeoCoordinate\Entity\ResolvedAddress;
use App\Module\GeoCoordinate\ValueObject\Address;
use App\Module\GeoCoordinate\ValueObject\Coordinates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResolvedAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResolvedAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResolvedAddress[]    findAll()
 * @method ResolvedAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolvedAddressRepository extends ServiceEntityRepository implements ResolvedAddressRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResolvedAddress::class);
    }

    public function findByAddress(Address $address): ?ResolvedAddress
    {
        return $this->findOneBy([
            'countryCode' => $address->getCountry(),
            'city' => $address->getCity(),
            'street' => $address->getStreet(),
            'postcode' => $address->getPostcode(),
        ]);
    }

    public function save(Address $address, ?Coordinates $coordinates): bool
    {
        $resolvedAddress = new ResolvedAddress();
        $resolvedAddress
            ->setCountryCode($address->getCountry())
            ->setCity($address->getCity())
            ->setStreet($address->getStreet())
            ->setPostcode($address->getPostcode());

        if (null !== $coordinates) {
            $resolvedAddress
                ->setLat((string) $coordinates->getLat())
                ->setLng((string) $coordinates->getLng());
        }

        $this->getEntityManager()->persist($resolvedAddress);
        $this->getEntityManager()->flush();

        return true;
    }
}
