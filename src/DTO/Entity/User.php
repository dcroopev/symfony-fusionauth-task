<?php

namespace App\DTO\Entity;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class User
{
    #[Groups(['update', 'delete', 'registration-retrieve', 'registration-existing-user', 'registration-update'])]
    #[Assert\NotBlank(groups: ['update', 'delete', 'registration-retrieve', 'registration-existing-user', 'registration-update'])]
    #[Assert\Uuid]
    private ?string $id;

    #[Groups(['create', 'retrieve', 'update', 'registration-new-user'])]
    #[Assert\NotBlank(groups: ['create', 'retrieve', 'update', 'registration-new-user'])]
    #[Assert\Email]
    private ?string $email;


    #[Groups(['create', 'update', 'registration-new-user'])]
    #[Assert\NotBlank(groups: ['create', 'registration-new-user'])]
    #[Assert\Length(
        min: 8,
        minMessage: 'Password cannot be less than {{ limit }} characters long',
    )]
    private ?string $password;


    #[Groups(['create', 'update', 'registration-new-user'])]
    private ?string $firstName;


    #[Groups(['create', 'update', 'registration-new-user'])]
    private ?string $lastName;

    #[Groups(['create', 'update', 'registration-new-user'])]
    private ?string $birthDate;

    private ?bool $active;

    /**
     * @var Registration[]|null
     */
    private ?array $registrations;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(?string $birthDate): User
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): User
    {
        $this->active = $active;
        return $this;
    }

    public function getRegistrations(): ?array
    {
        return $this->registrations;
    }

    public function setRegistrations(array $registrations): User
    {
        $this->registrations = $registrations;
        return $this;
    }

}