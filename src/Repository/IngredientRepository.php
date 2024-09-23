<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

        public function save(Ingredient $ingredient): Ingredient
        {
            $this->getEntityManager()->persist($ingredient);
            $this->getEntityManager()->flush();

            return $ingredient;
        }

        public function findOneByName(string $name): ?Ingredient
        {
            return $this->createQueryBuilder('i')
                ->andWhere('i.name = :val')
                ->setParameter('val', $name)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
}
