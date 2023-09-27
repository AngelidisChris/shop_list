<?php

namespace App\Service;

use App\Dto\ShopDTO;
use App\Entity\Shop;
use App\Entity\ShopCategory;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopOwnerRepository;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Routing\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShopService
{
    private ShopRepository $shopRepository;
    private ShopOwnerService $shopOwnerService;
    private ShopCategoryRepository $shopCategoryRepository;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    /**
     * @param ShopRepository $shopRepository
     * @param ShopOwnerService $shopOwnerService
     * @param ShopCategoryRepository $shopCategoryRepository
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(ShopRepository $shopRepository, ShopOwnerService $shopOwnerService, ShopCategoryRepository $shopCategoryRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->shopRepository = $shopRepository;
        $this->shopOwnerService = $shopOwnerService;
        $this->shopCategoryRepository = $shopCategoryRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }


    public function createShop(ShopDTO $shopDTO): Shop
    {
        $shopCategory = $this->shopCategoryRepository->find(id: $shopDTO->shopCategory->id);
        if ($shopCategory === null){
            throw new InvalidArgumentException("Shop category with id {$shopDTO->shopCategory->id} does not exist!", 400);
        }

        $shop = new Shop();
        $shop->setName($shopDTO->name);
        $shop->setAddress($shopDTO->address);
        $shop->setCity($shopDTO->city);
        $shop->setDescription($shopDTO->description);
        $shop->setOpenHours($shopDTO->openHours);
        $shop->setShopCategory($shopCategory);
        $shop->setShopOwner($this->shopOwnerService->getCurrent());

        $errors = $this->validator->validate($shop);
        if ($errors->count() > 0){
            throw new InvalidArgumentException(
                json_encode([
                    "errors" => ValidatorService::buildErrorArray($errors)
                ]),
                422
            );
        }

        $this->entityManager->persist($shop);
        $this->entityManager->flush();

        return $shop;
    }
}