<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AuthorizeController extends AbstractController
{
    // uses the authservice::isLoggedIn to check if logged in or not
    #[Route('authorize/hi', name: 'user.hi')]
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

    // uses the authservice to check if employe ( normal user ) , admin or super admin
    #[Route('/api/authorize/admin', name: 'admin.test')]
    public function sayAdmin(Request $request,AuthService $service): JsonResponse{

        if($service->isLoggedIn($request)) {
            $user = $this->getUser();
            if($service->isAdmin($user)){
                if($service->isSuperAdmin($user))
                    return $this->json([
                        'success' => 'hi super admin',200
                    ]);
                else return $this->json([
                    'error' => 'logged in admin but not super admin',401
                ]);
            }
            else return $this->json([
                'error' => 'logged in but not admin',401
            ]);
        }
        return $this->json([
            'error' => 'not logged in',401
        ]);

    }
}
