<?php

namespace App\DTO;

class Registration
{
    private ?string $id;

    private ?string $applicationId;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Registration
    {
        $this->id = $id;
        return $this;
    }

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): Registration
    {
        $this->applicationId = $applicationId;
        return $this;
    }

}