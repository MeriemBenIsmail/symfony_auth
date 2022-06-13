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

#[Route('/employe')]
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

        $entityManager->persist($employe);
        $entityManager->flush();
        return $this->json([
            'success' => $employe,200
        ]);
    }

}
