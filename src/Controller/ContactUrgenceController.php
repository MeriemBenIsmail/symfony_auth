<?php

namespace App\Controller;

use App\Entity\ContactUrgence;
use App\Form\ContactUrgenceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

{
    #[Route('/contacts')]
    class ContactUrgenceController extends AbstractController
    {
        #[Route('/add', name: 'contacts.add')]
        public function addContact(Request $request, ManagerRegistry $doctrine): Response
        {
            $entityManager = $doctrine->getManager();
            $contact = new ContactUrgence();
            $form = $this->createForm(ContactUrgenceType::class, $contact);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);

            if ($form->isSubmitted()) {
                $entityManager->persist($contact);
                $entityManager->flush();
            }
            return $this->json($contact, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        #[Route('/delete/{id<\d+>}', name: 'contacts.delete')]
        public function deleteContact(ManagerRegistry $doctrine, ContactUrgence $contact = null): Response
        {
            if ($contact) {
                $entityManager = $doctrine->getManager();
                $entityManager->remove($contact);
                $entityManager->flush();
                return $this->json($contact, Response::HTTP_OK, [], [
                    ObjectNormalizer::SKIP_NULL_VALUES => true,
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId();
                    }
                ]);
            } else {
                return new JsonResponse([
                    "message" => "error",
                    "data" => "No such contact"], 200);
            }
        }
        #[Route('/update/{id<\d+>}', name: 'contacts.update')]
        public function updateContact(ContactUrgence $contact = null, Request $request, ManagerRegistry $doctrine): Response
        {
            if ($contact) {
                $entityManager = $doctrine->getManager();
                $form = $this->createForm(ContactUrgenceType::class, $contact);
                $form->handleRequest($request);
                $form->submit($request->request->all(), false);
                if ($form->isSubmitted()) {
                    $entityManager->persist($contact);
                    $entityManager->flush();
                }
                return $this->json($contact, Response::HTTP_OK, [], [
                    ObjectNormalizer::SKIP_NULL_VALUES => true,
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId();
                    }
                ]);
            }
            return new JsonResponse([
                "message" => "error",
                "data" => "No such contact"], 200);
        }

        #[Route('/', name: 'contacts.list')]
        public function getContacts(Request $request, ManagerRegistry $doctrine): Response
        {
            $contactRepo = $doctrine->getRepository(ContactUrgence::class);
            $contacts = $contactRepo->findAll();
            return $this->json($contacts, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }

        #[Route('/{id<\d+>}', name: 'contacts.get')]
        public function getContact(int $id, ManagerRegistry $doctrine): Response
        {
            $contactRepo = $doctrine->getRepository(ContactUrgence::class);
            $contact = $contactRepo->find($id);
            if ($contact) {
                return $this->json($contact, Response::HTTP_OK, [], [
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
}
