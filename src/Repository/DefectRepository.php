<?php

namespace App\Repository;

use DateTime;
use DateInterval;
use App\Entity\Defect;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Defect>
 *
 * @method Defect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Defect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Defect[]    findAll()
 * @method Defect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefectRepository extends ServiceEntityRepository
{
    public function findDefectsPerCount(): array
    {
        return $this->createQueryBuilder('d')
            ->select('c.date, r.name AS reason, count(d) AS defectNumber')
            ->leftJoin('d.count', 'c')
            ->leftJoin('d.reason', 'r')
            ->groupBy('c.date','r.name')
            ->getQuery()
            ->getResult();
    }
    public function findDefectsPerReason(DateTime $mondaylastWeek): array
    {

        return $this->createQueryBuilder('d')
            ->select('r.name AS reason, count(d) AS defectNumber, d.isInvestigated AS isInvestigated ')
            ->leftJoin('d.reason', 'r')
            ->leftJoin('d.count', 'c')
//            ->andWhere('d.isInvestigated = true')
            ->andWhere('c.date >= :valmin')
            ->andWhere('c.date >= :valmax')
            ->setParameter('valmin', $mondaylastWeek)
            ->setParameter('valmax', $mondaylastWeek->add(new DateInterval('P7D')))
            ->groupBy('r.name, d.isInvestigated')
            ->getQuery()
            ->getResult();
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Defect::class);
    }

    public function save(Defect $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Defect $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Defect[] Returns an array of Defect objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Defect
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
