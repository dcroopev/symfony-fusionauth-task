<?php

namespace App\Controller;

use App\DTO\Entity\Token;
use App\DTO\Request\LoginRequest;
use App\Filter\DtoSerializerFilter;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{

    public function __construct(
        private FusionAuthClient $client,
        private FusionAuthResponseHandler $fusionAuthResponseHandler,
        private DtoSerializerFilter $dtoSerializerFilter,
    ) {
    }

    #[Route('/api/login', name: 'login', methods: 'POST')]
    public function login(Request $request, DTOSerializer $serializer): JsonResponse
    {
        $loginRequest = $serializer->deserialize($request->getContent(), LoginRequest::class, 'json');
        $loginRequestArray = $serializer->toArray($loginRequest);

        $response = $this->client->login($loginRequestArray);
        $response = $this->fusionAuthResponseHandler->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;

        $responseContent = $this->dtoSerializerFilter->filter($responseData, Token::class);

        return new JsonResponse(data: $responseContent, status: $statusCode, json: true);
    }

}