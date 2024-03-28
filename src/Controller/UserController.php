<?php

namespace App\Controller;

use App\DTO\CreateUserRequest;
use App\DTO\User;
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
        private FusionAuthClient $client
    ) {
    }


    #[Route('/api/user', name: 'user-retrieve', methods: 'GET')]
    public function retrieveUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        //todo implement jwt retrieval if loginId is not provided
        $emailRequest = $serializer->deserialize($request->getContent(), User::class, 'json');

        $result = $this->client->retrieveUserByLoginId($emailRequest->getEmail());

        if (!$result->wasSuccessful()) { //todo error handling
            return new JsonResponse($result, $result->status);
        }
        $responseContent = $serializer->serialize($result->successResponse, 'json');

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
    }


    #[Route('/api/user', name: 'user-create', methods: 'POST')]
    public function createUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        $createUserRequest = $serializer->deserialize($request->getContent(), CreateUserRequest::class, 'json');

        $createUserRequestArray = $serializer->toArray($createUserRequest);;

        $result = $this->client->createUser(null, $createUserRequestArray);

        if (!$result->wasSuccessful()) { //todo error handling
            return new JsonResponse($result, $result->status);
        }

        $responseContent = $serializer->serialize($result->successResponse, 'json');

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
    }

    #[Route('/api/user', name: 'user-update', methods: ['PUT', 'PATCH'])]
    public function updateUser(DTOSerializer $serializer, Request $request): JsonResponse
    {
        $createUserRequest = $serializer->deserialize($request->getContent(), User::class, 'json');

        $createUserRequestArray = ['user' => $serializer->toArray($createUserRequest)];
        $result = $this->client->updateUser($createUserRequest->getId(), $createUserRequestArray);

        if (!$result->wasSuccessful()) { //todo error handling
            return new JsonResponse($result, $result->status);
        }

        $responseContent = $serializer->serialize($result->successResponse, 'json');

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
    }

    #[Route('/api/user', name: 'user-delete', methods: 'DELETE')]
    public function deleteUser(DTOSerializer $serializer, Request $request, #[CurrentUser] ?\App\Security\User $user): JsonResponse
    {
        $deleteIdRequest = $serializer->deserialize($request->getContent(), User::class, 'json');
        if ($deleteIdRequest->getId() === $user->getId()){
            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        $result = $this->client->deactivateUser($deleteIdRequest->getId());
        if (!$result->wasSuccessful()) { //todo error handling
            return new JsonResponse($result, $result->status);
        }

        return new JsonResponse(status: Response::HTTP_OK);
    }

    #[Route('/api/user/search', name: 'search', methods: 'POST')]
    public function searchUserByQuery()
    {
        $temp = [
            "search" => [
                "numberOfResults" => 25,
                "queryString" => "croopev",
                "sortFields" => [
                    [
                        "name" => "email",
                        "order" => "asc"

                    ]
                ]
            ]
        ];

        $result2 = $this->client->searchUsersByQuery($temp);
    }
}