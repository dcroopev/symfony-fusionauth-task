<?php

namespace App\Service\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionData extends ServiceExceptionData
{

    public function __construct(int $statusCode, string $type, private ConstraintViolationList $violations)
    {
        parent::__construct($statusCode, $type);
    }


    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'violations' => $this->getViolationsArray(),
        ];
    }

    public function getViolationsArray(): array
    {
        $violations = [];

        foreach ($this->violations as $violation) {
            $violations[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $violations;
    }
}