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

class UserController extends AbstractController
{
    #[Route('/api/logout', name: 'user.logout')]
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

    #[Route('/api/loggedIn', name: 'user.logged')]
    public function isLoggedIn(Request $request,ManagerRegistry $doctrine): JsonResponse

    {
        $tokenX = $request->headers->get('Authorization');
        preg_match('/Bearer\s(\S+)/', $tokenX, $matches);

        $repo = $doctrine->getRepository(Blacklisted::class);

        $token = $repo->findOneBy(['token' => $matches[1]]);

        if($token) {
            return $this->json([
                'error' => 'you are not logged in',401
            ]);
        }
        return $this->json([
            'success' => 'you are logged in',200
        ]);
    }
    #[Route('/api/hi', name: 'user.hi')]
    public function sayHi(Request $request,AuthService $service): JsonResponse{

        if($service->isLoggedIn($request)) {
            return $this->json([
                'success' => 'hii',200
            ]);
        }
        return $this->json([
            'error' => 'not logged in',401
        ]);

    }
    #[Route('/api/admin', name: 'admin.test')]
    public function sayAdmin(Request $request,AuthService $service): JsonResponse{

        if($service->isLoggedIn($request)) {
            $user = $this->getUser();
            if($service->isAdmin($user))
                return $this->json([
                    'success' => 'hi admin',200
                ]);
            else return $this->json([
                'error' => 'logged in but not admin',401
            ]);
        }
        return $this->json([
            'error' => 'not logged in',401
        ]);

    }




}
