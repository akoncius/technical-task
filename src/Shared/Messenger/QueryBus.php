<?php

declare(strict_types=1);

namespace App\Shared\Messenger;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus
{
    use HandleTrait;

    public function __construct(private readonly MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(object $query): mixed
    {
        return $this->handle($query);
    }
}
