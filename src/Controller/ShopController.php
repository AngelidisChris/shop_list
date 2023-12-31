<?php

namespace App\Controller;

use App\Dto\ShopDTO;
use App\Service\SerializerService;
use App\Service\ShopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidArgumentException;

#[Route('/shops')]
class ShopController extends AbstractController
{
    private ShopService $shopService;
    private SerializerService $jsonSerializerService;

    /**
     * @param ShopService $shopService
     * @param SerializerService $jsonSerializerService
     */
    public function __construct( ShopService $shopService, SerializerService $jsonSerializerService)
    {
        $this->shopService = $shopService;
        $this->jsonSerializerService = $jsonSerializerService;
    }

    #[Route('', name: 'index_shop', methods: ["GET"])]
    public function index(
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT)] ?array $shopOwnerIds,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT)] ?array $shopCategoryIds,
        #[MapQueryParameter] ?string $city,
        #[MapQueryParameter] ?int $range,
        #[MapQueryParameter] int $page = 1,
    ): JsonResponse
    {
        $pagination = $this->shopService->indexShops($page, $range, $shopOwnerIds, $shopCategoryIds, $city);

        $filteredShops = [
            'data' => $pagination->getItems(),
            'meta' => $pagination->getPaginationData()
        ];

        return new JsonResponse($this->jsonSerializerService->serialize($filteredShops, ["show_shop"]));
    }

    #[Route('/{id}', name: 'show_shop', requirements: ["id" => "\d+"], methods: ["GET"])]
    public function show(int $id): Response
    {
        try{
            $shop = $this->shopService->showShopById($id);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(["errors" => [$e->getMessage()]], $e->getCode());
        }

        return new JsonResponse($this->jsonSerializerService->serialize($shop, ["show_shop"]), 200);
    }

    #[Route('', name: 'create_shop', methods: ["POST"])]
    public function create(#[MapRequestPayload] ShopDTO $shopDTO): Response
    {
        try{
            $shop = $this->shopService->createShop($shopDTO);
        } catch (InvalidArgumentException $e) {
            if ($errors = json_decode($e->getMessage(), true)){
                return new JsonResponse($errors, $e->getCode());
            }
            return new JsonResponse(["errors" => [$e->getMessage()]], $e->getCode());
        }

        return new JsonResponse(["id" => $shop->getId()], 201);
    }

    #[Route('/{id}', name: 'update_shop', requirements: ["id" => "\d+"], methods: ["PUT"])]
    public function update(#[MapRequestPayload] ShopDTO $shopDTO, int $id): Response
    {
        try{
            $shop = $this->shopService->updateShop($id, $shopDTO);
        } catch (InvalidArgumentException $e) {
            if ($errors = json_decode($e->getMessage(), true)){
                return new JsonResponse($errors, $e->getCode());
            }
            return new JsonResponse(["errors" => [$e->getMessage()]], $e->getCode());
        }

        return new JsonResponse($this->jsonSerializerService->serialize($shop, ["update_shop"]), 200);
    }

    #[Route('/{id}', name: 'delete_shop', requirements: ["id" => "\d+"], methods: ["DELETE"])]
    public function delete(int $id): Response
    {
        try{
            $this->shopService->deleteShop($id);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(["errors" => [$e->getMessage()]], $e->getCode());
        }

        return new JsonResponse([], 204);
    }
}
