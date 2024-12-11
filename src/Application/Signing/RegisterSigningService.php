<?php

namespace App\Application\Signing;

use App\Domain\Entity\Company\Company;
use App\Domain\Entity\Signing\Signing;
use App\Domain\Entity\Signing\SigningEnd;
use App\Domain\Entity\Signing\SigningStart;
use App\Domain\Entity\User\User;
use App\Domain\Exception\Signing\DateRangeException;
use App\Domain\Exception\Signing\EndWorkingHourException;
use App\Domain\Exception\Signing\NumberHoursExceededException;
use App\Domain\Exception\Signing\StartWorkingHourException;
use App\Domain\Exception\Signing\WorkingDayException;
use App\Domain\Exception\User\InactiveUserException;
use App\Domain\Repository\SigningRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterSigningService
{
    private SigningRepository $signingRepository;
    private ?MessageBusInterface $messageBus;

    public function __construct(SigningRepository $signingRepository, ?MessageBusInterface $messageBus = null)
    {
        $this->signingRepository = $signingRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws NumberHoursExceededException
     * @throws DateRangeException
     * @throws \Exception
     */
    public function execute(User $user, string $start, string $end): void
    {
        if (!$user->active()) {
            throw new InactiveUserException('Usuario INACTIVO');
        }

        $start = new SigningStart(new \DateTimeImmutable($start));
        if (empty($end)) {
            $end = new SigningEnd(
                $start->value()->add(new \DateInterval('PT'.$user->company()->numberHoursWorkDay().'H'))
            );
        } else {
            $end = new SigningEnd(new \DateTimeImmutable($end));
        }

        $this->validateHours($user->company(), $start, $end);

        $signing = Signing::create($user, $start, $end);

        $this->signingRepository->saveSigning($signing);

        $this->dispatchEvents($signing);
    }

    /**
     * @throws DateRangeException
     * @throws EndWorkingHourException
     * @throws NumberHoursExceededException
     * @throws StartWorkingHourException
     * @throws WorkingDayException
     */
    private function validateHours(Company $company, SigningStart $start, SigningEnd $end): void
    {
        if ($start > $end) {
            throw new DateRangeException('La hora de comienzo es posterior a la hora de fin.');
        }

        if ($end <= $start) {
            throw new DateRangeException('La hora de fin es anterior a la hora de inicio.');
        }

        if (!empty($company->startTime()) && !empty($company->endTime())) {
            if (new \DateTimeImmutable($start->value()->format('H:i:s')) < new \DateTimeImmutable($company->startTime())) {
                throw new StartWorkingHourException('Hora de inicio anterior a la estipulada.');
            }

            if (new \DateTimeImmutable($end->value()->format('H:i:s')) > new \DateTimeImmutable($company->endTime())) {
                throw new EndWorkingHourException('Hora de fin posterior a la estipulada.');
            }

            $maxNumberHoursToWork = $company->numberHoursWorkDay();
            $hours = $end->value()->diff($start->value())->h;
            if ($hours > $maxNumberHoursToWork) {
                throw new NumberHoursExceededException('Numero de horas trabajadas superior al estipulado');
            }

            if ($hours < $maxNumberHoursToWork) {
                throw new NumberHoursExceededException('Numero de horas trabajadas inferior al estipulado');
            }
        }

        if (!empty($company->availableWorkingDays())) {
            $this->validateWorkingDay(strtoupper($start->value()->format('l')), $company);
        }
    }

    /**
     * @throws WorkingDayException
     */
    private function validateWorkingDay(string $day, Company $company): void
    {
        $availableWorkingDays = $company->availableWorkingDays();

        if (!in_array($day, $availableWorkingDays)) {
            throw new WorkingDayException('El dia registrado no esta permitido.');
        }
    }

    /**
     * @param Signing $signing
     *
     * @return void
     */
    private function dispatchEvents(Signing $signing): void
    {
        if (null !== $this->messageBus) {
            foreach ($signing->pullEvents() as $event) {
                $this->messageBus->dispatch(
                    $event
                );
            }
        }
    }
}