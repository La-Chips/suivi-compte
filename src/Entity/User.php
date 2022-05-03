<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Ligne::class, mappedBy="user")
     */
    private $AllLignes;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="User")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Filter::class, mappedBy="user")
     */
    private $filters;

    /**
     * @ORM\OneToOne(targetEntity=LastImport::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $lastImport;


    public function __construct()
    {
        $this->AllLignes = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->filters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isAdmin()
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    /**
     * @return Collection<int, Ligne>
     */
    public function getAllLignes(): Collection
    {
        return $this->AllLignes;
    }

    public function addAllLigne(Ligne $allLigne): self
    {
        if (!$this->AllLignes->contains($allLigne)) {
            $this->AllLignes[] = $allLigne;
            $allLigne->setUser($this);
        }

        return $this;
    }

    public function removeAllLigne(Ligne $allLigne): self
    {
        if ($this->AllLignes->removeElement($allLigne)) {
            // set the owning side to null (unless already changed)
            if ($allLigne->getUser() === $this) {
                $allLigne->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Filter>
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function addFilter(Filter $filter): self
    {
        if (!$this->filters->contains($filter)) {
            $this->filters[] = $filter;
            $filter->setUser($this);
        }

        return $this;
    }

    public function removeFilter(Filter $filter): self
    {
        if ($this->filters->removeElement($filter)) {
            // set the owning side to null (unless already changed)
            if ($filter->getUser() === $this) {
                $filter->setUser(null);
            }
        }

        return $this;
    }

    public function getLastImport(): ?LastImport
    {
        return $this->lastImport;
    }

    public function setLastImport(?LastImport $lastImport): self
    {
        // unset the owning side of the relation if necessary
        if ($lastImport === null && $this->lastImport !== null) {
            $this->lastImport->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($lastImport !== null && $lastImport->getUser() !== $this) {
            $lastImport->setUser($this);
        }

        $this->lastImport = $lastImport;

        return $this;
    }
}