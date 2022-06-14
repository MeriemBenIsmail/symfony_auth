<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

// only superadmin can add/get/update/delete admin
#[Route('/admins')]
class AdminController extends AbstractController
{
    #[Route('/add', name: 'admins.add')]
    public function addAdmin(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $admin = new User();
        $admin->setEmail($request->request->get('email'));
        $admin->setSuper($request->request->get('super'));
        // hashing the password
        $hashedPassword = $passwordHasher->hashPassword(
            $admin,
            $request->request->get('password')
        );
        $groupRepo = $doctrine->getRepository(Group::class);
        $group = $groupRepo->find($request->request->get("group"));
        $group->addUser($admin);
        $admin->setPassword($hashedPassword);
        $entityManager->persist($group);
        $entityManager->persist($admin);
        $entityManager->flush();
        return $this->json($group, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

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





}





