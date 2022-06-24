<?php

namespace App\Repository;

use App\Entity\LeaveRight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LeaveRight>
 *
 * @method LeaveRight|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeaveRight|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeaveRight[]    findAll()
 * @method LeaveRight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaveRightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeaveRight::class);
    }

    public function add(LeaveRight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LeaveRight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return LeaveRight[] Returns an array of LeaveRight objects
     */
    public function findCurrentYearlyRights(): array
    {
        $currentYear = date("Y");
        $currentYear=$currentYear."%";
        $qb = $this->createQueryBuilder('l')
            ->join('l.leaveType','t')
            ->where("l.startValidityDate LIKE :year ")
            ->andWhere('t.annual = true')
            ->andWhere('l.status = ACTIVE')
            ->setParameter('year', $currentYear);


        $query = $qb->getQuery();

        return $query->execute();
    }

//    /**
//     * @return LeaveRight[] Returns an array of LeaveRight objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LeaveRight
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
