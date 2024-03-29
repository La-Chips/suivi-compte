<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("default")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("default")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Filter::class, mappedBy="categorie")
     */
    private $filters;

    /**
     * @ORM\OneToMany(targetEntity=Ligne::class, mappedBy="categorie")
     */
    private $lignes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $color;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="categories")
     */
    private ?User $User;

    /**
     * @ORM\OneToMany(targetEntity=ScheduleExpense::class, mappedBy="category")
     */
    private $scheduleExpenses;

    #[Pure] public function __construct()
    {
        $this->filters = new ArrayCollection();
        $this->lignes = new ArrayCollection();
        $this->scheduleExpenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLabel(): ?string
    {
        return $this->getLibelle();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * @return Collection
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function addFilter(Filter $filter): self
    {
        if (!$this->filters->contains($filter)) {
            $this->filters[] = $filter;
            $filter->setCategorie($this);
        }

        return $this;
    }

    public function removeFilter(Filter $filter): self
    {
        if ($this->filters->removeElement($filter)) {
            // set the owning side to null (unless already changed)
            if ($filter->getCategorie() === $this) {
                $filter->setCategorie(null);
            }
        }

        return $this;
    }

    public function hasKeywords()
    {
        return $this->filters->count() > 0;
    }

    /**
     * @return Collection
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(Ligne $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes[] = $ligne;
            $ligne->setCategorie($this);
        }

        return $this;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getCategorie() === $this) {
                $ligne->setCategorie(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, ScheduleExpense>
     */
    public function getScheduleExpenses(): Collection
    {
        return $this->scheduleExpenses;
    }

    public function addScheduleExpense(ScheduleExpense $scheduleExpense): self
    {
        if (!$this->scheduleExpenses->contains($scheduleExpense)) {
            $this->scheduleExpenses[] = $scheduleExpense;
            $scheduleExpense->setCategory($this);
        }

        return $this;
    }

    public function removeScheduleExpense(ScheduleExpense $scheduleExpense): self
    {
        if ($this->scheduleExpenses->removeElement($scheduleExpense)) {
            // set the owning side to null (unless already changed)
            if ($scheduleExpense->getCategory() === $this) {
                $scheduleExpense->setCategory(null);
            }
        }

        return $this;
    }
}
