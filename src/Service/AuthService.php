<?php

namespace App\Service;

use App\Controller\RoleController;
use App\Entity\User;
use App\Entity\Blacklisted;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class AuthService
{
    private $doctrine;
    private $roleController;
    public function __construct(ManagerRegistry $doctrine,RoleController $roleController){
        $this->doctrine = $doctrine;
        $this->roleController=$roleController;
    }
    //check if token passed is valid or not ( logout case )
    public function  isLoggedIn($request): bool

    {

        $tokenX = $request->headers->get('Authorization');
        preg_match('/Bearer\s(\S+)/', $tokenX, $matches);

        $repo = $this->doctrine->getRepository(Blacklisted::class);

        $token = $repo->findOneBy(['token' => $matches[1]]);

        if($token) {
            return false;
        }
        return true;
    }
    public function  isAdmin($user)  {
        $repo = $this->doctrine->getRepository(User::class);

        $admin = $repo->findOneBy(['email' => $user->getEmail()]);
        if($admin->isSuper()!==null) {
            return $admin;
        }
        return false;

    }
    public function  isSuperAdmin($user) : bool {
        $admin=$this->isAdmin($user);
        if($admin){
            if($admin->isSuper()) {
                return true;
            }
        }
        return false;

    }
    public function hasRole($user,$roleEntity,$rolePerm) : bool {

        if($this->isSuperAdmin($user)){
            return true;
        }
        $role1= $this->roleController->getRoleByName($roleEntity,$this->doctrine);

        $groups = $user->getGroups();
        $rolesUnion = array_merge(array($groups[0]->getGroupRoles()),array($user->getUserRoles()));

        if(in_array($role1,$rolesUnion)) {
            return true;
        }
        $role2= $this->roleController->getRoleByName($rolePerm,$this->doctrine);

        if(in_array($role2,$rolesUnion)) {
            return true;
        }
        return false;
    }

}
$containerBuilder = new ContainerBuilder();
$containerBuilder->register('AuthService','AuthService');