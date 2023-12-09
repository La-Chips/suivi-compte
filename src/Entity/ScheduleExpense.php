<?php

namespace App\Entity;

use App\Repository\ScheduleExpenseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleExpenseRepository::class)
 */
class ScheduleExpense
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
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="scheduleExpenses")
     */
    private $category;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=ScheduleRepeat::class, inversedBy="scheduleExpense")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scheduleRepeat;

    /**
     * @ORM\ManyToOne(targetEntity=BankAccount::class, inversedBy="scheduleExpenses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bankAccount;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategory(): ?Categorie
    {
        return $this->category;
    }

    public function setCategory(?Categorie $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getScheduleRepeat(): ?ScheduleRepeat
    {
        return $this->scheduleRepeat;
    }

    public function setScheduleRepeat(?ScheduleRepeat $scheduleRepeat): self
    {
        $this->scheduleRepeat = $scheduleRepeat;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }
}
