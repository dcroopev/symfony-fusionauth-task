<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class TokenRequest
{

    #[Groups(['issue-jwt'])]
    #[Assert\Uuid]
    #[Assert\NotBlank(groups: ['issue-jwt'])]
    private ?string $applicationId;


    #[Groups(['issue-jwt', 'refresh-token'])]
    #[Assert\NotBlank(groups: ['refresh-token'])]
    private ?string $refreshToken = null;


    #[Assert\Uuid]
    private ?string $refreshTokenId;

    private ?string $token;

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): TokenRequest
    {
        $this->applicationId = $applicationId;
        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): TokenRequest
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getRefreshTokenId(): ?string
    {
        return $this->refreshTokenId;
    }

    public function setRefreshTokenId(?string $refreshTokenId): TokenRequest
    {
        $this->refreshTokenId = $refreshTokenId;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): TokenRequest
    {
        $this->token = $token;
        return $this;
    }

//    public function getToken(): ?string
//    {
//        return $this->token;
//    }
//
//    public function setToken(?string $token): TokenRequest
//    {
//        $this->token = $token;
//        return $this;
//    }


}