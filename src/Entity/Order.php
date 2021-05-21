<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
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
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderAt;

    /**
     * @ORM\ManyToOne(targetEntity=Guest::class, inversedBy="p_order")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_guest;

    /**
     * @ORM\OneToMany(targetEntity=OrderContent::class, mappedBy="id_order", orphanRemoval=true)
     */
    private $p_orderContent;

    public function __construct()
    {
        $this->p_orderContent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getOrderAt(): ?\DateTimeInterface
    {
        return $this->orderAt;
    }

    public function setOrderAt(\DateTimeInterface $orderAt): self
    {
        $this->orderAt = $orderAt;

        return $this;
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
            $pOrderContent->setIdOrder($this);
        }

        return $this;
    }

    public function removePOrderContent(OrderContent $pOrderContent): self
    {
        if ($this->p_orderContent->contains($pOrderContent)) {
            $this->p_orderContent->removeElement($pOrderContent);
            // set the owning side to null (unless already changed)
            if ($pOrderContent->getIdOrder() === $this) {
                $pOrderContent->setIdOrder(null);
            }
        }

        return $this;
    }
}
