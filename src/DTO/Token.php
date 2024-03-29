<?php

namespace App\DTO;

class Token
{
    private ?string $token;
    private ?int $tokenExpirationInstant;
    private ?User $user;

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

}