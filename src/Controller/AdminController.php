<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserRole;
use App\Form\AdminType;
use App\Service\AuthService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

// only superadmin can access the routes of this controller
#[Route('/api/admins')]
class AdminController extends AbstractController
{
    #[Route('/add', name: 'admin.add')]
    public function addAdmin(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $admin = new User();
        $form = $this->createForm(AdminType::class, $admin);
        $hashedPassword = $passwordHasher->hashPassword(
            $admin,
            $request->request->get('password')
        );


        if ($request->request->get("roles")) {
            $roles = explode(",", $request->request->get("roles"));
            $admin->setRoles($roles);
        }
        if ($request->request->get("groups")) {
            $groupRepo = $doctrine->getRepository(Group::class);
            $groupsArray = explode(",", $request->request->get("groups"));
            foreach ($groupsArray as $group) {
                $grp = $groupRepo->find($group);
                $admin->addGroup($grp);
            }
        }

        $form->handleRequest($request);
        // hashing the password

        $form->submit($request->request->all(), false);
        $newAdmin = $form->getData();
        $newAdmin->setPassword($hashedPassword);

        if ($form->isSubmitted()) {
            $entityManager->persist($newAdmin);
            $entityManager->flush();
        }

        return $this->json($newAdmin, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/addTest', name: 'admins.addTest')]
    public function addAdminTest(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher, AuthService $authService): JsonResponse
    {
        $user = $this->getUser();

        if ($authService->hasRole($user, 'create_admin')) {

            return $this->json("hii");

        }
        return $this->json(['error' => "you don't have access to this resource"], 401);


    }

    #[Route('/update/{id}', name: 'admins.update')]
    public function updateAdmin(User $user = null, Request $request, ManagerRegistry $doctrine): Response
    {

        if ($user) {
            $entityManager = $doctrine->getManager();

            $form = $this->createForm(AdminType::class, $user);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);

            if ($request->request->get("roles") !== null) {

                $roles = explode(",", $request->request->get("roles"));
                if ($request->request->get("roles") == "") {
                    $roles = [];
                }
                $user->setRoles($roles);
            }
            if ($request->request->get("groups") !== null) {
                $user->emptyGroups();
                $groupRepo = $doctrine->getRepository(Group::class);
                $groupsArray = explode(",", $request->request->get("groups"));
                foreach ($groupsArray as $group) {
                    if ($group) {
                        $grp = $groupRepo->find($group);
                        $user->addGroup($grp);
                    }
                }
            }
            if ($form->isSubmitted()) {
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->json($user, Response::HTTP_OK, [], [
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return $this->json([
            "message" => "error",
            "data" => "No such user"], 200
        );


    }

    #[Route('/delete/{id<\d+>}', name: 'admins.delete')]
    public function deleteAdmin(User $admin, ManagerRegistry $doctrine): Response
    {
        if ($admin) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($admin);
            $entityManager->flush();
            return $this->json($admin, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse([
                    "message" => "error"]
            );
        }
    }

    #[Route('/{id<\d+>}', name: 'admins.detail')]
    public function detail(User $admin = null): JsonResponse
    {
        return $this->json($admin, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{email}', name: 'admins.email')]
    public function getByEmail(ManagerRegistry $doctrine, $email): JsonResponse
    {

        $repo = $doctrine->getRepository(User::class);
        $admin = $repo->findBy(['email' => $email]);
        return $this->json($admin, Response::HTTP_OK, [], [
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
        return $this->json($admins, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }


}