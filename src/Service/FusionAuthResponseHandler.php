<?php

namespace App\Service;

use App\Service\Exception\FusionAuthClientExceptionData;
use App\Service\Exception\ServiceException;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\ClientResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class FusionAuthResponseHandler
{

    public function __construct(protected DTOSerializer $dtoSerializer,
    ) {
    }

    public function handle(ClientResponse $response): ?ClientResponse
    {
        if (!$response->wasSuccessful()) {
            $errorResponseArray = isset($response->errorResponse) ? json_decode(
                json_encode($response->errorResponse),
                true
            ) : null;
            $exceptionData = new FusionAuthClientExceptionData(
                $response->status,
                "FusionAuthClientViolation",
                $errorResponseArray
            );

            throw new ServiceException($exceptionData);
        }

        return $response;
    }

    public function createJsonResponse(ClientResponse $response, ?string $filterClassName = null): JsonResponse
    {
        $response = $this->handle($response);

        $responseData = $response->successResponse;
        $statusCode = $response->status;
        $json = false;


        if (!$responseData) {
            return new JsonResponse(status: $statusCode);
        }

        if ($filterClassName) {
            $responseData = $this->dtoSerializer->filter($responseData, $filterClassName);
            $json = true;
        }

        return new JsonResponse(data: $responseData, status: $statusCode, json: $json);
    }

}