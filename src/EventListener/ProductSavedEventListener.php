<?php

namespace App\EventListener;

use App\Event\ProductSavedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class ProductSavedEventListener
{
    public function __construct(private LoggerInterface $logger)
    {
        //
    }

    #[AsEventListener(event: ProductSavedEvent::class)]
    public function onProductSaved($event): void
    {
        $this->logger->info(sprintf("Product %s successfully saved", $event->getProductName()));
    }
}
