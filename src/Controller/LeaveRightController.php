<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\LeaveRight;
use App\Entity\LeaveType;
use App\Form\LeaveRightType;
use App\Service\DateConvertorService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/leaveRights')]
class LeaveRightController extends AbstractController
{
    private $dateConvertor;

    public function __construct(DateConvertorService $dateConvertor)
    {
        $this->dateConvertor = $dateConvertor;
    }

    #[Route('/add',name:'leaveRights.add')]
    public function addLeaveRight(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $leaveRight = new LeaveRight();
        $form = $this->createForm(LeaveRightType::class, $leaveRight);

        $newDateStart = $this->dateConvertor->convertStringToDateTime($request->request->get('startValidityDate'));
        $newDateEnd=$this->dateConvertor->convertStringToDateTime($request->request->get('endValidityDate'));

        $form->handleRequest($request);
        $form->submit($request->request->all(), false);

        $leaveRight->setStartValidityDate($newDateStart);
        $leaveRight->setEndValidityDate($newDateEnd);
        $leaveRight->setStatus(LeaveRight::ACTIVE);

        if ($request->request->get("employee")) {
            $employeeRepo = $doctrine->getRepository(Employe::class);
            $employee = $employeeRepo->find($request->request->get("employee"));
            if($employee) {
                $leaveRight->setEmploye($employee);
            }

        }
        if ($request->request->get("leaveType")) {
            $leaveTypeRepo = $doctrine->getRepository(LeaveType::class);

            $leaveType = $leaveTypeRepo->find($request->request->get("leave_type"));
            if($leaveType) {
                $leaveRight->setLeaveType($leaveType);
            }

        }

        if ($form->isSubmitted()) {
            $entityManager->persist($leaveRight);
            $entityManager->flush();
        }

        return $this->json($leaveRight, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/update/{id<\d+>}', name: 'leaveRights.update')]
    public function updateLeaveRights(LeaveRight $leaveRight = null, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        if ($leaveRight) {
            $leaveRightRepo = $doctrine->getRepository(LeaveRight::class);
            $entityManager = $doctrine->getManager();
            $form = $this->createForm(LeaveRightType::class, $leaveRight);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);


            if ($request->request->get("employee")) {
                $employeeRepo = $doctrine->getRepository(Employe::class);
                $employee = $employeeRepo->find($request->request->get("employee"));
                if($employee) {
                    $leaveRight->setEmploye($employee);
                }
            }
            if ($request->request->get("leave_type")) {
                $leaveTypeRepo = $doctrine->getRepository(LeaveType::class);
                $leaveType = $leaveTypeRepo->find($request->request->get("leave_type"));
                if($leaveType) {
                    $leaveRight->setLeaveType($leaveType);
                }

            }
            if ($form->isSubmitted()) {
                $entityManager->persist($leaveRight);
                $entityManager->flush();
            }

            return $this->json($leaveRight, Response::HTTP_OK, [], [
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

    #[Route('/delete/{id<\d+>}', name: 'leaveRights.delete')]
    public function deleteLeaveRight(ManagerRegistry $doctrine, LeaveRight $leaveRight = null): Response
    {
        if ($leaveRight) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($leaveRight);
            $entityManager->flush();
            return $this->json($leaveRight, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);

        } else {
            return new JsonResponse([
                "message" => "error",
                "data" => "No such leave Right"], 401);
        }
    }

    #[Route('/', name: 'leaveRights.list')]
    public function getLeaveRights(Request $request, ManagerRegistry $doctrine): Response
    {
        $leaveRightRepo = $doctrine->getRepository(LeaveRight::class);
        $leaveRights = $leaveRightRepo->findAll();
        return $this->json($leaveRights, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id<\d+>}', name: 'leaveRights.get')]
    public function getLeaveRight(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $leaveRightRepo = $doctrine->getRepository(LeaveRight::class);
        $leaveRight = $leaveRightRepo->find($id);
        if ($leaveRight) {
            return $this->json($leaveRight, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse(["message" => "error not found"], 401);
        }
    }
}
