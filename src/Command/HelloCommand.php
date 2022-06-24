<?php

namespace App\Command;

use App\Controller\RoleController;
use App\Entity\UserRole;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HelloCommand extends Command
{
    protected static $defaultName = 'app:hello';
    private $doctrine;
    private $roleController;

    public function __construct(ManagerRegistry $doctrine,RoleController $roleController)
    {
        $this->doctrine = $doctrine;
        $this->roleController=$roleController;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('says hello');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $manager=$this->doctrine->getManager();
        $role=new UserRole();
        $role->setName("hdfdsh");
        $manager->persist($role);
        $manager->flush();
        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf("done"));
        return 0;
    }
}