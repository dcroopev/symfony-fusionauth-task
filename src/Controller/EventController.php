<?php

namespace App\Controller;

use App\DTO\Entity\Event;
use App\DTO\Request\EmailTemplateSearchRequest;
use App\DTO\Request\EventRequest;
use App\Service\Exception\ServiceException;
use App\Service\Exception\ServiceExceptionData;
use App\Service\Serializer\DTOSerializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class EventController extends AbstractFusionAuthApiController
{
    #[OA\Tag(name: 'Webhook')]
    #[OA\RequestBody(
        description: "Takes an event object from FusionAuth. Only `user.registration.create` event is supported.
         Then the action searches for a specific template with name `User Registration`, again sadly hardcoded, and sends it to the user. Smtp configuration in the tenant is requried. ",
        content: new OA\JsonContent(oneOf: [
            new OA\Schema(ref: new Model(type: EventRequest::class))
        ]))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful and ',
        content: new OA\JsonContent(
            description: "asdasd",
//            type: 'array',
            example: [
                "anonymousResults" => [],
                "results" => [
                    "52ff66b3-6ae1-46a8-a0d7-7d9536e35a8d" => [
                        "parseErrors" => [],
                        "renderErrors" => []
                    ]
                ]
            ],
//            items: new OA\Items(ref: new Model(type: SearchResponse::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error, Bad Request or `User Registration` template not found')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Route('/api/webhook/event', name: 'webhook-event', methods: 'POST', format: 'json')]
    public function webhookEvent(Request $request, DTOSerializer $serializer): JsonResponse
    {
        $eventRequest = $serializer->deserialize($request->getContent(), EventRequest::class, 'json');

        //TODO for now it will do but needs refactoring - map webhook events with corresponding actions
        if ($eventRequest->getEvent()->getType() !== Event::WEBHOOK_REGISTRATION_EVENT) {
            $exceptionData = new ServiceExceptionData(422, "Event not handled.");
            throw new ServiceException($exceptionData);
        }

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