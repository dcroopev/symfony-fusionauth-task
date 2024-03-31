<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use App\DTO\Entity\User as UserDTO;

class User implements UserInterface
{

    private ?UserDTO $userDto;

    /**
     * @var list<string> The user roles
     */
    private array $roles = [];


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->getUserDto()->getEmail();
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserDto(): ?UserDTO
    {
        return $this->userDto;
    }

    public function setUserDto(?UserDTO $userDto): User
    {
        $this->userDto = $userDto;
        return $this;
    }

}
