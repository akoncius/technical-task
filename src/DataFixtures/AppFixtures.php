<?php

namespace App\DataFixtures;

use App\Entity\ResolvedAddress;
use App\ValueObject\Coordinates;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $address = new ResolvedAddress();
        $address->setCountry('country');
        $address->setStreet('street');
        $address->setCity('city');
        $address->setPostcode('12345');
        $address->setCoordinates(new Coordinates(1, 2));

        $manager->persist($address);
        $manager->flush();
    }
}
