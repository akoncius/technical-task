<?php

namespace App\Tests\Integration\Repository;

use App\DataFixtures\AppFixtures;
use App\Entity\ResolvedAddress;
use App\Repository\AddressResolverInterface;
use App\ValueObject\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResolvedAddressRepositoryTest extends KernelTestCase
{
    private AddressResolverInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        /** @var \Doctrine\Bundle\FixturesBundle\Purger\ORMPurgerFactory $purgerFactory */
        $purgerFactory = $container->get('doctrine.fixtures.purger.orm_purger_factory');
        $purger = $purgerFactory->createForEntityManager(
            emName: null,
            em: $em
        );
        $purger->purge();
        $container->get(AppFixtures::class)->load($em);

        $this->repository = $container->get(AddressResolverInterface::class);
    }

    public function testGetByAddressReturnResolvedAddress()
    {
        $address = new Address('country', 'city', 'street', '12345');
        $resolved = $this->repository->getByAddress($address);

        $this->assertInstanceOf(ResolvedAddress::class, $resolved);
        $this->assertEquals($address->getCountry(), $resolved->getCountry());
        $this->assertEquals($address->getCity(), $resolved->getCity());
        $this->assertEquals($address->getStreet(), $resolved->getStreet());
        $this->assertEquals($address->getPostcode(), $resolved->getPostcode());
    }

    public function testGetByAddressReturnNull()
    {
        $address = new Address('no country', 'no city', 'nonono', 'code');

        $this->assertNull($this->repository->getByAddress($address));
    }

    public function testSaveResolvedAddress()
    {
        $address = new Address('new country', 'city', 'nonono', 'code');

        $resolved = $this->repository->saveResolvedAddress($address, null);

        $this->assertEquals($address->getCountry(), $resolved->getCountry());
        $this->assertEquals($address->getCity(), $resolved->getCity());
        $this->assertEquals($address->getStreet(), $resolved->getStreet());
        $this->assertEquals($address->getPostcode(), $resolved->getPostcode());
    }
}
