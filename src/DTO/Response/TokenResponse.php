<?php

namespace App\DTO\Response;

use App\DTO\Entity\{Registration, User};
use Symfony\Component\Serializer\Attribute\Groups;

class TokenResponse
{
    #[Groups(['token', 'refresh-token'])]
    private ?string $refreshToken;

    #[Groups(['refresh-token'])]
    private ?string $refreshTokenId;

    #[Groups(['token', 'refresh-token'])]
    private ?string $token;
    private ?int $tokenExpirationInstant;

    private ?User $user;

    private ?Registration $registration;


    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): TokenResponse
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getRefreshTokenId(): ?string
    {
        return $this->refreshTokenId;
    }

    public function setRefreshTokenId(?string $refreshTokenId): TokenResponse
    {
        $this->refreshTokenId = $refreshTokenId;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): TokenResponse
    {
        $this->token = $token;
        return $this;
    }

    public function getTokenExpirationInstant(): ?int
    {
        return $this->tokenExpirationInstant;
    }

    public function setTokenExpirationInstant(?int $tokenExpirationInstant): TokenResponse
    {
        $this->tokenExpirationInstant = $tokenExpirationInstant;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): TokenResponse
    {
        $this->user = $user;
        return $this;
    }

    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }

    public function setRegistration(?Registration $registration): TokenResponse
    {
        $this->registration = $registration;
        return $this;
    }

}