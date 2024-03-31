<?php

namespace App\DTO\Entity;


class Event
{
    const string WEBHOOK_REGISTRATION_EVENT = 'user.registration.create';

    private ?string $id;

    private ?string $type;

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