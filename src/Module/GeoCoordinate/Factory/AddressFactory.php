<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Factory;

use App\Module\GeoCoordinate\ValueObject\Address;
use Symfony\Component\HttpFoundation\Request;

class AddressFactory
{
    public function createFromRequest(Request $request): Address
    {
        $country = $request->query->get('countryCode', 'lt');
        $city = $request->query->get('city', 'vilnius');
        $street = $request->query->get('street', 'jasinskio 16');
        $postcode = $request->query->get('postcode', '01112');

        return new Address(
            $country,
            $city,
            $street,
            $postcode
        );
    }
}
