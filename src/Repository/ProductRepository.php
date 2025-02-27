<?php

namespace App\Repository;

use App\Document\Product as ProductDocument;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function storeProductFromMongo(ProductDocument $document): Product
    {
        $category = $this->getEntityManager()->getRepository(Category::class)
            ->getCategory($document->getCategory());

        return $this->createOrUpdate(
            name: $document->getName(),
            price: $document->getPrice(),
            category: $category
        );

    }

    public function createOrUpdate(string $name, float $price, Category $category): Product
    {
        return $this->getEntityManager()
            ->wrapInTransaction(function (EntityManagerInterface $em) use ($name, $price, $category) {
                try {
                    $product = $em->getRepository(Product::class)
                        ->createQueryBuilder('p')
                        ->where('p.name = :name')
                        ->setParameter('name', $name)
                        ->getQuery()
                        ->getSingleResult();
                } catch (NoResultException $e) {
                    $product = new Product();
                    $product->setName($name);
                }

                $product->setPrice($price);
                $product->setCategory($category);

                $em->persist($product);
                $em->flush();
                return $product;
            });
    }
}
