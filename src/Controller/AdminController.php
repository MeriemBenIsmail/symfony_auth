<?php

namespace App\Controller;

use App\Entity\Admin;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/addAdmin', name: 'admin.add')]
    public function addAdmin(ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $admin = new Admin();
        $admin->setEmail($request->request->get('email'));
        $admin->setPassword($request->request->get('password'));
        $admin->setIsSuper($request->request->get('isSuper'));
        $entityManager->persist($admin);
        $entityManager->flush();
        return $this->json([
            'error' => $admin,200
        ]);
    }
}
