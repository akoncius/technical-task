<?php

namespace App\Tests\Unit\ArgumentResolver;

use App\ArgumentResolver\AddressValueResolver;
use App\ValueObject\Address;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AddressValueResolverTest extends TestCase
{
    private AddressValueResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new AddressValueResolver();
    }

    public function testResolverDefaultValues()
    {
        $request = $this->createMock(Request::class);
        $request->method('get')
            ->withConsecutive(['country'], ['city'], ['street'], ['postcode'])
            ->willReturnOnConsecutiveCalls(null, null, null, null);

        $argument = $this->createMock(ArgumentMetadata::class);

        $address = iterator_to_array($this->resolver->resolve($request, $argument))[0];

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('lithuania', $address->getCountry());
        $this->assertEquals('vilnius', $address->getCity());
        $this->assertEquals('jasinskio 16', $address->getStreet());
        $this->assertEquals('01112', $address->getPostcode());
    }

    public function testResolverSomeValues()
    {
        $request = $this->createMock(Request::class);
        $request->method('get')
            ->withConsecutive(['country'], ['city'], ['street'], ['postcode'])
            ->willReturnOnConsecutiveCalls('country', 'city', null, null);
        $argument = $this->createMock(ArgumentMetadata::class);

        $address = iterator_to_array($this->resolver->resolve($request, $argument))[0];

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('country', $address->getCountry());
        $this->assertEquals('city', $address->getCity());
        $this->assertEquals('', $address->getStreet());
        $this->assertEquals('', $address->getPostcode());
    }

    public function testResolverAllValues()
    {
        $request = $this->createMock(Request::class);
        $request->method('get')
            ->withConsecutive(['country'], ['city'], ['street'], ['postcode'])
            ->willReturnOnConsecutiveCalls('country', 'city', 'street', 'postcode');
        $argument = $this->createMock(ArgumentMetadata::class);

        $address = iterator_to_array($this->resolver->resolve($request, $argument))[0];

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('country', $address->getCountry());
        $this->assertEquals('city', $address->getCity());
        $this->assertEquals('street', $address->getStreet());
        $this->assertEquals('postcode', $address->getPostcode());
    }
}
