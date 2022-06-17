<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Entity\Employe;
use App\Form\DemandType;
use App\Service\DateConvertorService;
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
    private $dateConvertor;


    public function __construct(DateConvertorService $dateConvertor)
    {
        $this->dateConvertor = $dateConvertor;
    }

    #[Route('/add', name: 'demands.add')]
    public function addDemand(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $demand = new Demand();
        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);
        //Demand type
        $demand->setStatus(Demand::WAITING);
        if ($demand->getType()) {
            if ($demand->getType() != "LEAVE" && $demand->getType() != "TELEWORK" && $demand->getType() != "AUTHORIZATION") {
                return new JsonResponse(["status" => "error", "message" => "verify your demand type"]);
            }
        }
        //demand leave type
        if ($demand->getType() === "LEAVE") {
            if (!$demand->getLeaveType()) {
                return new JsonResponse(["status" => "error", "message" => "please provide leave type"]);
            }
        } else {
            $demand->setLeaveType(null);
        }
        //dates

        $demand->setStartDate($this->dateConvertor->convertStringToDateTime($request->request->get('startDate')));
        $demand->setEndDate($this->dateConvertor->convertStringToDateTime($request->request->get('endDate')));

        //persist
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
        return new JsonResponse([
            "message" => "error",
            "data" => "No such demand"], 200);
    }

    #[Route('/confirm/{id<\d+>}', name: 'demands.confirm')]
    public function confirmDemand(Demand $demand = null, ManagerRegistry $doctrine): Response
    {
        if ($demand) {
            $entityManager = $doctrine->getManager();
            $demand->setStatus(Demand::CONFIRMED);
            $entityManager->persist($demand);
            $entityManager->flush();

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

    #[Route('/decline/{id<\d+>}', name: 'demands.delete')]
    public function declineDemand(Demand $demand = null, ManagerRegistry $doctrine): Response
    {
        if ($demand) {
            $entityManager = $doctrine->getManager();
            $demand->setStatus(Demand::DECLINED);
            $entityManager->persist($demand);
            $entityManager->flush();

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

    #[Route('/cancel/{id<\d+>}', name: 'demands.cancel')]
    public function cancelDemand(Demand $demand = null, ManagerRegistry $doctrine): Response
    {
        if ($demand) {
            if ($demand->getStatus() == "WAITING") {
                $entityManager = $doctrine->getManager();
                $entityManager->remove($demand);
                $entityManager->flush();

                return $this->json($demand, Response::HTTP_OK, [], [
                    ObjectNormalizer::SKIP_NULL_VALUES => true,
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId();
                    }
                ]);
            }
            return new JsonResponse([
                "status" => "error",
                "message" => "can't cancel this demand"], 200);
        }
        return new JsonResponse([
            "status" => "error",
            "message" => "No such demand"], 200);
    }

    #[Route('/delete/{id<\d+>}', name: 'demands.delete')]
    public function deleteDemand(Demand $demand = null, ManagerRegistry $doctrine): Response
    {
        if ($demand) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($demand);
            $entityManager->flush();

            return $this->json($demand, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return new JsonResponse([
            "status" => "error",
            "message" => "No such demand"], 200);
    }

    #[Route('/leave', name: 'demands.list.leave')]
    public function getLeaveDemands(Request $request, ManagerRegistry $doctrine): Response
    {
        $demandRepo = $doctrine->getRepository(Demand::class);
        $demands = $demandRepo->findBy(["type" => "LEAVE"]);
        return $this->json($demands, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/authorization', name: 'demands.list.authorization')]
    public function getAuthorizationDemands(Request $request, ManagerRegistry $doctrine): Response
    {
        $demandRepo = $doctrine->getRepository(Demand::class);
        $demands = $demandRepo->findBy(["type" => "AUTHORIZATION"]);
        return $this->json($demands, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/telework', name: 'demands.list.telework')]
    public function getTeleworkDemands(ManagerRegistry $doctrine): Response
    {
        $demandRepo = $doctrine->getRepository(Demand::class);
        $demands = $demandRepo->findBy(["type" => "TELEWORK"]);
        return $this->json($demands, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/employee/{id<\d+>}', name: 'demands.list.employee')]
    public function getEmployeeDemands(Employe $employe = null, ManagerRegistry $doctrine): Response
    {
        if ($employe) {
            $demandRepo = $doctrine->getRepository(Demand::class);
            $demands = $demandRepo->findBy(["employe" => $employe]);
            return $this->json($demands, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return new JsonResponse([
            "status" => "error",
            "message" => "No such employee"], 200);
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
