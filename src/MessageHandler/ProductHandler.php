<?php

namespace App\MessageHandler;

use App\Document\Product as ProductDocument;
use App\Enum\ProductDocumentStatus;
use App\Message\Product as ProductMessage;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProductHandler
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }
    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function __invoke(ProductMessage $message): void
    {
        $productDocument = new ProductDocument();
        $productDocument->setName($message->name);
        $productDocument->setPrice($message->price);
        $productDocument->setCategory($message->category);
        $productDocument->setStatus(ProductDocumentStatus::New);
        $productDocument->setCreatedAt(new DateTimeImmutable());

        $this->dm->persist($productDocument);
        $this->dm->flush();
    }
}
