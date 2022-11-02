<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Workout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Workout>
 *
 * @method Workout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workout[]    findAll()
 * @method Workout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workout::class);
    }

    public function save(Workout $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Workout $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForUser(int $id, User|UserInterface $user): ?Workout
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id = :id')->setParameter('id', $id)
            ->andWhere('w.user = :user')->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryForUser(User|UserInterface $user): Query
    {
        return $this->createQueryBuilder('w')->andWhere('w.user = :user')->setParameter('user', $user)->getQuery();
    }
}
