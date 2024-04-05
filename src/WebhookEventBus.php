<?php

namespace App;

use App\DTO\Request\EventRequest;
use App\Service\Exception\ServiceException;
use App\Service\Exception\ServiceExceptionData;
use App\Service\Serializer\DTOSerializer;
use App\WebhookEventHandler\WebhookEventHandlerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class WebhookEventBus implements ServiceSubscriberInterface
{

    public function __construct(
        private ContainerInterface $serviceLocator,
        private DTOSerializer $dtoSerializer
    ) {
    }

    public static function getSubscribedServices(): array
    {
        return [
            'user.registration.create' => 'App\WebhookEventHandler\UserRegistrationEventHandler',
        ];
    }

    public function handle(): mixed
    {
        $eventRequest = $this->extractEventData();
        $event = $eventRequest->getEvent()->getType();

        if ($event && $this->serviceLocator->has($event)) {
            /* @var WebhookEventHandlerInterface $handler */
            $handler = $this->serviceLocator->get($event);

            return $handler->handle($eventRequest);
        } else {
            $exceptionData = new ServiceExceptionData(422, "Event not handled.");
            throw new ServiceException($exceptionData);
        }
    }

    private function extractEventData(): EventRequest
    {
        $httpRequest = Request::createFromGlobals();

        return $this->dtoSerializer->deserialize($httpRequest->getContent(), EventRequest::class, 'json');
    }

}