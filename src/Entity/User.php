<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


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
     * @ORM\ManyToMany(targetEntity=Ligne::class, mappedBy="owner")
     */
    private $lignes;

    /**
     * @ORM\OneToMany(targetEntity=Ligne::class, mappedBy="user")
     */
    private $lignes_created;

    /**
     * @ORM\OneToMany(targetEntity=BankAccount::class, mappedBy="user")
     */
    private $bankAccounts;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="User")
     */
    private $categories;

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
        $this->lignes_created = new ArrayCollection();
        $this->bankAccounts = new ArrayCollection();
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

    public function is(string $role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * @return Collection<int, Ligne>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(Ligne $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes[] = $ligne;
            $ligne->addOwner($this);
        }

        return $this;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->lignes->removeElement($ligne)) {
            $ligne->removeOwner($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username;
    }

    /**
     * @return Collection<int, Ligne>
     */
    public function getLignesCreated(?string $sort = null , string $order = 'ASC' ): Collection
    {
        if($sort != null) {
            return $this->lignes_created->matching(
                Criteria::create()->orderBy([$sort => $order])
            );
        }

        return $this->lignes_created;
    }

    public function addLignesCreated(Ligne $lignesCreated): self
    {
        if (!$this->lignes_created->contains($lignesCreated)) {
            $this->lignes_created[] = $lignesCreated;
            $lignesCreated->setUser($this);
        }

        return $this;
    }

    public function removeLignesCreated(Ligne $lignesCreated): self
    {
        if ($this->lignes_created->removeElement($lignesCreated)) {
            // set the owning side to null (unless already changed)
            if ($lignesCreated->getUser() === $this) {
                $lignesCreated->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BankAccount>
     */
    public function getBankAccounts(): Collection
    {
        return $this->bankAccounts;
    }

    public function addBankAccount(BankAccount $bankAccount): self
    {
        if (!$this->bankAccounts->contains($bankAccount)) {
            $this->bankAccounts[] = $bankAccount;
            $bankAccount->setUser($this);
        }

        return $this;
    }

    public function removeBankAccount(BankAccount $bankAccount): self
    {
        if ($this->bankAccounts->removeElement($bankAccount)) {
            // set the owning side to null (unless already changed)
            if ($bankAccount->getUser() === $this) {
                $bankAccount->setUser(null);
            }
        }

        return $this;
    }

    // has bank account
    public function hasBankAccount(): bool
    {
        return $this->bankAccounts->count() > 0;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    // has categories
    public function hasCategories(): bool
    {
        return $this->categories->count() > 0;
    }
}