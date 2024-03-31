<?php

namespace App\Controller;

use App\DTO\Entity\Event;
use App\DTO\Request\EmailTemplateSearchRequest;
use App\DTO\Request\EventRequest;
use App\Service\Serializer\DTOSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractFusionAuthApiController
{

    #[Route('/api/webhook/event', name: 'webhook-event', methods: 'POST', format: 'json')]
    public function webhookEvent(Request $request, DTOSerializer $serializer): JsonResponse
    {
        $eventRequest = $serializer->deserialize($request->getContent(), EventRequest::class, 'json');


        //TODO for now it will do but needs refactoring - map webhook events with corresponding actions
        if ($eventRequest->getEvent()->getType() !== Event::WEBHOOK_REGISTRATION_EVENT) {
            return new JsonResponse(data: 'Event not handled', status: 422);
        }

        $templateId = $this->fetchTemplateId('User Registration');

        $emailRequest = [
            "userIds" => [
                $eventRequest->getEvent()->getUser()->getId()
            ]
        ];

        $response = $this->client->sendEmail($templateId, $emailRequest);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        return new JsonResponse(data: $responseData, status: $statusCode);
    }

    private function fetchTemplateId(string $templateName): ?string
    {
        $templateSearch = new EmailTemplateSearchRequest($templateName);

        $templateResponse = $this->client->searchEmailTemplates($templateSearch->toArray());

        if (!$templateResponse->wasSuccessful()) {
            return null;
        }

        return ($templateResponse->successResponse->emailTemplates[0]->id ?? null);
    }

}