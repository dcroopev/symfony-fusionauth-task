<?php

namespace App\WebhookEventHandler;

use App\DTO\Request\EventRequest;

interface WebhookEventHandlerInterface
{
    public function handle(EventRequest $eventRequest): mixed;
}