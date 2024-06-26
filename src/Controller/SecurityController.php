<?php

namespace App\Controller;

use App\DTO\Request\RevokeSessionRequest;
use App\DTO\Request\TokenRequest;
use App\DTO\Request\LoginRequest;
use App\DTO\Response\RefreshTokenResponse;
use App\DTO\Response\TokenResponse;
use Nelmio\ApiDocBundle\Annotation\{Model, Security};
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\AccessToken\HeaderAccessTokenExtractor;

class SecurityController extends AbstractFusionAuthApiController
{

    #[OA\Tag(name: 'Authentication')]
    #[OA\RequestBody(
        description: "User/Password authentication. Generates tokens.",
        content: new OA\JsonContent(ref: new Model(type: LoginRequest::class)))
    ]
    #[OA\Response(
        response: '200',
        description: 'The authentication was successful. ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TokenResponse::class))
        )
    )]
    #[OA\Response(
        response: 202,
        description: 'The authentication was successful. The user is not registered for the application specified by the applicationId on the request.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TokenResponse::class))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '404', description: 'Incorrect user/password')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '423', description: 'User is Locked')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Route('/api/login', name: 'login', methods: 'POST')]
    public function login(Request $request): JsonResponse
    {
        $loginRequest = $this->dtoSerializer->deserialize($request->getContent(), LoginRequest::class, 'json');
        $loginRequestArray = $this->dtoSerializer->toArray($loginRequest);

        $response = $this->client->login($loginRequestArray);

        return $this->fusionAuthResponseHandler->createJsonResponse($response, TokenResponse::class);
    }

    #[OA\Tag(name: 'Authentication')]
    #[OA\RequestBody(
        description: "Revokes a single refresh token.",
        content: new OA\JsonContent(ref: new Model(type: TokenRequest::class, groups: ['refresh-token'])))
    ]
    #[OA\Response(response: '200', description: 'The request was successful. Refresh token has been revoked if it had existed.')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/logout', name: 'logout', methods: 'POST')]
    public function logout(Request $request): JsonResponse
    {
        $logoutRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            TokenRequest::class,
            'json',
            context: ["groups" => ['refresh-token']],
            validationGroups: 'refresh-token'
        );
        $headerTokenExtractor = new HeaderAccessTokenExtractor();

        $encodedJwt = $headerTokenExtractor->extractAccessToken($request);
        $logoutRequest->setToken($encodedJwt);

        $refreshTokenRequestArray = $this->dtoSerializer->toArray($logoutRequest);

        $response = $this->client->logoutWithRequest($refreshTokenRequestArray);

        return $this->fusionAuthResponseHandler->createJsonResponse($response);
    }


    #[OA\Tag(name: 'Authentication')]
    #[OA\RequestBody(
        description: "Issues a JWT token. The jwt token, needed for the request is already provided in the `Authorization: Bearer` header",
        content: new OA\JsonContent(ref: new Model(type: TokenRequest::class, groups: ['issue-jwt'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful. ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TokenResponse::class, groups: ['token']))
        )
    )]
    #[OA\Response(
        response: 202,
        description: 'The authentication was successful. The user is not authorized to the requested Application.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TokenResponse::class, groups: ['token']))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/jwt/issue', name: 'jwt-issue', methods: 'POST')]
    public function issueJwt(Request $request): JsonResponse
    {
        $jwtRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            TokenRequest::class,
            'json',
            validationGroups: 'issue-jwt'
        );
        $headerTokenExtractor = new HeaderAccessTokenExtractor();

        $encodedJwt = $headerTokenExtractor->extractAccessToken($request);

        $response = $this->client->issueJWT(
            $jwtRequest->getApplicationId(),
            $encodedJwt,
            $jwtRequest->getRefreshToken()
        );
        $response = $this->fusionAuthResponseHandler->handle($response);

        return $this->fusionAuthResponseHandler->createJsonResponse($response, TokenResponse::class);
    }


    #[OA\Tag(name: 'Authentication')]
    #[OA\Parameter(
        name: 'refreshTokenId',
        description: 'Get the refresh token object that corresponds to ths refreshTokenId (UUID)',
        in: 'path',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: '200',
        description: 'The request was successful. ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: RefreshTokenResponse::class))
        )
    )]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/jwt/refresh/{refreshTokenId}', name: 'jwt-retrieve-refresh', methods: 'GET')]
    public function retrieveRefreshToken(string $refreshTokenId): JsonResponse
    {
        $refreshTokenRequest = new TokenRequest();
        $refreshTokenRequest->setRefreshTokenId($refreshTokenId);

        $this->dtoSerializer->validateDto($refreshTokenRequest);

        $response = $this->client->retrieveRefreshTokenById($refreshTokenRequest->getRefreshTokenId());

        return $this->fusionAuthResponseHandler->createJsonResponse($response, RefreshTokenResponse::class);
    }

    #[OA\Tag(name: 'Authentication')]
    #[OA\RequestBody(
        description: 'Takes a `refreshToken` and provides a new set of `jwt` and `refresh` tokens.',
        content: new OA\JsonContent(ref: new Model(type: TokenRequest::class, groups: ['refresh-token'])))
    ]
    #[OA\Response(
        response: '200',
        description: 'The request was successful. ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TokenResponse::class, groups: ['refresh-token']))
        )
    )]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'Not Found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/jwt/refresh', name: 'jwt-refresh', methods: 'POST')]
    public function refreshJwt(Request $request): JsonResponse
    {
        $refreshTokenRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            TokenRequest::class,
            'json',
            context: ["groups" => ['refresh-token']],
            validationGroups: 'refresh-token'
        );

        $refreshTokenRequestArray = $this->dtoSerializer->toArray($refreshTokenRequest);
        $response = $this->client->exchangeRefreshTokenForJWT($refreshTokenRequestArray);

        return $this->fusionAuthResponseHandler->createJsonResponse($response, TokenResponse::class);
    }

    #[OA\Tag(name: 'Authentication')]
    #[OA\RequestBody(
        description: "Revokes all session for the given user in the given application",
        content: new OA\JsonContent(ref: new Model(type: RevokeSessionRequest::class)))
    ]
    #[OA\Response(response: '200', description: 'The request was successful. All existing sessions for the given user in the given application have been revoked.')]
    #[OA\Response(response: '400', description: 'FusionAuthClientViolation error or `Bad Request` ')]
    #[OA\Response(response: '401', description: 'Unauthorized request ')]
    #[OA\Response(response: '404', description: 'Not Found')]
    #[OA\Response(response: '422', description: 'Constraint Violation Error')]
    #[OA\Response(response: '500', description: 'Server Error')]
    #[Security(name: 'Bearer')]
    #[Route('/api/jwt/revoke', name: 'jwt-revoke-all', methods: 'DELETE')]
    public function revokeAllJwt(Request $request): JsonResponse
    {
        $revokeRequest = $this->dtoSerializer->deserialize(
            $request->getContent(),
            RevokeSessionRequest::class,
            'json',
        );

        $response = $this->client->revokeRefreshTokensByUserIdForApplication(
            $revokeRequest->getUserId(),
            $revokeRequest->getApplicationId()
        );

        return $this->fusionAuthResponseHandler->createJsonResponse($response);
    }


}