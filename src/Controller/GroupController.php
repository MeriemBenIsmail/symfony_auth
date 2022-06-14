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

#[Route('/groups')]
class GroupController extends AbstractController
{
    #[Route('/add', name: 'groups.add')]
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

}
