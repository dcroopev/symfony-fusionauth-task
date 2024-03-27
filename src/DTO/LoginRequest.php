<?php

namespace App\DTO;

class LoginRequest
{
    private ?string $username;

    private ?string $password;

    private ?string $applicationId;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): LoginRequest
    {
        $this->username = $username;
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