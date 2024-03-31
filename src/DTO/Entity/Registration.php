<?php

namespace App\DTO\Entity;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class Registration
{
    #[Groups(['registration-retrieve-response'])]
    private ?string $id;

    #[Groups([
        'registration-retrieve',
        'registration-new-user',
        'registration-existing-user',
        'registration-update',
        'registration-retrieve-response'
    ])]
    #[Assert\NotBlank(groups: [
        'registration-retrieve',
        'registration-new-user',
        'registration-existing-user',
        'registration-update'
    ])]
    #[Assert\Uuid(groups: [
        'registration-retrieve',
        'registration-new-user',
        'registration-existing-user',
        'registration-update',
        'registration-retrieve-response'
    ])]
    private ?string $applicationId;


    #[Groups([
        'registration-new-user',
        'registration-existing-user',
        'registration-update',
        'registration-retrieve-response'
    ])]
    private ?string $username;

    #[Groups([
        'registration-new-user',
        'registration-existing-user',
        'registration-update',
        'registration-retrieve-response'
    ])]
    private ?array $roles;

    #[Groups([
        'registration-new-user',
        'registration-existing-user',
        'registration-update',
        'registration-retrieve-response'
    ])]
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