<?php

namespace App\DTO\Request;

use App\DTO\Entity\Event;
use Symfony\Component\Validator\Constraints as Assert;

class EventRequest
{

    #[Assert\Valid]
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