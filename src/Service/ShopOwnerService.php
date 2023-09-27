<?php

namespace App\Service;

use App\Dto\ShopOwnerDTO;
use App\Entity\ShopOwner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShopOwnerService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private ValidatorInterface $validator;
    private Security $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param ValidatorInterface $validator
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->validator = $validator;
        $this->security = $security;
    }

    /**
     * @param ShopOwnerDTO $shopOwnerDTO
     * @return ShopOwner
     *@throws InvalidArgumentException
     */
    public function create(ShopOwnerDTO $shopOwnerDTO): ShopOwner {
        $shopOwner = new ShopOwner();
        $shopOwner->setEmail($shopOwnerDTO->email);
        $shopOwner->setName($shopOwnerDTO->name);
        $shopOwner->setRawPassword($shopOwnerDTO->password);
        $shopOwner->setRoles(["ROLE_SHOP_OWNER"]);

        $errors = $this->validator->validate($shopOwner);
        if ($errors->count() > 0){
            throw new InvalidArgumentException(
                json_encode([
                    "errors" => ValidatorService::buildErrorArray($errors)
                ]),
                422
            );
        }

        // encode the plain password
        $shopOwner->setPassword(
            $this->userPasswordHasher->hashPassword(
                $shopOwner,
                $shopOwnerDTO->password
            )
        );

        $this->entityManager->persist($shopOwner);
        $this->entityManager->flush();

        return $shopOwner;
    }

    public function getCurrent(): ?UserInterface {
        return $this->security->getUser();
    }
}