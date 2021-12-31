<?php

namespace App\ArgumentResolver;

use App\ValueObject\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AddressValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Address::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $country = $request->get('country');
        $city = $request->get('city');
        $street = $request->get('street');
        $postcode = $request->get('postcode');

        if (!$country && !$city && !$street && !$postcode) {
            $country = 'lithuania';
            $city = 'vilnius';
            $street = 'jasinskio 16';
            $postcode = '01112';
        }

        yield new Address((string) $country, (string) $city, (string) $street, (string) $postcode);
    }
}
