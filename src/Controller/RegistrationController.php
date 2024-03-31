<?php

namespace App\Controller;

use App\DTO\Request\RegistrationRequest;
use App\DTO\Response\Token;
use Nelmio\ApiDocBundle\Annotation\{Model, Security};
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractFusionAuthApiController
{

    #[OA\Tag(name: 'Registration')]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: RegistrationRequest::class, groups: ['registration-retrieve'])
        ))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(
                    type: RegistrationRequest::class,
                    groups: ['registration-retrieve-response']
                )
            )
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'Registration/User not found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user/registration', name: 'registration-retrieve', methods: 'GET')]
    public function retrieveRegistration(Request $request): JsonResponse
    {
        $retrieveRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json',
            validationGroups: ['registration-retrieve']
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


    #[OA\Tag(name: 'Registration')]
    #[OA\RequestBody(
        content: new OA\JsonContent(oneOf: [
            new OA\Schema(ref: new Model(type: RegistrationRequest::class, groups: ['registration-new-user'])),
            new OA\Schema(ref: new Model(type: RegistrationRequest::class, groups: ['registration-existing-user']))
        ]))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Token::class)) //TODO check token/refreshToken
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: '[registration-existing-user] The user specified by Id in the request parameter does not exist ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user/registration', name: 'registration-user', methods: 'POST')]
    public function registerUser(Request $request): JsonResponse
    {
        $registrationRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json',
            validate: false
        );

        $userId = $registrationRequest->getUser()->getId();

        if ($userId) {
            $registrationRequest = $this->dtoSerializer->validateDto(
                $registrationRequest,
                'registration-existing-user'
            );
            $registrationRequest->setUser(null);
        } else {
            $registrationRequest = $this->dtoSerializer->validateDto($registrationRequest, 'registration-new-user');
        }

        $registrationRequestArray = $this->dtoSerializer->toArray($registrationRequest);

        $response = $this->client->register($userId, $registrationRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[OA\Tag(name: 'Registration')]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: RegistrationRequest::class, groups: ['registration-update'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(
                    type: RegistrationRequest::class,
                    groups: ['registration-retrieve-response']
                )
            )
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'Registration/User not found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user/registration', name: 'registration-update', methods: 'PUT')]
    public function updateRegistration(Request $request): JsonResponse
    {
        $updateRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json',
            validationGroups: ['registration-update']
        );
        $updateRequestArray = $this->dtoSerializer->toArray($updateRequest);
        $response = $this->client->updateRegistration($updateRequest->getUser()->getId(), $updateRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, RegistrationRequest::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }

    #[OA\Tag(name: 'Registration')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: RegistrationRequest::class, groups: ['registration-retrieve'])
        ))
    ]
    #[OA\Response(response: '200', description: 'The request was successful')]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'Registration/User not found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user/registration', name: 'registration-delete', methods: 'DELETE')]
    public function unregister(Request $request): JsonResponse
    {
        $deleteRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RegistrationRequest::class,
            'json',
            validationGroups: ['registration-retrieve']
        );

        $response = $this->client->deleteRegistration(
            $deleteRequest->getUser()->getId(),
            $deleteRequest->getRegistration()->getApplicationId()
        );
        $response = $this->fusionAuthResponseHandler->handle($response);

        $statusCode = $response->status;

        return new JsonResponse(status: $statusCode);
    }

}