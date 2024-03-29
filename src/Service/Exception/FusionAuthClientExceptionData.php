<?php

namespace App\Service\Exception;

class FusionAuthClientExceptionData extends ServiceExceptionData
{

    public function __construct(int $statusCode, string $type, private ?array $errorResponse)
    {
        parent::__construct($statusCode, $type);
    }


    public function toArray(): ?array
    {
        if (!$this->errorResponse) {
            return null;
        }

        $array = [
            'type' => $this->getType(),
        ];

        $generalErrors = $this->getGeneralErrorsArray();

        if (!empty($generalErrors)) {
            $array['generalErrors'] = $generalErrors;
        }

        $fieldErrors = $this->getFieldErrors();

        if (!empty($fieldErrors)) {
            $array['fieldErrors'] = $fieldErrors;
        }

        return $array;
    }

    public function getGeneralErrorsArray(): array
    {
        $violations = [];

        $generalErrors = $this->errorResponse['generalErrors'];

        foreach ($generalErrors as $error) {
            if (!isset($error['code'])) {
                continue;
            }
            $violations[] = [
                'propertyPath' => ucfirst(trim($error['code'], '[]')),
                'message' => $error['message'],
            ];
        }

        return $violations;
    }

    public function getFieldErrors(): array
    {
        $violations = [];

        $fieldErrors = $this->errorResponse['fieldErrors'];

        foreach ($fieldErrors as $fieldName => $error) {
            $violations[] = [
                'propertyPath' => $fieldName,
                'message' => $error[0]['code'],
            ];
        }

        return $violations;
    }
}