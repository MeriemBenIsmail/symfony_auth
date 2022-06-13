<?php

namespace App\Controller;

use App\Entity\Blacklisted;
use App\Service\AuthService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/logout', name: 'user.logout')]
    public function logout(Request $request,ManagerRegistry $doctrine): JsonResponse

    {
        //$user = $this->getUser();
        $tokenX = $request->headers->get('Authorization');
        preg_match('/Bearer\s(\S+)/', $tokenX, $matches);
        // adding token to blacklisted in the db
        $entityManager = $doctrine->getManager();
        $token = new Blacklisted();
        $token->setToken($matches[1]);
        $entityManager->persist($token);
        $entityManager->flush();

        return $this->json([
            'logout' => 'success',200
        ]);
    }

}
