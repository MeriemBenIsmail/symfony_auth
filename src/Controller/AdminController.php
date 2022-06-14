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

// only superadmin can add/get/update/delete admin
#[Route('/admins')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'admins.all')]
    public function getAdmins(ManagerRegistry $doctrine)
    {
        $repo = $doctrine->getRepository(User::class);
        $admins = $repo->findBy(['super' => 0]);
        return $this->json([
            'admins' => $admins,200
        ]);
    }
    #[Route('/{id<\d+>}', name: 'admins.detail')]
    public function detail(User $admin = null): JsonResponse
    {
        return $this->json([
            'admin' => $admin,200
        ]);
    }

    #[Route('/{email}', name: 'admins.email')]
    public function getByEmail(ManagerRegistry $doctrine,$email): JsonResponse
    {

        $repo = $doctrine->getRepository(User::class);
        $admin= $repo->findBy(['email' => $email]);
        return $this->json([
            'admin' => $admin,200
        ]);
    }


    #[Route('/add', name: 'admins.add')]
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
