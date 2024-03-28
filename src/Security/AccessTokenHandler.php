<?php

namespace App\Security;

use FusionAuth\FusionAuthClient;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private FusionAuthClient $fusionAuthClient
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        //todo validate the jwt locally
        $validationResult = $this->fusionAuthClient->validateJWT($accessToken);

        if (!$validationResult->wasSuccessful()) {
            throw new BadCredentialsException('Invalid credentials.'); // todo uniform error handling
        }
        return new UserBadge($validationResult->successResponse->jwt->email);
    }
}