<?php

namespace App\Controller;

use App\Entity\LeaveType;
use App\Form\LeaveTypeF;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/leave_types')]
class LeaveTypeController extends AbstractController
{
    #[Route('/add', name: 'leave_types.add')]
    public function addLeaveType(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $leaveType = new LeaveType();
        $form = $this->createForm(LeaveTypeF::class, $leaveType);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted()) {
            $entityManager->persist($leaveType);
            $entityManager->flush();
        }
        return $this->json($leaveType);
    }

    #[Route('/delete/{id<\d+>}', name: 'leave_types.delete')]
    public function deleteLeaveType(ManagerRegistry $doctrine, LeaveType $leaveType = null): Response
    {
        if ($leaveType) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($leaveType);
            $entityManager->flush();
            return $this->json($leaveType);
        } else {
            return new JsonResponse([
                "message" => "error",
                "data" => "No such leave type"], 200);
        }
    }

    #[Route('/update/{id<\d+>}', name: 'leave_types.update')]
    public function updateLeaveType(LeaveType $leaveType = null, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($leaveType) {
            $entityManager = $doctrine->getManager();
            $form = $this->createForm(LeaveTypeF::class, $leaveType);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);
            if ($form->isSubmitted()) {
                $entityManager->persist($leaveType);
                $entityManager->flush();
            }
            return $this->json($leaveType);
        }
        return new JsonResponse([
            "message" => "error",
            "data" => "No such leave "], 200);
    }

    #[Route('/', name: 'leave_types.list')]
    public function getLeaveTypes(Request $request, ManagerRegistry $doctrine): Response
    {
        $leaveTypeRepo = $doctrine->getRepository(LeaveType::class);
        $leaveTypes = $leaveTypeRepo->findAll();
        return $this->json($leaveTypes);
    }

    #[Route('/{id<\d+>}', name: 'leave_types.get')]
    public function getLeaveType(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $leaveTypeRepo = $doctrine->getRepository(LeaveType::class);
        $leaveType = $leaveTypeRepo->find($id);
        if ($leaveType) {
            return $this->json($leaveType);
        } else {
            return new JsonResponse(["message" => "error not found"], 200);
        }
    }
}
