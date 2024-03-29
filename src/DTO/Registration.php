<?php

namespace App\DTO;

class Registration
{
    private ?string $id;

    private ?string $applicationId;

    private ?string $username;

    private ?array $roles;

    private ?array $data;

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): Registration
    {
        $this->username = $username;
        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): Registration
    {
        $this->data = $data;
        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): Registration
    {
        $this->roles = $roles;
        return $this;
    }

}