<?php

namespace App\Event;

use App\Entity\Product as ProductEntity;
use Symfony\Contracts\EventDispatcher\Event;

class ProductSavedEvent extends Event
{
    public function __construct(private ProductEntity $product)
    {
        //
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->product->getName();
    }
}