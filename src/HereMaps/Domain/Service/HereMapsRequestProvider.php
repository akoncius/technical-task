<?php

declare(strict_types=1);

namespace App\HereMaps\Domain\Service;

use App\Contracts\Geocoder\ValueObject\Address;

class HereMapsRequestProvider
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
                'qq' => implode(
                    ';',
                    [
                        "country={$address->getCountry()}",
                        "city={$address->getCity()}",
                        "street={$address->getStreet()}",
                        "postalCode={$address->getPostcode()}",
                    ]
                ),
                'apiKey' => $this->apiKey,
            ]
        ];
    }
}
