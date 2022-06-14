<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/postes')]
class PosteController extends AbstractController
{

    #[Route('/add', name: 'postes.add')]
    public function addPoste(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted()) {
            $entityManager->persist($poste);
            $entityManager->flush();
        }
        return $this->json($poste, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/update/{id<\d+>}', name: 'postes.update')]
    public function updatePoste(Poste $poste = null, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($poste) {
            $entityManager = $doctrine->getManager();
            $form = $this->createForm(PosteType::class, $poste);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);
            if ($form->isSubmitted()) {
                $entityManager->persist($poste);
                $entityManager->flush();
            }
            return $this->json($poste, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return new JsonResponse([
            "message" => "error",
            "data" => "No such poste"], 200);
    }

    #[Route('/', name: 'postes.list')]
    public function getPostes(Request $request, ManagerRegistry $doctrine): Response
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
