<?php

namespace App\Entity;

use App\Repository\ShopCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShopCategoryRepository::class)]
class ShopCategory
{
    #[Groups(["show_shop", "update_shop"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["show_shop", "update_shop"])]
    #[ORM\Column(length: 64, unique: true)]
    private ?string $name = null;

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
}
