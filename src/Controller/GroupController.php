<?php

namespace App\Controller;


use App\Entity\Admin;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserRole;
use App\Form\GroupType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/api/groups')]
class GroupController extends AbstractController
{

    #[Route('/add', name: 'group.add')]
    public function addGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $userRepo = $doctrine->getRepository(User::class);
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        $group = new Group();
        $group->setName($request->request->get('name'));
        if ($request->request->get("users")) {
            $usersArray = explode(",", $request->request->get("users"));
            foreach ($usersArray as $user) {
                $usr = $userRepo->find($user);
                $group->addUser($usr);
            }
        }
        if ($request->request->get("groupRoles")) {
            $groupRolesArray = explode(",", $request->request->get("groupRoles"));
            foreach ($groupRolesArray as $groupRole) {
                $groupRol = $userRoleRepo->find($groupRole);
                $group->addUser($groupRol);
            }
        }
        $entityManager->persist($group);
        $entityManager->flush();
        return $this->json([
            'success' => $group, 200
        ]);
    }

    #[Route('/addTo', name: 'group.addAdmin')]
    public function addToGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $adminRepo = $doctrine->getRepository(Admin::class);
        $groupRepo = $doctrine->getRepository(Group::class);
        $admin = $adminRepo->find($request->request->get("admin"));
        $group = $groupRepo->find($request->request->get("group"));
        $group->addAdmin($admin);
        $entityManager->persist($group);
        $entityManager->flush();
        return $this->json($group, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/removeFrom', name: 'group.removeAdmin')]
    public function removeFromGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $adminRepo = $doctrine->getRepository(Admin::class);
        $groupRepo = $doctrine->getRepository(Group::class);
        $admin = $adminRepo->find($request->request->get("admin"));
        $group = $groupRepo->find($request->request->get("group"));
        $group->removeUser($admin);
        $entityManager->persist($group);
        $entityManager->flush();
        return $this->json($group, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/updateGroup/{id<\d+>}', name: 'group.update')]
    public function updateGroup(Group $group = null, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        if ($group) {
            $userRepo = $doctrine->getRepository(User::class);
            $userRoleRepo = $doctrine->getRepository(UserRole::class);
            $entityManager = $doctrine->getManager();
            $form = $this->createForm(GroupType::class, $group);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);
            if ($request->request->get("users")) {
                $usersArray = explode(",", $request->request->get("users"));
                $group->emptyUsers();
                foreach ($usersArray as $user) {
                    $usr = $userRepo->find($user);
                    $group->addUser($usr);
                }
            }
            if ($request->request->get("groupRoles")) {
                $groupRolesArray = explode(",", $request->request->get("groupRoles"));
                $group->emptyGroupRoles();
                foreach ($groupRolesArray as $groupRole) {
                    $groupRol = $userRoleRepo->find($groupRole);
                    $group->addUser($groupRol);
                }
            }
            if ($form->isSubmitted()) {
                $entityManager->persist($group);
                $entityManager->flush();
            }

            return $this->json($group, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return $this->json([
                "message" => "error no group with this id", 200
            ]);

        }
    }

    #[Route('/', name: 'group.list')]
    public function getAll(ManagerRegistry $doctrine): JsonResponse
    {
        $groupRepo = $doctrine->getRepository(Group::class);
        $groups = $groupRepo->findAll();
        return $this->json($groups, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id<\d+>}', name: 'group.get')]
    public function getGroup(ManagerRegistry $doctrine, Group $group = null): JsonResponse
    {
        if ($group) {
            return $this->json($group, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse(["message" => "error"]);
        }
    }
}
