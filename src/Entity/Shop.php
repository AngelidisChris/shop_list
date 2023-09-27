<?php

namespace App\Entity;

use App\Dto\ShopDTO;
use App\Repository\ShopRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ShopRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'There is already a shop with this name')]
class Shop
{
    #[Groups(["show_shop", "update_shop"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["show_shop", "update_shop"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopOwner $shopOwner = null;

    #[Groups(["show_shop", "update_shop"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopCategory $shopCategory = null;

    #[Groups(["show_shop", "update_shop"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 1000])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(["show_shop", "update_shop"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64)]
    private ?string $openHours = null;

    #[Groups(["show_shop", "update_shop"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64)]
    private ?string $city = null;

    #[Groups(["show_shop", "update_shop"])]
    #[Assert\Length(["min" => 0, "max" => 64])]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $address = null;

    public function __construct(ShopDTO $shopDTO, ShopOwner $shopOwner, ShopCategory $shopCategory)
    {
        $this->setName($shopDTO->name);
        $this->setAddress($shopDTO->address);
        $this->setCity($shopDTO->city);
        $this->setDescription($shopDTO->description);
        $this->setOpenHours($shopDTO->openHours);
        $this->setShopCategory($shopCategory);
        $this->setShopOwner($shopOwner);
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

    public function updateShop(ShopDTO $shopDTO, ShopCategory $shopCategory): static
    {
        $this->setName($shopDTO->name);
        $this->setAddress($shopDTO->address);
        $this->setCity($shopDTO->city);
        $this->setDescription($shopDTO->description);
        $this->setOpenHours($shopDTO->openHours);
        $this->setShopCategory($shopCategory);
        return $this;
    }
}
