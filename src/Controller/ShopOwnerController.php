<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("shopOwners")]
class ShopOwnerController extends AbstractController
{
    #[Route('/', name: 'getShopOwners', methods: ["GET"])]
    public function index(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
