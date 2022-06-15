<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Group;
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


#[Route('/employes')]
class EmployeController extends AbstractController
{
    #[Route('/add', name: 'employe.add')]
    public function addEmploye(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $employe = new Employe();
        $employe->setEmail($request->request->get('email'));
        // hashing the password
        $hashedPassword = $passwordHasher->hashPassword(
            $employe,
            $request->request->get('password')
        );

        $employe->setPassword($hashedPassword);
        $employe->setMatricule($request->request->get('matricule'));
        $employe->setNom($request->request->get('nom'));
        $employe->setPrenom($request->request->get('prenom'));
        $employe->setAdresse($request->request->get('adresse'));
        $employe->setTelPerso($request->request->get('telPerso'));
        $employe->setTelPro($request->request->get('telPro'));
        $date= strtotime($request->request->get('dateEmbauche'));
        $newdate=date("Y-m-d",$date);
        $employe->setDateEmbauche($newdate);
        if ($request->request->get("groups")) {
            $groupRepo = $doctrine->getRepository(Group::class);
            $groupsArray = explode(",", $request->request->get("groups"));
            foreach ($groupsArray as $group) {
                $grp = $groupRepo->find($group);
                $grp->addUser($employe);
                $entityManager->persist($grp);
            }
        }
        if ($request->request->get("roles")) {
            $userRoleRepo = $doctrine->getRepository(UserRole::class);
            $userRolesArray = explode(",", $request->request->get("roles"));
            foreach ($userRolesArray as $userRole) {
                $userRol = $userRoleRepo->find($userRole);
                $employe->addUserRole($userRol);
            }
        }
        $entityManager->persist($employe);
        $entityManager->flush();
        return $this->json($employe, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/update/{matricule}', name: 'employes.update')]
    public function updateRole(Employe $employe = null, Request $request, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        if ($employe) {
            $form = $this->createForm(EmployeType::class, $employe);
            $form->handleRequest($request);
            $form->submit($request->request->all(), false);

            if ($request->request->get("groups")) {
                $groupRepo = $doctrine->getRepository(Group::class);
                $groupsArray = explode(",", $request->request->get("groups"));
                $employe->emptyGroups();
                foreach ($groupsArray as $group) {
                    $grp = $groupRepo->find($group);
                    $employe->addGroup($grp);
                }
            }
            if ($request->request->get("roles")) {
                $roleRepo = $doctrine->getRepository(UserRole::class);
                $roleArray = explode(",", $request->request->get("roles"));
                $employe->emptyUserRoles();
                foreach ($roleArray as $role) {
                    $rol = $roleRepo->find($role);
                    $employe->addUserRole($rol);
                }
            }

            if ($form->isSubmitted()) {
                $entityManager->persist($employe);
                $entityManager->flush();
            }

            return $this->json([
                "message" => "success",
                "data" => $employe], 200
            );
        }
        return $this->json([
            "message" => "error",
            "data" => "No such employe"], 200
        );


    }

    #[Route('/', name: 'employes.all')]
    public function getEmployes(ManagerRegistry $doctrine)
    {
        $repo = $doctrine->getRepository(Employe::class);
        $employes = $repo->findAll();
        return $this->json([
            'employes' => $employes,200
        ]);
    }

    #[Route('/{matricule}', name: 'employes.matricule')]
    public function getByMatricule(ManagerRegistry $doctrine,$matricule): JsonResponse
    {

        $repo = $doctrine->getRepository(Employe::class);
        $employe = $repo->findOneBy(['matricule' => $matricule]);
        return $this->json([
            'employe' => $employe,200
        ]);
    }

    #[Route('/{id<\d+>}', name: 'employes.detail')]
    public function detail(Employe $employe = null): JsonResponse
    {
        return $this->json([
            'employe' => $employe,200
        ]);
    }



}
