<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\Controller;

use App\Module\GeoCoordinate\Factory\AddressFactory;
use App\Module\GeoCoordinate\Message\FindGeoCoordinatesQuery;
use App\Shared\Controller\AbstractController;
use App\Shared\Messenger\QueryBus;
use App\Shared\Response\NordJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
    #[Route(path: '/coordinates', name: 'geocode')]
    public function find(Request $request, QueryBus $queryBus, AddressFactory $addressFactory): NordJsonResponse
    {
        $address = $addressFactory->createFromRequest($request);

        return $queryBus->dispatch(new FindGeoCoordinatesQuery($address));
    }
}
