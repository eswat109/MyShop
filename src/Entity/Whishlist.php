<?php

namespace App\Entity;

use App\Repository\WhishlistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WhishlistRepository::class)
 */
class Whishlist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Guest::class, inversedBy="p_whishlist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_guest;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="p_whishlist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdGuest(): ?Guest
    {
        return $this->id_guest;
    }

    public function setIdGuest(?Guest $id_guest): self
    {
        $this->id_guest = $id_guest;

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
