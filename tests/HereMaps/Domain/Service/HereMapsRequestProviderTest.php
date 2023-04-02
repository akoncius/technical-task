<?php

declare(strict_types=1);

namespace App\Tests\HereMaps\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Address;
use App\HereMaps\Domain\Service\HereMapsRequestProvider;
use PHPUnit\Framework\TestCase;

class HereMapsRequestProviderTest extends TestCase
{
    private HereMapsRequestProvider $hereMapsRequestProvider;

    protected function setUp(): void
    {
        $this->hereMapsRequestProvider = new HereMapsRequestProvider('test');
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
                'qq' => "country={$address->getCountry()};city={$address->getCity()};street={$address->getStreet()};postalCode={$address->getPostcode()}",
                'apiKey' => 'test',
            ]
        ];

        $this->assertEquals($expectedResult, $this->hereMapsRequestProvider->fromAddress($address));
    }
}
