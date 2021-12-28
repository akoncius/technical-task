<?php

namespace App\Entity;

use App\Repository\ResolvedAddressRepository;
use App\ValueObject\Coordinates;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=ResolvedAddressRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="search_idx", columns={"country", "city", "street", "postcode"})
 * })
 */
class ResolvedAddress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $city = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $street = null;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private ?string $postcode = null;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?float $lat = null;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?float $lng = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }

    #[Pure] public function getCoordinates(): ?Coordinates
    {
        $coordinates = null;

        if ($this->lng !== null && $this->lat !== null) {
            $coordinates = new Coordinates($this->lat, $this->lng);
        }

        return $coordinates;
    }

    public function setCoordinates(?Coordinates $coordinates): void
    {
        if ($coordinates !== null) {
            $this->lat = $coordinates->getLat();
            $this->lng = $coordinates->getLng();
        } else {
            $this->lat = $this->lng = null;
        }
    }
}
