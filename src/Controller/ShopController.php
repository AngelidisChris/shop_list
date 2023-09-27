<?php

namespace App\Controller;

use App\Dto\ShopDTO;
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

    /**
     * @param ShopService $shopService
     */
    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
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
}
