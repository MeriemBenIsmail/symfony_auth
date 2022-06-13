<?php

namespace App\Controller;


use App\Entity\Admin;
use App\Entity\Group;
use App\Service\ResponseService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
class GroupController extends AbstractController
{
    #[Route('/addGroup', name: 'group.add')]
    public function addGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $group = new Group();
        $group->setName($request->request->get('name'));
        $entityManager->persist($group);
        $entityManager->flush();
        return $this->json([
            'success' => $group,200
        ]);
    }
    #[Route('/addToGroup', name: 'group.addAdmin')]
    public function addToGroup(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $adminRepo=$doctrine->getRepository(Admin::class);
        $groupRepo=$doctrine->getRepository(Group::class);
        $admin=$adminRepo->find($request->request->get("admin"));
        $group=$groupRepo->find($request->request->get("group"));
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
}
