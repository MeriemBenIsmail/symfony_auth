<?php

namespace App\Controller;


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

#[Route('/groups')]
class GroupController extends AbstractController
{
    private $json;

    #[Route('/add', name: 'group.add')]
    public function addGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $userRepo = $doctrine->getRepository(User::class);
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);;
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
                $group->addGroupRole($groupRol);
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
    }

    #[Route('/addTo', name: 'group.addUser')]
    public function addToGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $userRepo = $doctrine->getRepository(User::class);
        $groupRepo = $doctrine->getRepository(Group::class);
        $user = $userRepo->find($request->request->get("user"));
        $group = $groupRepo->find($request->request->get("group"));
        $group->addUser($user);
        $entityManager->persist($group);
        $entityManager->flush();
        return $this->json($group, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/removeFrom', name: 'group.removeUser')]
    public function removeFromGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $userRepo = $doctrine->getRepository(User::class);
        $groupRepo = $doctrine->getRepository(Group::class);
        $user = $userRepo->find($request->request->get("user"));
        $group = $groupRepo->find($request->request->get("group"));
        $group->removeUser($user);
        $entityManager->persist($group);
        $entityManager->flush();
        return $this->json($group, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/update/{id<\d+>}', name: 'group.update')]
    public function updateGroup(Group $group = null, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        if ($group) {
            $userRepo = $doctrine->getRepository(User::class);
            $userRoleRepo = $doctrine->getRepository(UserRole::class);
            $entityManager = $doctrine->getManager();
            $form = $this->createForm(GroupType::class, $group);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);
            if ($request->request->get("users") !== null) {
                $usersArray = explode(",", $request->request->get("users"));
                $group->emptyUsers();
                foreach ($usersArray as $user) {
                    if ($user) {
                        $usr = $userRepo->find($user);
                        $group->addUser($usr);
                    }
                }
            }
            if ($request->request->get("groupRoles") !== null) {
                $groupRolesArray = explode(",", $request->request->get("groupRoles"));
                $group->emptyGroupRoles();
                foreach ($groupRolesArray as $groupRole) {
                    if ($groupRole) {
                        $groupRol = $userRoleRepo->find($groupRole);
                        $group->addGroupRole($groupRol);
                    }
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

    #[Route('/delete/{id<\d+>}', name: 'groups.delete')]
    public function deleteGroup(Group $group, ManagerRegistry $doctrine): Response
    {
        $this->json = $this->json($group, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
        if ($group) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($group);
            $entityManager->flush();
            return $this->json;
        } else {
            return new JsonResponse([
                    "message" => "error"]
            );
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
