<?php

namespace App\Entity;

use DateTime;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ScheduleExpenseRepository;


enum ScheduleRepeatType: int
{
    case DAILY = 0;
    case MONTHLY = -2;
    case YEARLY = -3;
}

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
     * @ORM\JoinColumn(nullable=true)
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

    /**
     * @ORM\Column(type="boolean")
     */
    private $repeatable;

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

    public function getRepeatable(): ?bool
    {
        return $this->repeatable;
    }

    public function setRepeatable(bool $repeatable): self
    {
        $this->repeatable = $repeatable;

        return $this;
    }

    public function __toString()
    {
        return $this->label;
    }

    public function getSumByMonth(\DateTime $date): ?float
    {
        $month_id = $date->format('m');

        if($this->start_date > $date && $this->start_date->format('m') != $month_id)
            return 0;

        if($this->getRepeatable())
            return $this->amount * $this->getRepeatCountByMonth($month_id);
        else
            return $month_id == $this->getStartDate()->format('m') &&
                $this->getStartDate()->format('d') >= date('d')
         ? $this->amount : null;
    }

    private function getRepeatCountByMonth(int $month_id): int
    {
        $repeat = $this->getScheduleRepeat();
        
            $repeat_value = $repeat->getDayDuration();
            switch ($repeat_value) {
                case ScheduleRepeatType::DAILY->value:
                    return $this->dayInCurrentMonth($month_id);

                case ScheduleRepeatType::MONTHLY->value:
                    return $this->dateIsOverInMonth($month_id) ? 0 : 1;

                case ScheduleRepeatType::YEARLY->value:
                    return $month_id == $this->getStartDate()->format('m') ? 1 : 0;
            }
        


       return 0;
    }



    private function dateIsOverInMonth(int $month_id): bool
    {
        $current_date = new \DateTime('now');
        if($month_id == $current_date->format('m')) {
            return $this->getStartDate()->format('d') < $current_date->format('d');
        }
        return false;
    }

    private function dayInCurrentMonth(int $month_id):int 
    {
        if($month_id == date('m'))
            return cal_days_in_month(CAL_GREGORIAN, $month_id, $this->getStartDate()->format('Y')) - date('d');
        return cal_days_in_month(CAL_GREGORIAN, $month_id, $this->getStartDate()->format('Y'));
    }

    public function haveToPay(int $month_id): bool
    {
        $date = new \DateTime('now');
        $interval = DateInterval::createFromDateString(strval($month_id).' month');
        $date->add($interval);
        return $this->getSumByMonth($date) != null;
    }
    

}
