<?php

namespace App\Controller;

use App\DTO\Entity\Token;
use App\DTO\Request\RegistrationRequest;
use App\Filter\DtoSerializerFilter;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private DTOSerializer $dtoSerializer,
        private FusionAuthClient $client,
        private FusionAuthResponseHandler $fusionAuthResponseHandler,
        private DtoSerializerFilter $dtoSerializerFilter,
    ) {
    }


    #[Route('/api/user/registration', name: 'registration-retrieve', methods: 'GET')]
    public function retrieveRegistration(Request $request): JsonResponse
    {
        $retrieveRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            Token::class,
            'json'
        );
        $response = $this->client->retrieveRegistration(
            $retrieveRequest->getUser()->getId(),
            $retrieveRequest->getRegistration()->getApplicationId()
        );
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, RegistrationRequest::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user/registration', name: 'registration-new-user', methods: 'POST')]
    public function registerNewUser(Request $request): JsonResponse
    {
        $registrationRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json'
        );
        $registrationRequestArray = $this->dtoSerializer->toArray($registrationRequest);

        $response = $this->client->register(null, $registrationRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user/registration/{userId}', name: 'registration-existing-user', methods: 'POST')]
    public function registerExistingUser(Request $request, string $userId = null): JsonResponse
    {
        $registrationRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json'
        );
        $registrationRequestArray = $this->dtoSerializer->toArray($registrationRequest);

        $response = $this->client->register($userId, $registrationRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user/registration', name: 'registration-update', methods: 'PUT')]
    public function updateRegistration(Request $request): JsonResponse
    {
        $updateRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json'
        );
        $updateRequestArray = $this->dtoSerializer->toArray($updateRequest);

        $response = $this->client->updateRegistration($updateRequest->getUser()->getId(), $updateRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user/registration', name: 'registration-delete', methods: 'DELETE')]
    public function unregister(Request $request): JsonResponse
    {
        $deleteRequest = $this->dtoSerializer->deserialize($request->getContent(), Token::class, 'json');

        $response = $this->client->deleteRegistration(
            $deleteRequest->getUser()->getId(),
            $deleteRequest->getRegistration()->getApplicationId()
        );
        $response = $this->fusionAuthResponseHandler->handle($response);

        $statusCode = $response->status;

        return new JsonResponse(status: $statusCode);
    }

}