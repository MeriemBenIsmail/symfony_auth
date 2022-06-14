<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/employes')]
class EmployeController extends AbstractController
{
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

        $entityManager->persist($employe);
        $entityManager->flush();
        return $this->json([
            'success' => $employe,200
        ]);
    }

    #[Route('/update/{matricule}', name: 'employes.update')]
    public function updateEmploye(ManagerRegistry $doctrine,Request $request,$matricule): JsonResponse
    {
        dd($request->request->all()['nom']);
        $entityManager = $doctrine->getManager();
        $repo = $doctrine->getRepository(Employe::class);
        $employe = $repo->findOneBy(['matricule' => $matricule]);

        $entityManager->persist($employe);
        $entityManager->flush();
        return $this->json([
            'success' => $employe,200
        ]);
    }

}
