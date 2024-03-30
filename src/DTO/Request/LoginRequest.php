<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    private ?string $loginId;

    #[Assert\Length(
        min: 8,
        minMessage: 'Password cannot be less than {{ limit }} characters long',
    )]
    private ?string $password;

    #[Assert\Uuid]
    private ?string $applicationId;

    public function getLoginId(): ?string
    {
        return $this->loginId;
    }

    public function setLoginId(?string $loginId): LoginRequest
    {
        $this->loginId = $loginId;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): LoginRequest
    {
        $this->password = $password;
        return $this;
    }

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): LoginRequest
    {
        $this->applicationId = $applicationId;
        return $this;
    }

}