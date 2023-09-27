<?php

namespace App\Controller;

use App\Dto\ShopOwnerDTO;
use App\Service\ShopOwnerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidArgumentException;

class RegistrationController extends AbstractController
{
    private ShopOwnerService $shopOwnerService;

    /**
     * @param ShopOwnerService $shopOwnerService
     */
    public function __construct(ShopOwnerService $shopOwnerService)
    {
        $this->shopOwnerService = $shopOwnerService;
    }


    #[Route('/register', name: 'user_register', methods: ["POST"])]
    public function register(#[MapRequestPayload] ShopOwnerDTO $shopOwnerDTO): JsonResponse
    {

        try {
            $shopOwner = $this->shopOwnerService->create($shopOwnerDTO);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(json_decode($e->getMessage(), true), $e->getCode());
        }

        return new JsonResponse(["id" => $shopOwner->getId()], 201);
    }
}
