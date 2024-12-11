<?php

namespace App\Domain\Entity\Company;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\CompanyRepositoryDoctrine")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Company\CompanyName", columnPrefix=false)
     */
    private CompanyName $name;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Company\CompanyStartTime", columnPrefix=false)
     */
    private CompanyStartTime $startTime;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Company\CompanyEndTime", columnPrefix=false)
     */
    private CompanyEndTime $endTime;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Company\CompanyWorkingDay", columnPrefix=false)
     */
    private CompanyWorkingDay $availableWorkingDays;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Company\WorkingHours", columnPrefix=false)
     */
    private WorkingHours $numberHoursWorkDay;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\User\User", mappedBy="company")
     */
    private Collection $users;

    public function __construct(
        int $id,
        CompanyName $name,
        CompanyStartTime $startTime,
        CompanyEndTime $endTime,
        CompanyWorkingDay $availableWorkingDays,
        WorkingHours $numberHoursWorkDay,
        Collection $users
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->availableWorkingDays = $availableWorkingDays;
        $this->numberHoursWorkDay = $numberHoursWorkDay;
        $this->users = $users;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function setName(CompanyName $name): void
    {
        $this->name = $name;
    }

    public function startTime(): string
    {
        return $this->startTime->value();
    }

    public function setStartTime(CompanyStartTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function endTime(): string
    {
        return $this->endTime->value();
    }

    public function setEndTime(CompanyEndTime $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function availableWorkingDays(): CompanyWorkingDay
    {
        return $this->availableWorkingDays;
    }

    public function setAvailableWorkingDays(CompanyWorkingDay $availableWorkingDays): void
    {
        $this->availableWorkingDays = $availableWorkingDays;
    }

    public function numberHoursWorkDay(): float
    {
        return $this->numberHoursWorkDay->value();
    }

    public function setNumberHoursWorkDay(WorkingHours $numberHoursWorkDay): void
    {
        $this->numberHoursWorkDay = $numberHoursWorkDay;
    }

    public function users(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }
}