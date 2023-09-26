<?php

namespace App\Controller;

use App\Entity\ShopOwner;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'user_register', methods: ["POST"])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $user = new ShopOwner();
        $user->setEmail($request->getPayload()->get("email"));
        $user->setRawPassword($request->getPayload()->get("password"));
        $user->setName($request->getPayload()->get("name"));
        $user->setRoles(["ROLE_SHOP_OWNER"]);

        $errors = $validator->validate($user);

        if ($errors->count() > 0){
            return new JsonResponse([
                "errors" => ValidatorService::buildErrorArray($errors)
            ],422);
        }

        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $request->getPayload()->get("password")
            )
        );



        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(["id" => $user->getId()], 201);

    }
}
