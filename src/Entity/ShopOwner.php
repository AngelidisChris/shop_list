<?php

namespace App\Entity;

use App\Repository\ShopOwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use JMS\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: ShopOwnerRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class ShopOwner implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(["show_shop"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["show_shop"])]
    #[Assert\Email]
    #[Assert\NotNull]
    #[Assert\Length(["max" => 180])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[Assert\PasswordStrength([
        'minScore' => PasswordStrength::STRENGTH_WEAK,
        'message' => 'Your password is too easy to guess. Try a 10 character long password with at least one symbol, letter and number'
    ])]
    #[Assert\NotNull]
    private ?string $rawPassword;

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(["show_shop"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(["max" => 64])]
    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'shopOwner', targetEntity: Shop::class, orphanRemoval: true)]
    private Collection $shops;

    #[Pure]
    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    /**
     * @return string
     */
    public function getRawPassword(): string
    {
        return $this->rawPassword;
    }


    /**
     * @param string|null $rawPassword
     */
    public function setRawPassword(?string $rawPassword): void
    {
        $this->rawPassword = $rawPassword;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->setShopOwner($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop)) {
            // set the owning side to null (unless already changed)
            if ($shop->getShopOwner() === $this) {
                $shop->setShopOwner(null);
            }
        }

        return $this;
    }
}
