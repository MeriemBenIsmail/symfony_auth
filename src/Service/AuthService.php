<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Blacklisted;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class AuthService
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
    }
    public function isLoggedIn($request): bool

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

}
$containerBuilder = new ContainerBuilder();
$containerBuilder->register('AuthService','AuthService');