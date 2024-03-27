<?php

namespace App\Controller;

use App\DTO\LoginRequest;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{

    public function __construct(
        private FusionAuthClient $client
    ) {
    }

    #[Route('/api/login', name: 'login', methods: 'POST')]
    public function login(Request $request, DTOSerializer $serializer)
    {
        $loginRequest = $serializer->deserialize($request->getContent(), LoginRequest::class, 'json');

        $authResult = $this->client->login([
            "loginId" => $loginRequest->getUsername(),
            "password" => $loginRequest->getPassword(),
            "applicationId" => $loginRequest->getApplicationId(),
        ]);

        $responseContent = $serializer->serialize($authResult, 'json');

        if (!$authResult->wasSuccessful()) { //todo error handling
            return new JsonResponse(data: $responseContent, status: $authResult->status, json: true);
        }

        //todo return filtered token and user information
        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
    }

    #[Route('/api/logout', name: 'logout', methods: 'POST')]
    public function logout()
    {

    }

}