<?php

namespace App\Entity;

use App\Repository\FilterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilterRepository::class)
 */
class Filter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $keyword;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="filters")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="filters")
     */
    private $user;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): self
    {
        $keywork = strtolower($keyword);
        $keyword = trim($keyword);
        $this->keyword = $keyword;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    // has amount 
    public function hasAmount(): bool
    {
        return $this->amount !== null;
    }
}
