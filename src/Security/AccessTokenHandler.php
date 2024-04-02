<?php

namespace App\Security;

use App\DTO\Entity\JWT;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private FusionAuthClient $fusionAuthClient,
        private FusionAuthResponseHandler $fusionAuthResponseHandler,
        private DTOSerializer $dtoSerializer,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        //todo validate the jwt locally
        $validationResult = $this->fusionAuthClient->validateJWT($accessToken);

        $validationResult = $this->fusionAuthResponseHandler->handle($validationResult);

        $responseData = $validationResult->successResponse;

        /* @var JWT $jwt */
        $jwt = $this->dtoSerializer->fromData($responseData->jwt, JWT::class);

        return new UserBadge($jwt->getEmail());
    }
}