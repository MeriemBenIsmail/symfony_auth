<?php

namespace App\Controller;

use App\Entity\ContactUrgence;
use App\Entity\Employe;
use App\Entity\Group;
use App\Entity\Poste;
use App\Entity\UserRole;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


#[Route('/employees')]
class EmployeController extends AbstractController
{
    #[Route('/add', name: 'employee.add')]
    public function addEmployee(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $date= strtotime($request->request->get('dateEmbauche'));
        $newdate=date("Y-m-d",$date);
        $hashedPassword = $passwordHasher->hashPassword(
            $employe,
            $request->request->get('password')
        );

        if ($request->request->get("roles")) {
            $roles = explode(",", $request->request->get("roles"));
            $employe->setRoles($roles);
        }
        if ($request->request->get("groups")) {
            $groupRepo = $doctrine->getRepository(Group::class);
            $groupsArray = explode(",", $request->request->get("groups"));
            foreach ($groupsArray as $group) {
                $grp = $groupRepo->find($group);
                $employe->addGroup($grp);
            }
        }

        if ($request->request->get('contactUrgence')) {
            $contactRepo = $doctrine->getRepository(ContactUrgence::class);
            $contact = $contactRepo->find($request->request->get('contactUrgence'));
            $employe->setContactUrgence($contact);
        }
        if ($request->request->get('poste')) {
            $posteRepo = $doctrine->getRepository(Poste::class);
            $poste = $posteRepo->find($request->request->get('poste'));
            $employe->setPoste($poste);
        }

        $form->handleRequest($request);
        // hashing the password

        $form->submit($request->request->all(),false);
        $newEmploye = $form->getData();
        $newEmploye->setPassword($hashedPassword);

        if ($form->isSubmitted()) {
            $entityManager->persist($newEmploye);
            $entityManager->flush();
        }

        return $this->json($newEmploye, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/update/{matriculate}', name: 'employees.update')]
    public function updateRole(Employe $employe = null, Request $request, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        if ($employe) {
            $form = $this->createForm(EmployeType::class, $employe);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);

            if ($request->request->get("roles") !== null) {

                $roles = explode(",", $request->request->get("roles"));
                if ($request->request->get("roles") == "") {
                    $roles = [];
                }
                $employe->setRoles($roles);
            }
            if ($request->request->get("groups") !== null) {
                $employe->emptyGroups();
                $groupRepo = $doctrine->getRepository(Group::class);
                $groupsArray = explode(",", $request->request->get("groups"));
                foreach ($groupsArray as $group) {
                    if ($group) {
                        $grp = $groupRepo->find($group);
                        $employe->addGroup($grp);
                    }
                }
            }

            if ($form->isSubmitted()) {
                $entityManager->persist($employe);
                $entityManager->flush();
            }

            return $this->json($employe, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        }
        return $this->json([
            "message" => "error",
            "data" => "No such employee"], 200
        );


    }

    #[Route('/delete/{id<\d+>}', name: 'employees.delete')]
    public function deleteEmployee(Employe $employee, ManagerRegistry $doctrine): Response
    {
        if ($employee) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($employee);
            $entityManager->flush();
            return $this->json($employee, Response::HTTP_OK, [], [
                ObjectNormalizer::SKIP_NULL_VALUES => true,
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ]);
        } else {
            return new JsonResponse([
                    "message" => "error"]
            );
        }
    }

    #[Route('/', name: 'employees.all')]
    public function getEmployees(ManagerRegistry $doctrine)
    {
        $repo = $doctrine->getRepository(Employe::class);
        $employes = $repo->findAll();
        return $this->json($employes, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{matriculate}', name: 'employees.matriculate')]
    public function getByMatriculate(ManagerRegistry $doctrine,$matriculate): JsonResponse
    {

        $repo = $doctrine->getRepository(Employe::class);
        $employe = $repo->findOneBy(['matricule' => $matriculate]);
        return $this->json($employe, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id<\d+>}', name: 'employees.detail')]
    public function detail(Employe $employee = null): JsonResponse
    {
        return $this->json($employee, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

}
