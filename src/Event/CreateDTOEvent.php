<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CreateDTOEvent extends Event
{
    public const string NAME = 'create.dto';

    public function __construct(private object $dto)
    {
    }

    public function getDto(): object
    {
        return $this->dto;
    }

}