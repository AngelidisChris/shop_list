<?php

namespace App\Service;

use App\Dto\ShopDTO;
use App\Entity\Shop;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShopService
{
    private ShopRepository $shopRepository;
    private ShopOwnerService $shopOwnerService;
    private ShopCategoryRepository $shopCategoryRepository;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private PaginatorInterface $paginator;

    /**
     * @param ShopRepository $shopRepository
     * @param ShopOwnerService $shopOwnerService
     * @param ShopCategoryRepository $shopCategoryRepository
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param PaginatorInterface $paginator
     */
    public function __construct( ShopRepository         $shopRepository,
                                 ShopOwnerService       $shopOwnerService,
                                 ShopCategoryRepository $shopCategoryRepository,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface     $validator,
                                 PaginatorInterface     $paginator)
    {
        $this->shopRepository = $shopRepository;
        $this->shopOwnerService = $shopOwnerService;
        $this->shopCategoryRepository = $shopCategoryRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }


    /**
     * @param int $page
     * @param int|null $range
     * @param array|null $shopOwnerIds
     * @param array|null $shopCategoryIds
     * @param string|null $city
     * @return PaginationInterface
     */
    public function indexShops(int $page, int $range = null, array $shopOwnerIds = null, array $shopCategoryIds = null, ?string $city = null): PaginationInterface
    {
        $filteredShops = $this->shopRepository->findByFilters($shopOwnerIds, $shopCategoryIds, $city);
        return $this->paginator->paginate($filteredShops, $page, $range);
    }

    public function createShop(ShopDTO $shopDTO): Shop
    {
        $shopCategory = $this->shopCategoryRepository->find(id: $shopDTO->shopCategory->id);
        if ($shopCategory === null){
            throw new InvalidArgumentException("Shop category with id {$shopDTO->shopCategory->id} does not exist!", 400);
        }

        $shop = new Shop($shopDTO, $this->shopOwnerService->getCurrent(), $shopCategory);

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

    public function showShopById(int $id): Shop
    {
        $shop = $this->shopRepository->find(id: $id);
        if ($shop === null){
            throw new InvalidArgumentException("Shop with id {$id} does not exist!", 400);
        }

        return $shop;
    }

    public function updateShop(int $id, ShopDTO $shopDTO): Shop
    {
        $shop = $this->shopRepository->findOneBy([
            "id" => $id,
            "shopOwner" => $this->shopOwnerService->getCurrent()
        ]);
        if ($shop === null){
            throw new InvalidArgumentException("Shop with id {$id} does not exist!", 400);
        }

        $updatedShopCategory = $shop->getShopCategory();
        if ($shopDTO->shopCategory->id !== $shop->getShopCategory()->getId()){
            $updatedShopCategory = $this->shopCategoryRepository->find(id: $shopDTO->shopCategory->id);
            if ($updatedShopCategory === null){
                throw new InvalidArgumentException("Shop category with id {$shopDTO->shopCategory->id} does not exist!", 400);
            }
        }

        $errors = $this->validator->validate($shop);
        if ($errors->count() > 0){
            throw new InvalidArgumentException(
                json_encode([
                    "errors" => ValidatorService::buildErrorArray($errors)
                ]),
                422
            );
        }

        $shop->updateShop($shopDTO, $updatedShopCategory);

        return $shop;
    }

    public function deleteShop(int $id):void
    {
        $shop = $this->shopRepository->findOneBy([
            "id" => $id,
            "shopOwner" => $this->shopOwnerService->getCurrent()
        ]);
        if ($shop === null){
            throw new InvalidArgumentException("Shop with id {$id} does not exist!", 400);
        }

        $this->entityManager->remove($shop);
        $this->entityManager->flush();
    }
}