<?php

declare(strict_types=1);

namespace App\Tests\GoogleMaps\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Address;
use App\GoogleMaps\Domain\Service\GoogleMapsRequestProvider;
use PHPUnit\Framework\TestCase;

class GoogleMapsRequestProviderTest extends TestCase
{
    private GoogleMapsRequestProvider $googleMapsRequestProvider;

    protected function setUp(): void
    {
        $this->googleMapsRequestProvider = new GoogleMapsRequestProvider('test');
    }

    public function testFromAddress(): void
    {
        $address = new Address(
            'test_country',
            'test_city',
            'test_street',
            'test_postcode'
        );
        $expectedResult = [
            'query' => [
                'address' => $address->getStreet(),
                'components' => "country:{$address->getCountry()}|locality:{$address->getCity()}|postal_code:{$address->getPostcode()}",
                'key' => 'test',
            ]
        ];

        $this->assertEquals($expectedResult, $this->googleMapsRequestProvider->fromAddress($address));
    }
}
