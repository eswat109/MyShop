<?php

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuestRepository::class)
 */
class Guest
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
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $secondName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="id_guest", orphanRemoval=true)
     */
    private $p_cart;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="id_guest", orphanRemoval=true)
     */
    private $p_order;

    /**
     * @ORM\OneToMany(targetEntity=Whishlist::class, mappedBy="id_guest", orphanRemoval=true)
     */
    private $p_whishlist;

    public function __construct()
    {
        $this->p_cart = new ArrayCollection();
        $this->p_order = new ArrayCollection();
        $this->p_whishlist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(string $secondName): self
    {
        $this->secondName = $secondName;

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
            $pCart->setIdGuest($this);
        }

        return $this;
    }

    public function removePCart(Cart $pCart): self
    {
        if ($this->p_cart->contains($pCart)) {
            $this->p_cart->removeElement($pCart);
            // set the owning side to null (unless already changed)
            if ($pCart->getIdGuest() === $this) {
                $pCart->setIdGuest(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName.' '.$this->secondName;
    }

    /**
     * @return Collection|Order[]
     */
    public function getPOrder(): Collection
    {
        return $this->p_order;
    }

    public function addPOrder(Order $pOrder): self
    {
        if (!$this->p_order->contains($pOrder)) {
            $this->p_order[] = $pOrder;
            $pOrder->setIdGuest($this);
        }

        return $this;
    }

    public function removePOrder(Order $pOrder): self
    {
        if ($this->p_order->contains($pOrder)) {
            $this->p_order->removeElement($pOrder);
            // set the owning side to null (unless already changed)
            if ($pOrder->getIdGuest() === $this) {
                $pOrder->setIdGuest(null);
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
            $pWhishlist->setIdGuest($this);
        }

        return $this;
    }

    public function removePWhishlist(Whishlist $pWhishlist): self
    {
        if ($this->p_whishlist->contains($pWhishlist)) {
            $this->p_whishlist->removeElement($pWhishlist);
            // set the owning side to null (unless already changed)
            if ($pWhishlist->getIdGuest() === $this) {
                $pWhishlist->setIdGuest(null);
            }
        }

        return $this;
    }
}
