<?php

namespace App\DTO\Entity;


use Symfony\Component\Validator\Constraints as Assert;

class Event
{

    private ?string $id;

    #[Assert\NotBlank(message: "No event has been specified.")]
    private ?string $type = null;

    private ?Registration $registration;

    private ?User $user;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Event
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Event
    {
        $this->type = $type;
        return $this;
    }

    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }

    public function setRegistration(?Registration $registration): Event
    {
        $this->registration = $registration;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Event
    {
        $this->user = $user;
        return $this;
    }

}