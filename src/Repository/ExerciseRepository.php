<?php

namespace App\Repository;

use App\Entity\Exercise;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Exercise>
 *
 * @method Exercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercise[]    findAll()
 * @method Exercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    public function save(Exercise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Exercise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOrCreateForUser(string $exerciseName, User|UserInterface $user): Exercise
    {
        $exercise = $this->findForUser($exerciseName, $user);
        if(false === $exercise instanceof Exercise) {
            $exercise = new Exercise();
            $exercise->setName($exerciseName);
            $exercise->setUser($user);
            $this->save($exercise, true);
        }
        return $exercise;
    }

    public function findForUser(string $exerciseName, User|UserInterface $user): ?Exercise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.name = :name')->setParameter('name', $exerciseName)
            ->andWhere('e.user = :user')->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Exercise[] Returns an array of Exercise objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


}
