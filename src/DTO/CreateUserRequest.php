<?php

namespace App\DTO;

class CreateUserRequest
{
    private User $user;

    private ?string $applicationId;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): CreateUserRequest
    {
        $this->user = $user;
        return $this;
    }

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): CreateUserRequest
    {
        $this->applicationId = $applicationId;
        return $this;
    }

}