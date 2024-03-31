<?php

namespace App\Controller;

use App\DTO\Entity\User;
use App\DTO\Request\{CreateUserRequest, SearchRequest};
use App\DTO\Response\SearchResponse;
use App\DTO\Response\Token;
use App\Filter\DtoSerializerFilter;
use App\Service\Exception\ServiceException;
use App\Service\Exception\ServiceExceptionData;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Nelmio\ApiDocBundle\Annotation\{Model, Security};
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct(
        private FusionAuthClient $client,
        private FusionAuthResponseHandler $fusionAuthResponseHandler,
        private DtoSerializerFilter $dtoSerializerFilter,
    ) {
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
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
    public function retrieveUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        //todo implement jwt retrieval if loginId is not provided
        $emailRequest = $serializer->deserialize(
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
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: CreateUserRequest::class, groups: ['create'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Token::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or Bad Request')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/user', name: 'user-create', methods: 'POST')]
    public function createUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        $createUserRequest = $serializer->deserialize(
            $request->getContent(),
            CreateUserRequest::class,
            'json',
            validationGroups: ['create']
        );
        $createUserRequestArray = $serializer->toArray($createUserRequest);;

        $response = $this->client->createUser(null, $createUserRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
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
    public function updateUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        $createUserRequest = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            validationGroups: ['update']
        );

        $createUserRequestArray = ['user' => $serializer->toArray($createUserRequest)];
        $response = $this->client->updateUser($createUserRequest->getId(), $createUserRequestArray);

        $response = $this->fusionAuthResponseHandler->handle($response);
        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData->user, User::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
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
        DTOSerializer $serializer,
        Request $request,
        #[CurrentUser] ?\App\Security\User $user
    ): JsonResponse {
        $deleteIdRequest = $serializer->deserialize(
            $request->getContent(), User::class, 'json',
            validationGroups: ['delete']
        );

        if ($deleteIdRequest->getId() === $user->getId()) {
            $exceptionData = new ServiceExceptionData(Response::HTTP_FORBIDDEN, "Self-delete Not Allowed");
            throw new ServiceException($exceptionData);
        }

        $response = $this->client->deactivateUser($deleteIdRequest->getId());
        $response = $this->fusionAuthResponseHandler->handle($response);


        $statusCode = $response->status;

        return new JsonResponse(status: $statusCode);
    }


    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        content: new OA\JsonContent(oneOf: [
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
    public function searchUser(Request $request, DTOSerializer $serializer): JsonResponse
    {
        $searchRequest = $serializer->deserialize(
            $request->getContent(),
            SearchRequest::class,
            'json',
            validate: false
        );

        if ($searchRequest->getNextResults()) {
            $searchRequest = $serializer->validateDto($searchRequest, 'next-result');
        } else {
            $searchRequest = $serializer->validateDto($searchRequest, 'query');
        }

        $response = $this->client->searchUsersByQuery($searchRequest->toArray());
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, SearchResponse::class);

        return new JsonResponse($responseContent, status: $statusCode, json: true);
    }
}