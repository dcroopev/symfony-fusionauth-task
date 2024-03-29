<?php

namespace App\Controller;

use App\DTO\CreateUserRequest;
use App\DTO\SearchRequest;
use App\DTO\SearchResponse;
use App\DTO\Token;
use App\DTO\User;
use App\Filter\DtoSerializerFilter;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
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


    #[Route('/api/user', name: 'user-retrieve', methods: 'GET')]
    public function retrieveUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        //todo implement jwt retrieval if loginId is not provided
        $emailRequest = $serializer->deserialize($request->getContent(), User::class, 'json');

        $response = $this->client->retrieveUserByLoginId($emailRequest->getEmail());
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData->user, User::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user', name: 'user-create', methods: 'POST')]
    public function createUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        $createUserRequest = $serializer->deserialize($request->getContent(), CreateUserRequest::class, 'json');
        $createUserRequestArray = $serializer->toArray($createUserRequest);;

        $response = $this->client->createUser(null, $createUserRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user', name: 'user-update', methods: ['PUT', 'PATCH'])]
    public function updateUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        $createUserRequest = $serializer->deserialize($request->getContent(), User::class, 'json');

        $createUserRequestArray = ['user' => $serializer->toArray($createUserRequest)];
        $response = $this->client->updateUser($createUserRequest->getId(), $createUserRequestArray);

        $response = $this->fusionAuthResponseHandler->handle($response);
        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData->user, User::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }


    #[Route('/api/user', name: 'user-delete', methods: 'DELETE')]
    public function deleteUser(
        DTOSerializer $serializer,
        Request $request,
        #[CurrentUser] ?\App\Security\User $user
    ): JsonResponse {
        $deleteIdRequest = $serializer->deserialize($request->getContent(), User::class, 'json');

        if ($deleteIdRequest->getId() === $user->getId()) {
            //todo throw error and handle in listener
            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        $response = $this->client->deactivateUser($deleteIdRequest->getId());
        $response = $this->fusionAuthResponseHandler->handle($response);


        $statusCode = $response->status;

        return new JsonResponse(status: $statusCode);
    }


    #[Route('/api/user/search', name: 'search', methods: 'POST')]
    public function searchUserByQuery(Request $request, DTOSerializer $serializer): JsonResponse
    {
        $searchRequest = $serializer->deserialize($request->getContent(), SearchRequest::class, 'json');

        $response = $this->client->searchUsersByQuery($searchRequest->toArray());
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, SearchResponse::class);

        return new JsonResponse($responseContent, status: $statusCode, json: true);
    }
}