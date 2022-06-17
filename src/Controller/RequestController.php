<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/requests')]
class RequestController extends AbstractController
{
    #[Route('/', name: 'requests.list')]
    public function getRequests(Request $request, ManagerRegistry $doctrine): Response
    {
        $posteRepo = $doctrine->getRepository(Poste::class);
        $postes = $posteRepo->findAll();
        return $this->json($postes, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id<\d+>}', name: 'postes.get')]
    public function getPoste(int $id, ManagerRegistry $doctrine): Response
    {
        $posteRepo = $doctrine->getRepository(Poste::class);
        $poste = $posteRepo->find($id);
        if ($poste) {
            return $this->json($poste, Response::HTTP_OK, [], [
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
