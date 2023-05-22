<?php

declare(strict_types=1);

namespace App\Module\GeoCoordinate\MessageHandler;

use App\Module\GeoCoordinate\Message\FindGeoCoordinatesQuery;
use App\Module\GeoCoordinate\Service\GeocoderInterface;
use App\Shared\Response\NordJsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindGeoCoordinatesQueryHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly GeocoderInterface $geocoder,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(FindGeoCoordinatesQuery $query): NordJsonResponse
    {
        try {
            $coordinates = $this->geocoder->geocode($query->address);

            return (new NordJsonResponse())->createSuccessMessage([
                'lat' => $coordinates?->getLat(),
                'lng' => $coordinates?->getLng(),
            ]);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return (new NordJsonResponse())->createFailureMessage([
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
