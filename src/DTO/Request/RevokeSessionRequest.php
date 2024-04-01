<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RevokeSessionRequest
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $applicationId;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $userId;

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): RevokeSessionRequest
    {
        $this->applicationId = $applicationId;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): RevokeSessionRequest
    {
        $this->userId = $userId;
        return $this;
    }

}