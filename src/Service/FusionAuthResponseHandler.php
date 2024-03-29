<?php

namespace App\Service;

use FusionAuth\ClientResponse;

class FusionAuthResponseHandler
{

    public function handle(ClientResponse $response): ?ClientResponse
    {
        if (!$response->wasSuccessful()) {
            throw new FusionAuthClientException();
        }

        return $response;
    }

}