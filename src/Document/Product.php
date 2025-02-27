<?php

namespace App\Document;

use App\Enum\ProductDocumentStatus;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'products')]
class Product
{
    #[ODM\Id(type: 'string', strategy: 'UUID')]
    protected ?string $id = null;

    #[ODM\Field]
    protected ?string $name = null;

    #[ODM\Field]
    protected ?float $price = null;

    #[ODM\Field]
    protected ?string $category = null;

    #[ODM\Field(type: 'string', enumType: ProductDocumentStatus::class)]
    #[ODM\Index]
    protected ?ProductDocumentStatus $status = null;
    
    #[ODM\Field(type: 'date_immutable')]
    protected ?DateTimeImmutable $createdAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getStatus(): ?ProductDocumentStatus
    {
        return $this->status;
    }

    public function setStatus(ProductDocumentStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}