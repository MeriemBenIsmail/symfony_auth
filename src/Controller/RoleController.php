<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use App\Form\UserRoleType;
use App\Service\AuthService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


#[Route('/roles')]
class RoleController extends AbstractController
{
    #[Route('/add', name: 'roles.add')]
    public function addRole(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        $userRole = new UserRole();
        $form = $this->createForm(UserRoleType::class, $userRole);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);;
        if ($request->request->get("childRoles")) {
            $roleArray = explode(",", $request->request->get("childRoles"));
            foreach ($roleArray as $role) {
                $rol = $userRoleRepo->find($role);
                $userRole->addChildRole($rol);
            }
        }
        if ($form->isSubmitted()) {
            $entityManager->persist($userRole);
            $entityManager->flush();
        }
        return $this->json($userRole, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/update/{id<\d+>}', name: 'roles.update')]
    public function updateRole(UserRole $userRole = null, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        if ($userRole) {
            $form = $this->createForm(UserRoleType::class, $userRole);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);
            if ($request->request->get("childRoles") !== null) {
                $roleArray = explode(",", $request->request->get("childRoles"));
                $userRole->emptyChildRoles();
                foreach ($roleArray as $role) {
                    if ($role) {
                        $rol = $userRoleRepo->find($role);
                        $userRole->addChildRole($rol);
                    }
                }
            }
            if ($form->isSubmitted()) {
                $entityManager->persist($userRole);
                $entityManager->flush();
            }
            return $this->json($userRole, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return new JsonResponse([
            "message" => "error",
            "data" => "No such userRole"], 200);
    }

    #[Route('/delete/{id<\d+>}', name: 'roles.delete')]
    public function deleteRole(UserRole $userRole, ManagerRegistry $doctrine): Response
    {
        if ($userRole) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($userRole);
            $entityManager->flush();
            return $this->json($userRole, Response::HTTP_OK, [], [
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

    #[Route('/', name: 'roles.list')]
    public function getRoles(Request $request, ManagerRegistry $doctrine): Response
    {
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        $userRoles = $userRoleRepo->findAll();
        return $this->json($userRoles, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id<\d+>}', name: 'roles.get')]
    public function getRole(int $id, ManagerRegistry $doctrine): Response
    {
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        $userRole = $userRoleRepo->find($id);
        if ($userRole) {
            return $this->json($userRole, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse(["message" => "error"], 200);
        }
    }

    #[Route('/{name}', name: 'roles.getByName')]
    public function getRoleByName(string $name, ManagerRegistry $doctrine): Response
    {
        $userRoleRepo = $doctrine->getRepository(UserRole::class);
        $userRole = $userRoleRepo->findBy(["name" => $name]);
        if ($userRole) {
            return $this->json($userRole, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse(["message" => "error"], 200);
        }

    }

}
