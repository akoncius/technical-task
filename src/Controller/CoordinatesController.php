<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GeocoderCollectionInterface;
use App\ValueObject\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
    #[Route(
        path: "/coordinates",
        name: "coordinates",
        defaults: [
            'geocoder' => 'geocoder.dbcached.array',
        ],
    )]
    #[Route(
        path: "/gmaps",
        name: "gmaps",
        defaults: [
            'geocoder' => 'geocoder.dbcached.google',
        ],
    )]
    #[Route(
        path: "/hmaps",
        name: "hmaps",
        defaults: [
            'geocoder' => 'geocoder.dbcached.hereapi',
        ],
    )]
    public function geocodeAction(
        Request $request,
        Address $address,
        GeocoderCollectionInterface $geocoderCollection,
    ): Response {
        $name = $request->attributes->get('geocoder');
        $coordinate = $geocoderCollection->getByName($name)->geocode($address);
        return $this->json($coordinate ?: []);
    }
}
