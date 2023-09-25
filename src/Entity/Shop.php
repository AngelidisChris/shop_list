<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopOwner $shopOwner = null;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: ShopCategory::class)]
    private Collection $shopCategory;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 64)]
    private ?string $openHours = null;

    #[ORM\Column(length: 64)]
    private ?string $city = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $address = null;

    public function __construct()
    {
        $this->shopCategory = new ArrayCollection();
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

    /**
     * @return Collection<int, ShopCategory>
     */
    public function getShopCategory(): Collection
    {
        return $this->shopCategory;
    }

    public function addShopCategory(ShopCategory $shopCategory): static
    {
        if (!$this->shopCategory->contains($shopCategory)) {
            $this->shopCategory->add($shopCategory);
            $shopCategory->setShop($this);
        }

        return $this;
    }

    public function removeShopCategory(ShopCategory $shopCategory): static
    {
        if ($this->shopCategory->removeElement($shopCategory)) {
            // set the owning side to null (unless already changed)
            if ($shopCategory->getShop() === $this) {
                $shopCategory->setShop(null);
            }
        }

        return $this;
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
