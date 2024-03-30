<?php

namespace App\DTO\Request;

use App\DTO\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest
{
    #[Assert\Valid]
    private ?User $user;

    #[Assert\Uuid]
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