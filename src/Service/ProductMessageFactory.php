<?php

namespace App\Service;

use Faker\Factory;
use Faker\Generator;

class ProductMessageFactory
{

    private Generator $generator;
    public function __construct()
    {
        $this->generator = Factory::create('en_US');
    }

    public function getName(): string
    {
        return $this->generator->text(50);
    }

    public function getPrice(): float
    {
        return $this->generator->randomFloat(2, 1, 100);
    }

    public function getCategory(): string
    {
        $categories = [
            'Shoes',
            'Electrical',
            'Football',
            'PHP',
            'Hi People'
        ];
        return $categories[array_rand($categories)];
    }
}