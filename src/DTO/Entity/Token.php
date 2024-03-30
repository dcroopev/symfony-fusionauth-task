<?php

namespace App\DTO\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Token
{
    private ?string $token;
    private ?int $tokenExpirationInstant;

    #[Assert\Valid]
    private ?User $user;

    #[Assert\Valid]
    private ?Registration $registration;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): Token
    {
        $this->token = $token;
        return $this;
    }

    public function getTokenExpirationInstant(): ?int
    {
        return $this->tokenExpirationInstant;
    }

    public function setTokenExpirationInstant(?int $tokenExpirationInstant): Token
    {
        $this->tokenExpirationInstant = $tokenExpirationInstant;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Token
    {
        $this->user = $user;
        return $this;
    }

    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }

    public function setRegistration(?Registration $registration): Token
    {
        $this->registration = $registration;
        return $this;
    }

}