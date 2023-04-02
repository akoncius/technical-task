<?php

declare(strict_types=1);

namespace App\GoogleMaps\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Address;

class GoogleMapsRequestProvider
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function fromAddress(Address $address): array
    {
        return [
            'query' => [
                'address' => $address->getStreet(),
                'components' => implode(
                    '|',
                    [
                        "country:{$address->getCountry()}",
                        "locality:{$address->getCity()}",
                        "postal_code:{$address->getPostcode()}",
                    ]
                ),
                'key' => $this->apiKey,
            ]
        ];
    }
}
