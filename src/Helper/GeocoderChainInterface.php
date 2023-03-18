<?php

declare(strict_types=1);

namespace App\Helper;

use App\Service\GeocoderInterface;

interface GeocoderChainInterface
{
    public function add(GeocoderInterface $geocoder): void;

    public function first(): ?GeocoderInterface;

    public function next(): ?GeocoderInterface;

    public function count(): int;

    public function remove(GeocoderInterface $geocoder): void;
}