<?php

namespace App\Controller;

use App\DTO\Request\LoginRequest;
use App\DTO\Response\Token;
use App\Filter\DtoSerializerFilter;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Nelmio\ApiDocBundle\Annotation\{Model};
use OpenApi\Attributes as OA;
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

    #[OA\Tag(name: 'Authentication')]
    #[OA\Response(
        response: '200',
        description: 'The authentication was successful. ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Token::class))
        )
    )]
    #[OA\Response(
        response: 202,
        description: 'The authentication was successful. The user is not registered for the application specified by the applicationId on the request.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Token::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '404', description: 'Incorrect user/password')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '423', description: 'User is Locked')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: LoginRequest::class)))
    ]
    #[Route('/api/login', name: 'login', methods: 'POST', format: 'json')]
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