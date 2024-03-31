<?php

namespace App\DTO\Request;

use App\DTO\Entity\Event;

class EventRequest
{
    private ?Event $event;

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): EventRequest
    {
        $this->event = $event;
        return $this;
    }

}