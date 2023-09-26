<?php

namespace App\Service;

use App\Dto\CreateShopOwnerDTO;
use App\Entity\ShopOwner;
use App\Repository\ShopOwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShopOwnerService
{
    private ShopOwnerRepository $shopOwnerRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private ValidatorInterface $validator;

    /**
     * @param ShopOwnerRepository $shopOwnerRepository
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param ValidatorInterface $validator
     */
    public function __construct(ShopOwnerRepository $shopOwnerRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator)
    {
        $this->shopOwnerRepository = $shopOwnerRepository;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->validator = $validator;
    }

    /**
     * @param CreateShopOwnerDTO $shopOwnerDTO
     * @throws InvalidArgumentException
     * @return ShopOwner
     */
    public function createShopOwner(CreateShopOwnerDTO $shopOwnerDTO): ShopOwner {

        $existingShopOwner = $this->getShopOwnerByEmail($shopOwnerDTO->email);

        if ($existingShopOwner !== null){
            throw new InvalidArgumentException(
                "Shop owner with email {$shopOwnerDTO->email} already exist!",
                400);
        }

        $user = new ShopOwner();
        $user->setEmail($shopOwnerDTO->email);
        $user->setName($shopOwnerDTO->name);
        $user->setRawPassword($shopOwnerDTO->password);
        $user->setRoles(["ROLE_SHOP_OWNER"]);

        $errors = $this->validator->validate($user);
        if ($errors->count() > 0){
            throw new InvalidArgumentException(
                json_encode([
                    "errors" => ValidatorService::buildErrorArray($errors)
                ]),
                422
            );
        }

        // encode the plain password
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $shopOwnerDTO->password
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getShopOwnerByEmail(string $email): ?ShopOwner
    {
        return $this->shopOwnerRepository->findOneBy(
            ["email" => $email]
        );
    }

}