<?php

namespace App\Controller;

use App\Entity\UserRole;
use App\Service\AuthService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


#[Route('/api/roles')]
class RoleController extends AbstractController
{
    #[Route('/add', name: 'add_role')]
    public function addRole(AuthService $serv,Request $request,ManagerRegistry $doctrine): Response
    {   /*if(!$serv->isSuperAdmin($this->getUser())){
        return $this->json([
            'error' => "unauthorized",401
        ]);
         }*/
        $entityManager= $doctrine->getManager();
        $userRoleRepo= $doctrine->getRepository(UserRole::class);
         $userRole=new UserRole();
         $userRole->setName($request->request->get("name"));
         if($request->request->get("userRoles")){
             $roleArray = explode(",",$request->request->get("userRoles"));
             foreach ($roleArray as $role){
             $rol=$userRoleRepo->find($role);
             $userRole->addUserRole($rol);
         }}
         $entityManager->persist($userRole);
         $entityManager->flush();
        return $this->json($userRole, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }
}
