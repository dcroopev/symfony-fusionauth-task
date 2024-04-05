<?php

namespace App\Controller;

use App\DTO\Request\EventRequest;
use App\WebhookEventBus;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class EventController extends AbstractController
{
    #[OA\Tag(name: 'Webhook')]
    #[OA\RequestBody(
        description: "Takes an event object from FusionAuth. 
         Only `user.registration.create` event is supported. It is bound to sending an email `User Registration which is present in the kickstart configuration.",
        content: new OA\JsonContent(oneOf: [
            new OA\Schema(ref: new Model(type: EventRequest::class))
        ]))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
    )]
    #[OA\Response(
        response: '202',
        description: 'In case of email sending',
        content: new OA\JsonContent(
            example: [
                "anonymousResults" => [],
                "results" => [
                    "52ff66b3-6ae1-46a8-a0d7-7d9536e35a8d" => [
                        "parseErrors" => [],
                        "renderErrors" => []
                    ]
                ]
            ],
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error, Bad Request or email template not found')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Route('/api/webhook/event', name: 'webhook-event', methods: 'POST', format: 'json')]
    public function webhookEvent(WebhookEventBus $webhookEventBus): JsonResponse
    {
        $eventResponse = $webhookEventBus->handle();
        if ($eventResponse instanceof JsonResponse) {
            return $eventResponse;
        }

        return new JsonResponse($eventResponse, status: 200);
    }


}