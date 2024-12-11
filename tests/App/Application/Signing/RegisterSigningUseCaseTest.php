<?php

namespace App\Application\Signing;

use App\Domain\Entity\Company\Company;
use App\Domain\Entity\Company\WorkingDay;
use App\Domain\Entity\Company\WorkingHours;
use App\Domain\Entity\User\Role;
use App\Domain\Entity\User\User;
use App\Domain\Exception\Signing\DateRangeException;
use App\Domain\Exception\Signing\NumberHoursExceededException;
use App\Domain\Exception\Signing\StartWorkingHourException;
use App\Domain\Exception\Signing\WorkingDayException;
use App\Domain\Exception\User\InactiveUserException;
use App\Domain\Repository\SigningRepository;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\EndTime;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\StartTime;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class RegisterSigningUseCaseTest extends TestCase
{
    private RegisterSigningService $registerSigningUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerSigningUseCase = new RegisterSigningService(
            $this->createMock(SigningRepository::class)
        );
    }

    public function testUserRegisterSigningFailsIfItIsInactive()
    {
        $this->expectException(InactiveUserException::class);

        $user = new User(
            1,
            'name',
            'lastname',
            new Email('email@email.com'),
            Role::User->value,
            false,
            'pass',
            new Company(
                1,
                new CompanyName('company'),
                new StartTime('08:00'),
                new EndTime('20:00'),
                WorkingDay::AVAILABLE_WORKING_DAYS,
                new WorkingHours(8),
                $this->createMock(Collection::class)
            )
        );

        $this->registerSigningUseCase->execute($user, '2024-12-09 08:00', '');
    }

    public function testUserRegisterSigningIsValid()
    {
        $this->expectNotToPerformAssertions();

        $company = new Company(
            1,
            new Name('company'),
            new StartTime('08:00'),
            new EndTime('20:00'),
            [
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY'
            ],
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );
        $user = new User(
            1,
            'name',
            'lastname',
            new Email('email@email.com'),
            Role::User->value,
            true,
            'pass',
            $company
        );

        $this->registerSigningUseCase->execute($user, '2024-12-09 08:00', '2024-12-09 16:00');
    }

    public function testUserRegisterSigningThrowsDateRangeException()
    {
        $this->expectException(DateRangeException::class);

        $company = new Company(
            1,
            new Name('company'),
            new StartTime('08:00'),
            new EndTime('20:00'),
            WorkingDay::AVAILABLE_WORKING_DAYS,
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            'name',
            'lastname',
            new Email('email@email.com'),
            Role::User->value,
            true,
            'pass',
            $company
        );

        $this->registerSigningUseCase->execute($user, '2024-12-09 09:00', '2024-12-09 08:00');
    }

    public function testUserRegisterSigningThrowsStartWorkingHourException()
    {
        $this->expectException(StartWorkingHourException::class);

        $company = new Company(
            1,
            new Name('company'),
            new StartTime('08:00'),
            new EndTime('20:00'),
            WorkingDay::AVAILABLE_WORKING_DAYS,
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            'name',
            'lastname',
            new Email('email@email.com'),
            Role::User->value,
            true,
            'pass',
            $company
        );

        $this->registerSigningUseCase->execute($user, '2024-12-09 07:00', '2024-12-09 15:00');
    }

    public function testUserRegisterSigningThrowsNumberHoursExceededException()
    {
        $this->expectException(NumberHoursExceededException::class);

        $company = new Company(
            1,
            new Name('company'),
            new StartTime('08:00'),
            new EndTime('20:00'),
            WorkingDay::AVAILABLE_WORKING_DAYS,
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            'name',
            'lastname',
            new Email('email@email.com'),
            Role::User->value,
            true,
            'pass',
            $company
        );

        $this->registerSigningUseCase->execute($user, '2024-12-09 08:30', '2024-12-09 19:00');
    }

    public function testUserRegisterSigningThrowsWorkingDayException()
    {
        $this->expectException(WorkingDayException::class);

        $company = new Company(
            1,
            new Name('company'),
            new StartTime('08:00'),
            new EndTime('20:00'),
            [
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY'
            ],
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            'name',
            'lastname',
            new Email('email@email.com'),
            Role::User->value,
            true,
            'pass',
            $company
        );

        $this->registerSigningUseCase->execute($user, '2024-12-09 08:30', '2024-12-09 16:30');
    }
}
