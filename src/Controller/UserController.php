<?php

namespace App\Controller;

use App\DTO\Entity\User;
use App\DTO\Request\{CreateUserRequest, SearchRequest};
use App\DTO\Response\SearchResponse;
use App\DTO\Response\TokenResponse;
use App\Service\Exception\ServiceException;
use App\Service\Exception\ServiceExceptionData;
use Nelmio\ApiDocBundle\Annotation\{Model, Security};
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Security\User as LoggedInUser;

class UserController extends AbstractFusionAuthApiController
{

    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "Retrieve user information",
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['retrieve'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'User not found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user', name: 'user-retrieve', methods: 'GET')]
    public function retrieveUser(Request $request): JsonResponse
    {
        //todo implement jwt retrieval if loginId is not provided
        $emailRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            validationGroups: ['retrieve']
        );

        $response = $this->client->retrieveUserByLoginId($emailRequest->getEmail());
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData->user, User::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user/authenticated', name: 'user-retrieve-logged', methods: 'GET')]
    public function retrieveLoggedInUser(#[CurrentUser] ?LoggedInUser $user
    ): JsonResponse {
        $responseContent = $this->dtoSerializer->serialize($user->getUserDto(), 'json');

        return new JsonResponse(data: $responseContent, status: 200, json: true);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "Create user",
        content: new OA\JsonContent(ref: new Model(type: CreateUserRequest::class, groups: ['create'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TokenResponse::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user', name: 'user-create', methods: 'POST')]
    public function createUser(Request $request): JsonResponse
    {
        $createUserRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            CreateUserRequest::class,
            'json',
            validationGroups: ['create']
        );
        $createUserRequestArray = $this->dtoSerializer->toArray($createUserRequest);;

        $response = $this->client->createUser(null, $createUserRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, TokenResponse::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "Update the information of the user (including password).",
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['update'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'User not found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user', name: 'user-update', methods: 'PUT')]
    public function updateUser(Request $request): JsonResponse
    {
        $createUserRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            validationGroups: ['update']
        );

        $createUserRequestArray = ['user' => $this->dtoSerializer->toArray($createUserRequest)];
        $response = $this->client->updateUser($createUserRequest->getId(), $createUserRequestArray);

        $response = $this->fusionAuthResponseHandler->handle($response);
        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData->user, User::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "Hard delete users",
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['delete'])))
    ]
    #[OA\Response(response: '200', description: 'The request was successful')]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'User not found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user', name: 'user-delete', methods: 'DELETE')]
    public function deleteUser(
        Request $request,
        #[CurrentUser] ?LoggedInUser $user
    ): JsonResponse {
        $deleteIdRequest = $this->dtoSerializer->deserialize(
            $request->getContent(), User::class, 'json',
            validationGroups: ['delete']
        );

        if ($deleteIdRequest->getId() === $user->getId()) {
            $exceptionData = new ServiceExceptionData(Response::HTTP_FORBIDDEN, "Self-delete Not Allowed");
            throw new ServiceException($exceptionData);
        }

        $response = $this->client->deleteUser($deleteIdRequest->getId());
        $response = $this->fusionAuthResponseHandler->handle($response);


        $statusCode = $response->status;

        return new JsonResponse(status: $statusCode);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "Search for users either by a `queryString` or by `nextResults` token. Two bodies possible. ",
        content: new OA\JsonContent( oneOf: [
            new OA\Schema(ref: new Model(type: SearchRequest::class, groups: ['query'])),
            new OA\Schema(ref: new Model(type: SearchRequest::class, groups: ['next-result']))
        ]))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: SearchResponse::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'Empty response')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user/search', name: 'search', methods: 'POST')]
    public function searchUser(Request $request): JsonResponse
    {
        $searchRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            SearchRequest::class,
            'json',
            validate: false
        );

        if ($searchRequest->getNextResults()) {
            $searchRequest = $this->dtoSerializer->validateDto($searchRequest, 'next-result');
        } else {
            $searchRequest = $this->dtoSerializer->validateDto($searchRequest, 'query');
        }

        $response = $this->client->searchUsersByQuery($searchRequest->toArray());
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, SearchResponse::class);

        return new JsonResponse($responseContent, status: $statusCode, json: true);
    }
}