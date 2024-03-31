<?php

namespace App\DTO\Request;

use App\DTO\Entity\Registration;
use App\DTO\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class RegistrationRequest
{

    #[Groups(['registration-new-user', 'registration-existing-user', 'registration-update', 'registration-retrieve', 'registration-retrieve-response'])]
    #[Assert\NotBlank(groups: ['registration-new-user', 'registration-existing-user', 'registration-update', 'registration-retrieve'])]
    #[Assert\Valid]
    private ?Registration $registration;

    #[Groups(['registration-new-user', 'registration-existing-user', 'registration-update', 'registration-retrieve'])]
    #[Assert\NotBlank(groups: ['registration-new-user', 'registration-existing-user', 'registration-update', 'registration-retrieve'])]
    #[Assert\Valid]
    private ?User $user;

    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }

    public function setRegistration(?Registration $registration): RegistrationRequest
    {
        $this->registration = $registration;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): RegistrationRequest
    {
        $this->user = $user;
        return $this;
    }

}