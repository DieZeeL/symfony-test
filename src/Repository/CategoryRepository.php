<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getCategory($name): Category
    {
        try {
            $category = $this->createQueryBuilder('c')
                ->where('c.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            $category = new Category();
            $category->setName($name);
            $this->getEntityManager()->persist($category);
            $this->getEntityManager()->flush();
        }

        return $category;
    }
}
