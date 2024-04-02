<?php

namespace App\DTO\Response;

use App\DTO\Entity\User;

class UserResponse
{
    private ?User $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): UserResponse
    {
        $this->user = $user;
        return $this;
    }

}