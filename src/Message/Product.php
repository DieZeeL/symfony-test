<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('product_import')]
final readonly class Product
{
     public function __construct(
         public string $name,
         public float  $price,
         public string $category,
     ) {
         //
     }
}
