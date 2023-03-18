<?php
declare(strict_types=1);

namespace App\Helper;

use App\Service\GeocoderInterface;

class DefaultGeocoderChain implements GeocoderChainInterface
{
    private int $pointer = 0;
    private array $geocoders = [];

    public function __construct(GeocoderInterface ...$geocoders)
    {
        foreach ($geocoders as $geocoder) {
            $this->add($geocoder);
        }
    }

    public function add(GeocoderInterface $geocoder): void
    {
        $this->geocoders[] = $geocoder;
    }

    public function first(): ?GeocoderInterface
    {
        return $this->geocoders[0] ?? null;
    }

    public function next(): ?GeocoderInterface
    {
        $this->pointer ++;
        return $this->geocoders[$this->pointer] ?? null;
    }

    public function count(): int
    {
        return count($this->geocoders);
    }

    public function remove(GeocoderInterface $geocoder): void
    {
        foreach ($this->geocoders as $key => $currentGeocoder) {
            if ($geocoder === $currentGeocoder) {
                unset($this->geocoders[$key]);
            }
        }

        $this->geocoders = array_values($this->geocoders); // reindex table
    }

}