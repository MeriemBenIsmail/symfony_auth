<?php

namespace App\Command;

use App\Controller\RoleController;
use App\Entity\LeaveRight;
use App\Entity\UserRole;
use App\Repository\LeaveRightRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Date;

class MonthlyCommand extends Command
{
    protected static $defaultName = 'app:monthly';
    private $doctrine;
    private $leaveRightRepository;

    public function __construct(ManagerRegistry $doctrine, LeaveRightRepository $leaveRightRepository)
    {
        $this->doctrine = $doctrine;
        $this->leaveRightRepository = $leaveRightRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Updates balance ,creates new rights and expires invalid ones');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $manager = $this->doctrine->getManager();

        //Expire yearly invalid old leaveRights
        $yearlyExpiredCriteria = new Criteria();
        $date = new \DateTime('@' . strtotime('now'));
        $yearlyExpiredCriteria->where(Criteria::expr()->lt('endValidityDate', $date));
        $expiredRights = $this->leaveRightRepository->matching($yearlyExpiredCriteria);
        foreach ($expiredRights as $expiredRight) {
            $expiredRight->setStatus(LeaveRight::EXPIRED);
            $manager->persist($expiredRight);
        }

        //Expire invalid  leaveRights : not yearly and balance = 0
        $query1 = $manager->createQuery(
            'SELECT l
            FROM App\Entity\LeaveRight l , App\Entity\LeaveType t
            WHERE l.balance = 0
            and t.annual = false
            and t.id = l.leaveType
            '
        );
        $expiredNotYearlyBalances = $query1->getResult();

        foreach ($expiredNotYearlyBalances as $expiredNotYearlyBalance) {
            $expiredNotYearlyBalance->setStatus(LeaveRight::EXPIRED);
            $manager->persist($expiredNotYearlyBalance);
        }

        //Add unit to balance for each employee's leaveRight of yearly type and of this year
        $creditedLeaveRights = $this->leaveRightRepository->findCurrentYearlyRights();
        foreach ($creditedLeaveRights as $creditedLeaveRight) {
            $creditedLeaveRight->setBalance($creditedLeaveRight->getBalance() + $creditedLeaveRight->getUnit());
            $manager->persist($creditedLeaveRight);

        }

        $manager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf("done"));
        return 0;
    }
}