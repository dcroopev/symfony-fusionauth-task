<?php

namespace App\DTO\Response;

use App\DTO\Entity\{Registration, User};

class Token
{
    private ?string $refreshToken;
    private ?string $refreshTokenId;

    private ?string $token;
    private ?int $tokenExpirationInstant;

    private ?User $user;

    private ?Registration $registration;


    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): Token
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getRefreshTokenId(): ?string
    {
        return $this->refreshTokenId;
    }

    public function setRefreshTokenId(?string $refreshTokenId): Token
    {
        $this->refreshTokenId = $refreshTokenId;
        return $this;
    }

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