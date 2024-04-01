<?php

namespace App\DTO\Response;

use App\DTO\Entity\{RefreshToken};

class RefreshTokenResponse
{
    private ?RefreshToken $refreshToken;

    public function getRefreshToken(): ?RefreshToken
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?RefreshToken $refreshToken): RefreshTokenResponse
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

}