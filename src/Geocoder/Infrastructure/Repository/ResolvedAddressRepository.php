<?php

declare(strict_types=1);

namespace App\Geocoder\Infrastructure\Repository;

use App\Contracts\Geocoder\ValueObject\Address;
use App\Contracts\Geocoder\ValueObject\Coordinates;
use App\Geocoder\Infrastructure\Entity\ResolvedAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResolvedAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResolvedAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResolvedAddress[]    findAll()
 * @method ResolvedAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolvedAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResolvedAddress::class);
    }

    public function getByAddress(Address $address): ?ResolvedAddress
    {
        return $this->findOneBy([
            'countryCode' => $address->getCountry(),
            'city' => $address->getCity(),
            'street' => $address->getStreet(),
            'postcode' => $address->getPostcode()
        ]);
    }

    public function saveResolvedAddress(Address $address, ?Coordinates $coordinates): void
    {
        $resolvedAddress = new ResolvedAddress();
        $resolvedAddress
            ->setCountryCode($address->getCountry())
            ->setCity($address->getCity())
            ->setStreet($address->getStreet())
            ->setPostcode($address->getPostcode());

        if ($coordinates !== null) {
            $resolvedAddress
                ->setLat((string) $coordinates->getLat())
                ->setLng((string) $coordinates->getLng());
        }

        $this->getEntityManager()->persist($resolvedAddress);
        $this->getEntityManager()->flush();
    }
}
