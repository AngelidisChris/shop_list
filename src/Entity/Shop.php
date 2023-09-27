<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ShopRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'There is already a shop with this name')]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64, unique: true)]
    private ?string $name = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopOwner $shopOwner = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopCategory $shopCategory = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 1000])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64)]
    private ?string $openHours = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64)]
    private ?string $city = null;

    #[Assert\Length(["min" => 0, "max" => 64])]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $address = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShopOwner(): ?ShopOwner
    {
        return $this->shopOwner;
    }

    public function setShopOwner(?ShopOwner $shopOwner): static
    {
        $this->shopOwner = $shopOwner;

        return $this;
    }

    public function getShopCategory(): ?ShopCategory
    {
        return $this->shopCategory;
    }

    public function setShopCategory(?ShopCategory $shopCategory): void
    {
        $this->shopCategory = $shopCategory;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOpenHours(): ?string
    {
        return $this->openHours;
    }

    public function setOpenHours(string $openHours): static
    {
        $this->openHours = $openHours;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
