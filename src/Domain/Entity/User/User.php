<?php

namespace App\Domain\Entity\User;

use App\Domain\Entity\Company\Company;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\UserRepositoryDoctrine")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({
 * "user" = "User",
 * "manager" = "Manager",
 * })
 * @UniqueEntity(fields={"email"}, message="Este email ya esta registrado.")
 */
class User
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\User\UserFirstName", columnPrefix=false)
     */
    protected UserFirstName $firstName;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\User\UserLastName", columnPrefix=false)
     */
    protected UserLastName $lastName;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\User\UserEmail", columnPrefix=false)
     */
    private UserEmail $email;

    /**
     * @ORM\Column(type="string")
     */
    protected string $role;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\User\UserActive", columnPrefix=false)
     */
    private UserActive $active;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\User\UserPassword", columnPrefix=false)
     */
    private UserPassword $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\Company\Company", inversedBy="users")
     */
    private ?Company $company;

    public function __construct(
        int $id,
        UserFirstName $firstName,
        UserLastName $lastName,
        UserEmail $email,
        string $role,
        UserActive $active,
        UserPassword $password,
        ?Company $company = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->role = $role;
        $this->active = $active;
        $this->password = $password;
        $this->company = $company;
    }

    public function role(): string
    {
        return $this->role;
    }

    public function password(): string
    {
        return $this->password->value();
    }

    public function username(): string
    {
        return $this->email->email();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function firstName(): string
    {
        return $this->firstName->value();
    }

    public function setFirstName(UserFirstName $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function lastName(): string
    {
        return $this->lastName->value();
    }

    public function setLastName(UserLastName $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function email(): string
    {
        return $this->email->email();
    }

    public function setEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    public function active(): bool
    {
        return $this->active->value();
    }

    public function setActive(UserActive $active): void
    {
        $this->active = $active;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }
}
