<?php

namespace App\Application\Signing;

use App\Domain\Entity\Company\Company;
use App\Domain\Entity\Company\CompanyEndTime;
use App\Domain\Entity\Company\CompanyName;
use App\Domain\Entity\Company\CompanyStartTime;
use App\Domain\Entity\Company\CompanyWorkingDay;
use App\Domain\Entity\Company\WorkingHours;
use App\Domain\Entity\User\Role;
use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserActive;
use App\Domain\Entity\User\UserEmail;
use App\Domain\Entity\User\UserFirstName;
use App\Domain\Entity\User\UserLastName;
use App\Domain\Entity\User\UserPassword;
use App\Domain\Exception\Signing\DateRangeException;
use App\Domain\Exception\Signing\NumberHoursExceededException;
use App\Domain\Exception\Signing\StartWorkingHourException;
use App\Domain\Exception\Signing\WorkingDayException;
use App\Domain\Exception\User\InactiveUserException;
use App\Domain\Repository\SigningRepository;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class RegisterSigningServiceTest extends TestCase
{
    private RegisterSigningService $registerSigningService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerSigningService = new RegisterSigningService(
            $this->createMock(SigningRepository::class)
        );
    }

    public function testUserRegisterSigningFailsIfItIsInactive()
    {
        $this->expectException(InactiveUserException::class);

        $company = new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
            ]),
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(false),
            new UserPassword('pass'),
            $company
        );

        $start = '2024-12-09 08:00';
        $end = '';
        $this->registerSigningService->execute($user, $start, $end);
    }

    public function testUserRegisterSigningIsValid()
    {
        $this->expectNotToPerformAssertions();

        $company = new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
            ]),
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(true),
            new UserPassword('pass'),
            $company
        );

        $start = '2024-12-09 08:00';
        $end = '2024-12-09 16:00';
        $this->registerSigningService->execute($user, $start, $end);
    }

    public function testUserRegisterSigningThrowsDateRangeException()
    {
        $this->expectException(DateRangeException::class);

        $company = new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
            ]),
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(true),
            new UserPassword('pass'),
            $company
        );

        $start = '2024-12-09 09:00';
        $end = '2024-12-09 08:00';
        $this->registerSigningService->execute($user, $start, $end);
    }

    public function testUserRegisterSigningThrowsStartWorkingHourException()
    {
        $this->expectException(StartWorkingHourException::class);

        $company = new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
            ]),
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(true),
            new UserPassword('pass'),
            $company
        );

        $start = '2024-12-09 07:00';
        $end = '2024-12-09 15:00';
        $this->registerSigningService->execute($user, $start, $end);
    }

    public function testUserRegisterSigningThrowsNumberHoursExceededException()
    {
        $this->expectException(NumberHoursExceededException::class);

        $company = new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
            ]),
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(true),
            new UserPassword('pass'),
            $company
        );

        $start = '2024-12-09 08:30';
        $end = '2024-12-09 19:00';
        $this->registerSigningService->execute($user, $start, $end);
    }

    public function testUserRegisterSigningThrowsWorkingDayException()
    {
        $this->expectException(WorkingDayException::class);

        $company = new Company(
            1,
            new CompanyName('company'),
            new CompanyStartTime('08:00'),
            new CompanyEndTime('20:00'),
            new CompanyWorkingDay([
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY'
            ]),
            new WorkingHours(8),
            $this->createMock(Collection::class)
        );

        $user = new User(
            1,
            new UserFirstName('name'),
            new UserLastName('lastname'),
            new UserEmail('email@email.com'),
            Role::User->value,
            new UserActive(true),
            new UserPassword('pass'),
            $company
        );

        $start = '2024-12-09 08:30';
        $end = '2024-12-09 16:30';
        $this->registerSigningService->execute($user, $start, $end);
    }
}
