<?php

namespace App\Domain\Entity\Signing;

use App\Domain\Entity\AggregateRoot;
use App\Domain\Entity\User\User;
use App\Domain\Event\Signing\UserSigningRegistered;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\SigningRepositoryDoctrine")
 */
class Signing extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\User\User")
     */
    private User $user;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Signing\SigningStart", columnPrefix=false)
     */
    private SigningStart $start;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Signing\SigningEnd", columnPrefix=false)
     */
    private SigningEnd $end;

    /**
     * @ORM\Embedded(class="App\Domain\Entity\Signing\SigningCreatedAt", columnPrefix=false)
     */
    private SigningCreatedAt $createdAt;

    private function __construct(
        ?int $id,
        User $user,
        SigningStart $start,
        SigningEnd $end
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->start = $start;
        $this->end = $end;
        $this->createdAt = new SigningCreatedAt(new \DateTime());

        $this->recordEvent(new UserSigningRegistered($user->id()));
    }

    public static function create(
        User $user,
        SigningStart $start,
        SigningEnd $end
    ): Signing {
        return new self(null, $user, $start, $end);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function start(): SigningStart
    {
        return $this->start;
    }

    public function setStart(SigningStart $dateTime): void
    {
        $this->start = $dateTime;
    }

    public function end(): SigningEnd
    {
        return $this->end;
    }

    public function setEnd(SigningEnd $end): void
    {
        $this->end = $end;
    }

    public function createdAt(): SigningCreatedAt
    {
        return $this->createdAt;
    }

    public function setCreatedAt(SigningCreatedAt $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}