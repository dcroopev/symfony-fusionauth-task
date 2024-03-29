<?php

namespace App\DTO;

class RegistrationRequest
{
    private ?Registration $registration;

    private ?User $user;

    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }

    public function setRegistration(?Registration $registration): RegistrationRequest
    {
        $this->registration = $registration;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): RegistrationRequest
    {
        $this->user = $user;
        return $this;
    }

}