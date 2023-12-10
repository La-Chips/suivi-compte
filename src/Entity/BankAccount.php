<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Categorie;

/**
 * @ORM\Entity(repositoryClass=BankAccountRepository::class)
 */
class BankAccount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bankAccounts")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=ScheduleExpense::class, mappedBy="bankAccount")
     */
    private $scheduleExpenses;

    public function __construct()
    {
        $this->scheduleExpenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
            $scheduleExpense->setBankAccount($this);
        }

        return $this;
    }

    public function removeScheduleExpense(ScheduleExpense $scheduleExpense): self
    {
        if ($this->scheduleExpenses->removeElement($scheduleExpense)) {
            // set the owning side to null (unless already changed)
            if ($scheduleExpense->getBankAccount() === $this) {
                $scheduleExpense->setBankAccount(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->label;
    }

    public function getScheduleBalance(int $target_month = 0 ): ?float
    {
        $balance = 0;
        foreach ($this->getScheduleExpenses() as $scheduleExpense) {
            $balance += $scheduleExpense->getAmount();
        }
        return $balance;
    }

    // get schedule expenses categories
    public function getScheduleExpensesCategories(): array
    {
        $categories = [];
        foreach ($this->getScheduleExpenses() as $scheduleExpense) {
            $category = $scheduleExpense->getCategory();
            if(!in_array($category,$categories)){
                $categories[] = $category;
            }
        }
        return $categories;
    }

    // get schedule expenses by category
    public function getScheduleExpensesByCategory(Categorie $category): array
    {
        $scheduleExpenses = [];
        foreach ($this->getScheduleExpenses() as $scheduleExpense) {
            if($scheduleExpense->getCategory() == $category){
                $scheduleExpenses[] = $scheduleExpense;
            }
        }
        return $scheduleExpenses;
    }

    // get schedule expenses by category and month
    public function getScheduleExpensesByCategoryAndMonth(Categorie $category, int $month): array
    {
        $scheduleExpenses = [];
        foreach ($this->getScheduleExpenses() as $scheduleExpense) {
            if($scheduleExpense->getCategory() == $category && $scheduleExpense->getMonth() == $month){
                $scheduleExpenses[] = $scheduleExpense;
            }
        }
        return $scheduleExpenses;
    }



}
