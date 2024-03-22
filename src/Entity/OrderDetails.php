<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?order $id_order = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $Quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?order
    {
        return $this->id_order;
    }

    public function setIdOrder(order $id_order): static
    {
        $this->id_order = $id_order;

        return $this;
    }

    public function getProduct(): ?product
    {
        return $this->product;
    }

    public function setProduct(?product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->Quantity;
    }

    public function setQuantity(string $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }
}
