<?php

namespace App\Service;

use App\Entity\Admin;
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
    public function  isAdmin($user) : bool {
        $repo = $this->doctrine->getRepository(Admin::class);

        $admin = $repo->findOneBy(['email' => $user->getEmail()]);
        dd($admin);
        if($admin) {
            return true;
        }
        return false;

    }

}
$containerBuilder = new ContainerBuilder();
$containerBuilder->register('AuthService','AuthService');