<?php

namespace App\Service;

use App\Service\Exception\FusionAuthClientExceptionData;
use App\Service\Exception\ServiceException;
use FusionAuth\ClientResponse;

class FusionAuthResponseHandler
{

    public function handle(ClientResponse $response): ?ClientResponse
    {
        if (!$response->wasSuccessful()) {
            $errorResponseArray = isset($response->errorResponse) ? json_decode(json_encode($response->errorResponse), true) : null;
            $exceptionData = new FusionAuthClientExceptionData($response->status, "FusionAuthClientViolation", $errorResponseArray);

            throw new ServiceException($exceptionData);
        }

        return $response;
    }

}