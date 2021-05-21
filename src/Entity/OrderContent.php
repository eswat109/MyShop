<?php

namespace App\Entity;

use App\Repository\OrderContentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderContentRepository::class)
 */
class OrderContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="p_orderContent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_order;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="p_orderContent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIdOrder(): ?Order
    {
        return $this->id_order;
    }

    public function setIdOrder(?Order $id_order): self
    {
        $this->id_order = $id_order;

        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->id_product;
    }

    public function setIdProduct(?Product $id_product): self
    {
        $this->id_product = $id_product;

        return $this;
    }
}
