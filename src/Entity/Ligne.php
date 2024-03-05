<?php

namespace App\Entity;

use App\Repository\LigneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=LigneRepository::class)
 */
class Ligne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $libelle_2;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Statut::class, inversedBy="lignes")
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="lignes")
     * @JoinColumn(onDelete="SET NULL")
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_insert;


    /**
     * @ORM\Column(type="integer")
     */
    private $origine;


    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="lignes")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="lignes_created")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=BankAccount::class, inversedBy="accountantEntry")
     */
    private $bankAccount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $closed = false;

    public function __construct()
    {
        $this->date_insert = new \DateTime('now', new \DateTimeZone('Europe/paris'));
        $this->owner = new ArrayCollection();
        
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getLabel():?string
    {
        return $this->getLibelle();
    }

    public function getLibelle2(): ?string
    {
        return $this->libelle_2;
    }

    public function setLibelle2(?string $libelle_2): self
    {
        $this->libelle_2 = $libelle_2;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getAmount() :?float
    {
        return $this->getMontant();
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


    public function getDateInsert(): ?\DateTimeInterface
    {
        return $this->date_insert;
    }

    public function setDateInsert(\DateTimeInterface $date_insert): self
    {
        $this->date_insert = $date_insert;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(User $owner): self
    {
        if (!$this->owner->contains($owner)) {
            $this->owner[] = $owner;
        }

        return $this;
    }

    public function removeOwner(User $owner): self
    {
        $this->owner->removeElement($owner);

        return $this;
    }

    public function hasOwner() : bool
     {
        return $this->owner->count() > 0;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getOrigine(): ?int
    {
        return $this->origine;
    }

    public function setOrigine(int $origine): self
    {
        $this->origine = $origine;

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

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function getClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    
}