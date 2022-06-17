<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/demands')]
class DemandController extends AbstractController
{
    #[Route('/add', name: 'demands.add')]
    public function addDemand(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $demand = new Demand();
        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted()) {
            $entityManager->persist($demand);
            $entityManager->flush();
        }
        return $this->json($demand, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }
    #[Route('/update/{id<\d+>}', name: 'demands.update')]
    public function updateDemand(Demand $demand = null, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($demand) {
            $entityManager = $doctrine->getManager();
            $form = $this->createForm(PosteType::class, $demand);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);
            if ($form->isSubmitted()) {
                $entityManager->persist($demand);
                $entityManager->flush();
            }
            return $this->json($demand, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return new JsonResponse([
            "message" => "error",
            "data" => "No such demand"], 200);
    }

    #[Route('/{id<\d+>}', name: 'demands.get')]
    public function getDemand(int $id, ManagerRegistry $doctrine): Response
    {
        $demandRepo = $doctrine->getRepository(Demand::class);
        $demand = $demandRepo->find($id);
        if ($demand) {
            return $this->json($demand, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse(["message" => "error"], 200);
        }
    }

    #[Route('/', name: 'demands.list')]
    public function getAllDemands(Request $request, ManagerRegistry $doctrine): Response
    {
        $demandRepo = $doctrine->getRepository(Demand::class);
        $demands = $demandRepo->findAll();
        return $this->json($demands, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }


}
