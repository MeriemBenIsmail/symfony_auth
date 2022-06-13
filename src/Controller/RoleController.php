<?php

namespace App\Controller;

use App\Entity\AdminRole;
use App\Entity\Permission;
use App\Service\AuthService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/roles')]
class RoleController extends AbstractController
{
    #[Route('/add', name: 'add_role')]
    public function addRole(AuthService $serv,Request $request,ManagerRegistry $doctrine): Response
    {   if(!$serv->isSuperAdmin($this->getUser())){
        return $this->json([
            'error' => "unauthorized",401
        ]);
         }
        $entityManager= $doctrine->getManager();
        $permissionRepo= $doctrine->getRepository(Permission::class);
         $adminRole=new AdminRole();
         //dd($request->request->get("permissions"));
         $permArray = explode(",",$request->request->get("permissions"));
         $adminRole->setName($request->request->get("name"));
         foreach ($permArray as $permission){
             $perm=$permissionRepo->find($permission);
             $adminRole->addPermission($perm);

         }
         $entityManager->persist($adminRole);
         $entityManager->flush();
        return $this->json([
            'success' => $adminRole,200
        ]);
    }
}
