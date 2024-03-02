<?php

namespace App\Entity;

use DateInterval;
use App\Entity\Ligne;
use App\Entity\Categorie;
use App\Entity\ScheduleExpense;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Query\Mysql\Date;
use App\Repository\BankAccountRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $initial_value;

    /**
     * @ORM\OneToMany(targetEntity=Ligne::class, mappedBy="bankAccount")
     */
    private $accountantEntry;

    public function __construct()
    {
        $this->scheduleExpenses = new ArrayCollection();
        $this->accountantEntry = new ArrayCollection();
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

    public function getScheduleBalance(int $target_month = 0,array $expenses = [] ): ?float
    {
        $balance = 0;
        $date = new \DateTime('now');
        $interval = DateInterval::createFromDateString(strval($target_month).' month');
        $date->add($interval);


        if(empty($expenses)){
            $expenses = $this->getScheduleExpenses();
        }

        foreach ($expenses as $scheduleExpense) {
            $balance += $scheduleExpense->getSumByMonth($date);
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
            if($scheduleExpense->getCategory()->getId() == $category->getId()){
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
            if($scheduleExpense->getCategory()->getId() == $category->getId() && $scheduleExpense->haveToPay($month) ){
                $scheduleExpenses[] = $scheduleExpense;
            }
        }
        return $scheduleExpenses;
    }

    // get schedule expenses balance by category and month

    public function getScheduleExpensesBalanceByCategoryAndMonth(Categorie $category, int $month): float
    {

        $expenses = $this->getScheduleExpensesByCategoryAndMonth($category,$month);
        if(empty($expenses))
            return 0;
        
        return $this->getScheduleBalance($month,$expenses);
    }

    public function getInitialValue(): ?float
    {
        return $this->initial_value;
    }

    public function setInitialValue(?float $initial_value): self
    {
        $this->initial_value = $initial_value;

        return $this;
    }

    /**
     * @return Collection<int, Ligne>
     */
    public function getAccountantEntry(?string $sort = null , string $order = 'ASC'): Collection
    {
        if($sort != null) {
            return $this->accountantEntry->matching(
                Criteria::create()->orderBy([$sort => $order])
            );
        }

        return $this->accountantEntry;
    }



    public function addAccountantEntry(Ligne $accountantEntry): self
    {
        if (!$this->accountantEntry->contains($accountantEntry)) {
            $this->accountantEntry[] = $accountantEntry;
            $accountantEntry->setBankAccount($this);
        }

        return $this;
    }

    public function removeAccountantEntry(Ligne $accountantEntry): self
    {
        if ($this->accountantEntry->removeElement($accountantEntry)) {
            // set the owning side to null (unless already changed)
            if ($accountantEntry->getBankAccount() === $this) {
                $accountantEntry->setBankAccount(null);
            }
        }

        return $this;
    }

    public function getCurrentValue() : float
    {
        $value = $this->initial_value;

        foreach($this->accountantEntry as $entry){
            $value += $entry->getAmount();
        }
        return round($value,2);
    }
   

}
