<?php

namespace App\WebhookEventHandler;

use App\DTO\Request\EmailTemplateSearchRequest;
use App\DTO\Request\EventRequest;
use App\Service\Exception\ServiceException;
use App\Service\Exception\ServiceExceptionData;
use App\Service\FusionAuthResponseHandler;
use FusionAuth\FusionAuthClient;

class UserRegistrationEventHandler implements WebhookEventHandlerInterface
{

    public function __construct(
        private FusionAuthClient $client,
        private FusionAuthResponseHandler $fusionAuthResponseHandler
    ) {
    }

    public function handle(EventRequest $eventRequest): mixed
    {
        $templateId = $this->fetchTemplateId('User Registration');

        $emailRequest = [
            "userIds" => [
                $eventRequest->getEvent()->getUser()->getId()
            ]
        ];

        $response = $this->client->sendEmail($templateId, $emailRequest);

        return $this->fusionAuthResponseHandler->createJsonResponse($response);
    }

    private function fetchTemplateId(string $templateName): ?string
    {
        $templateSearch = new EmailTemplateSearchRequest($templateName);

        $templateResponse = $this->client->searchEmailTemplates($templateSearch->toArray());

        if (!$templateResponse->wasSuccessful() || empty($templateResponse->successResponse->emailTemplates)) {
            $exceptionData = new ServiceExceptionData(400, "'$templateName' template is not found");
            throw new ServiceException($exceptionData);
        }

        return ($templateResponse->successResponse->emailTemplates[0]->id ?? null);
    }

}