<?php

namespace App\Repository;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsPagination(int $first_result, int $results_per_page, Trick $trick)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.trick = :trick')
            ->setFirstResult($first_result)
            ->setMaxResults($results_per_page)
            ->orderBy('c.created_at', 'DESC')
            ->setParameter('trick', $trick)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Trick $trick
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumberOfTotalComments(Trick $trick)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('c.trick = :trick')
            ->setParameter('trick', $trick)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Comment $comment
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Comment $comment)
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
