<?php

namespace App\Entity;

use App\Repository\ScheduleRepeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepeatRepository::class)
 */
class ScheduleRepeat
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
     * @ORM\Column(type="integer")
     */
    private $day_duration;

    /**
     * @ORM\OneToMany(targetEntity=ScheduleExpense::class, mappedBy="scheduleRepeat")
     */
    private $scheduleExpense;

    public function __construct()
    {
        $this->scheduleExpense = new ArrayCollection();
    }

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

    public function getDayDuration(): ?int
    {
        return $this->day_duration;
    }

    public function setDayDuration(int $day_duration): self
    {
        $this->day_duration = $day_duration;

        return $this;
    }

    /**
     * @return Collection<int, ScheduleExpense>
     */
    public function getScheduleExpense(): Collection
    {
        return $this->scheduleExpense;
    }

    public function addScheduleExpense(ScheduleExpense $scheduleExpense): self
    {
        if (!$this->scheduleExpense->contains($scheduleExpense)) {
            $this->scheduleExpense[] = $scheduleExpense;
            $scheduleExpense->setScheduleRepeat($this);
        }

        return $this;
    }

    public function removeScheduleExpense(ScheduleExpense $scheduleExpense): self
    {
        if ($this->scheduleExpense->removeElement($scheduleExpense)) {
            // set the owning side to null (unless already changed)
            if ($scheduleExpense->getScheduleRepeat() === $this) {
                $scheduleExpense->setScheduleRepeat(null);
            }
        }

        return $this;
    }
}
