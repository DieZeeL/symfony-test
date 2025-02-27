<?php

namespace App\Command;

use App\Document\Product as ProductDocument;
use App\Entity\Product as ProductEntity;
use App\Enum\ProductDocumentStatus;
use App\Event\ProductSavedEvent;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsCommand(
    name: 'app:process-products',
    description: 'Process products',
)]
class ProcessProductsCommand extends Command
{

    public function __construct(
        private DocumentManager          $dm,
        private EntityManagerInterface   $em,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface        $logger
    )
    {
        parent::__construct();
    }

    /**
     * @throws MongoDBException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Processing new products');

        $cursor = $this->dm->getRepository(ProductDocument::class)
            ->createQueryBuilder()
            ->setRewindable(false) // This parameter user for disable CachedIterator and leak memory
            ->field('status')->equals(ProductDocumentStatus::New)
            ->getQuery()
            ->execute();

        while ($cursor->valid())
        {
            /** @var ProductDocument $document */
            $document = $cursor->current();

            try {
                $product = $this->em->getRepository(ProductEntity::class)
                ->storeProductFromMongo($document);

                $document->setStatus(ProductDocumentStatus::Processed);
                $this->dm->flush();

                $this->eventDispatcher->dispatch(new ProductSavedEvent($product), 'product.saved');

            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
            }

            $cursor->next();
        }

        $io->success('Successfully processed products');

        return Command::SUCCESS;
    }
}
