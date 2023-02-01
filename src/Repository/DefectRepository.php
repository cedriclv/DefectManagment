<?php

namespace App\Repository;

use App\Entity\Defect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        return $this->createQueryBuilder('defect')
            ->select('c.date, count(defect) AS defectNumber')
            ->leftJoin('defect.count', 'c')
            ->groupBy('c.date')
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
