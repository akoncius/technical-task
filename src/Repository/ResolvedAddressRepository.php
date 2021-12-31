<?php

namespace App\Repository;

use App\Entity\ResolvedAddress;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResolvedAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResolvedAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResolvedAddress[]    findAll()
 * @method ResolvedAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolvedAddressRepository extends ServiceEntityRepository implements AddressResolverInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResolvedAddress::class);
    }

    public function getByAddress(Address $address): ?ResolvedAddress
    {
        return $this->findOneBy([
            'country' => $address->getCountry(),
            'city' => $address->getCity(),
            'street' => $address->getStreet(),
            'postcode' => $address->getPostcode()
        ]);
    }

    public function saveResolvedAddress(Address $address, ?Coordinates $coordinates): ResolvedAddress
    {
        $resolvedAddress = new ResolvedAddress();
        $resolvedAddress->setCountry($address->getCountry());
        $resolvedAddress->setCity($address->getCity());
        $resolvedAddress->setStreet($address->getStreet());
        $resolvedAddress->setPostcode($address->getPostcode());
        $resolvedAddress->setCoordinates($coordinates);

        $em = $this->getEntityManager();
        $em->persist($resolvedAddress);
        $em->flush();

        return $resolvedAddress;
    }
}
