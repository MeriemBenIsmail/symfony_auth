<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/add', name: 'admin.add')]
    public function addAdmin(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $admin = new User();
        $admin->setEmail($request->request->get('email'));
        $admin->setSuper(
            $request->request->get('super'));
        // hashing the password
        $hashedPassword = $passwordHasher->hashPassword(
            $admin,
            $request->request->get('password')
        );

        $admin->setPassword($hashedPassword);
        //$admin->setPassword($request->request->get('password'));
        $entityManager->persist($admin);
        $entityManager->flush();
        return $this->json([
            'success' => $admin,200
        ]);
    }


}
