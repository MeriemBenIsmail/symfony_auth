<?php

namespace App\Service;

use App\Controller\GroupController;
use App\Controller\RoleController;
use App\Entity\User;
use App\Entity\Blacklisted;
use App\Entity\UserRole;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class AuthService
{
    private $doctrine;
    private $roleController;
    private $groupControler;

    public function __construct(ManagerRegistry $doctrine, RoleController $roleController, GroupController $groupControler)
    {
        $this->doctrine = $doctrine;
        $this->roleController = $roleController;
        $this->groupControler = $groupControler;
    }

    //check if token passed is valid or not ( logout case )
    public function isLoggedIn($request): bool

    {

        $tokenX = $request->headers->get('Authorization');
        preg_match('/Bearer\s(\S+)/', $tokenX, $matches);

        $repo = $this->doctrine->getRepository(Blacklisted::class);

        $token = $repo->findOneBy(['token' => $matches[1]]);

        if ($token) {
            return false;
        }
        return true;
    }

    public function isAdmin($user)
    {
        $repo = $this->doctrine->getRepository(User::class);

        $admin = $repo->findOneBy(['email' => $user->getEmail()]);
        if ($admin->isSuper() !== null) {
            return $admin;
        }
        return false;

    }

    public function isSuperAdmin($user): bool
    {
        $admin = $this->isAdmin($user);
        if ($admin) {
            if ($admin->isSuper()) {
                return true;
            }
        }
        return false;

    }

    public function hasRole($user, $roleName): bool
    {

        if ($this->isSuperAdmin($user)) {
            return true;
        }


        $groups = $user->getGroups();
        $rolesUnion = array();
        if ($user->getUserRoles()) {
            $rolesUnion = $user->getUserRoles()->toArray();
        }
        foreach ($groups as $group) {
            if ($group) {
                if ($group->getGroupRoles()) {
                    $rolesUnion = array_merge($rolesUnion, $group->getGroupRoles()->toArray());
                }
            }
        }
        $userRoleRepo = $this->doctrine->getRepository(UserRole::class);
        $userRole = $userRoleRepo->findOneBy(["name" => $roleName]);
        $roleParent = $userRole->getParentRole();
        while ($roleParent) {
            if (in_array($roleParent, $rolesUnion)) {
                return true;
            }
            $roleParent = $roleParent->getParentRole();
        }
        if (in_array($userRole, $rolesUnion)) {
            return true;
        }
        return false;
    }

}

$containerBuilder = new ContainerBuilder();
$containerBuilder->register('AuthService', 'AuthService');