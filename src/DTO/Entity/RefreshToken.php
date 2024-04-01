<?php

namespace App\DTO\Entity;

class RefreshToken
{
    private ?string $applicationId;
    private ?string $id;
    private ?array $metaData;
    private ?string $token;
    private ?string $userId;

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): RefreshToken
    {
        $this->applicationId = $applicationId;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): RefreshToken
    {
        $this->id = $id;
        return $this;
    }

    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    public function setMetaData(?array $metaData): RefreshToken
    {
        $this->metaData = $metaData;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): RefreshToken
    {
        $this->token = $token;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): RefreshToken
    {
        $this->userId = $userId;
        return $this;
    }


}