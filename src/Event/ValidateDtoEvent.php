<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ValidateDtoEvent extends Event
{
    public const string NAME = 'validate.dto';

    public function __construct(private object $dto,  private string|array|null $groups = null)
    {
    }

    public function getDto(): object
    {
        return $this->dto;
    }

    public function getGroups(): array|string|null
    {
        return $this->groups;
    }

    public function setGroups(array|string|null $groups): ValidateDtoEvent
    {
        $this->groups = $groups;
        return $this;
    }

}