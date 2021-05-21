<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="id_product", orphanRemoval=true)
     */
    private $p_cart;

    /**
     * @ORM\OneToMany(targetEntity=OrderContent::class, mappedBy="id_product", orphanRemoval=true)
     */
    private $p_orderContent;

    /**
     * @ORM\OneToMany(targetEntity=Whishlist::class, mappedBy="id_product", orphanRemoval=true)
     */
    private $p_whishlist;

    public function __construct()
    {
        $this->p_cart = new ArrayCollection();
        $this->p_orderContent = new ArrayCollection();
        $this->p_whishlist = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getPCart(): Collection
    {
        return $this->p_cart;
    }

    public function addPCart(Cart $pCart): self
    {
        if (!$this->p_cart->contains($pCart)) {
            $this->p_cart[] = $pCart;
            $pCart->setIdProduct($this);
        }

        return $this;
    }

    public function removePCart(Cart $pCart): self
    {
        if ($this->p_cart->contains($pCart)) {
            $this->p_cart->removeElement($pCart);
            // set the owning side to null (unless already changed)
            if ($pCart->getIdProduct() === $this) {
                $pCart->setIdProduct(null);
            }
        }

        return $this;
    }

    public function  __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|OrderContent[]
     */
    public function getPOrderContent(): Collection
    {
        return $this->p_orderContent;
    }

    public function addPOrderContent(OrderContent $pOrderContent): self
    {
        if (!$this->p_orderContent->contains($pOrderContent)) {
            $this->p_orderContent[] = $pOrderContent;
            $pOrderContent->setIdProduct($this);
        }

        return $this;
    }

    public function removePOrderContent(OrderContent $pOrderContent): self
    {
        if ($this->p_orderContent->contains($pOrderContent)) {
            $this->p_orderContent->removeElement($pOrderContent);
            // set the owning side to null (unless already changed)
            if ($pOrderContent->getIdProduct() === $this) {
                $pOrderContent->setIdProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Whishlist[]
     */
    public function getPWhishlist(): Collection
    {
        return $this->p_whishlist;
    }

    public function addPWhishlist(Whishlist $pWhishlist): self
    {
        if (!$this->p_whishlist->contains($pWhishlist)) {
            $this->p_whishlist[] = $pWhishlist;
            $pWhishlist->setIdProduct($this);
        }

        return $this;
    }

    public function removePWhishlist(Whishlist $pWhishlist): self
    {
        if ($this->p_whishlist->contains($pWhishlist)) {
            $this->p_whishlist->removeElement($pWhishlist);
            // set the owning side to null (unless already changed)
            if ($pWhishlist->getIdProduct() === $this) {
                $pWhishlist->setIdProduct(null);
            }
        }

        return $this;
    }
}
