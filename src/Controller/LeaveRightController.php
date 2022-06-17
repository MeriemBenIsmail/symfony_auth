<?php

namespace App\Controller;

use App\Entity\LeaveRight;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/leave_rights')]
class LeaveRightController extends AbstractController
{
    #[Route('/delete/{id<\d+>}', name: 'leave_types.delete')]
    public function deleteLeaveRight(ManagerRegistry $doctrine, LeaveRight $leaveRight = null): Response
    {
        if ($leaveRight) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($leaveRight);
            $entityManager->flush();
            return $this->json($leaveRight);
        } else {
            return new JsonResponse([
                "message" => "error",
                "data" => "No such leave Right"], 401);
        }
    }

    #[Route('/', name: 'leave_rights.list')]
    public function getLeaveRights(Request $request, ManagerRegistry $doctrine): Response
    {
        $leaveRightRepo = $doctrine->getRepository(LeaveRight::class);
        $leaveRights = $leaveRightRepo->findAll();
        return $this->json($leaveRights);
    }

    #[Route('/{id<\d+>}', name: 'leave_rights.get')]
    public function getLeaveRight(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $leaveRightRepo = $doctrine->getRepository(LeaveRight::class);
        $leaveRight = $leaveRightRepo->find($id);
        if ($leaveRight) {
            return $this->json($leaveRight);
        } else {
            return new JsonResponse(["message" => "error not found"], 401);
        }
    }
}
