<?php

namespace App\Controller;

use App\Dto\ShopDTO;
use App\Service\JsonSerializerService;
use App\Service\ShopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidArgumentException;

#[Route('/shops')]
class ShopController extends AbstractController
{
    private ShopService $shopService;
    private JsonSerializerService $jsonSerializerService;

    /**
     * @param ShopService $shopService
     * @param JsonSerializerService $jsonSerializerService
     */
    public function __construct(ShopService $shopService, JsonSerializerService $jsonSerializerService)
    {
        $this->shopService = $shopService;
        $this->jsonSerializerService = $jsonSerializerService;
    }

    #[Route('', name: 'create_shop', methods: ["POST"])]
    public function createShop(#[MapRequestPayload] ShopDTO $shopDTO): Response
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

    #[Route('/{id}', name: 'show_shop', requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showShop(int $id): Response
    {
        try{
            $shop = $this->shopService->showShopById($id);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(["errors" => [$e->getMessage()]], $e->getCode());
        }

        return new JsonResponse($this->jsonSerializerService->serialize($shop, ["show_shop"]), 200);
    }

    #[Route('/{id}', name: 'update_shop', requirements: ["id" => "\d+"], methods: ["PUT"])]
    public function updateShop(#[MapRequestPayload] ShopDTO $shopDTO, int $id): Response
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
    public function deleteShop(int $id): Response
    {
        try{
            $this->shopService->deleteShop($id);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(["errors" => [$e->getMessage()]], $e->getCode());
        }

        return new JsonResponse([], 204);
    }
}
