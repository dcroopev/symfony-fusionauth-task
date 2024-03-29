<?php

namespace App\Service\Exception;

class ServiceExceptionData
{
    public function __construct(protected int $statusCode, protected string $type)
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toArray()
    {
        return [
            'type' => $this->getType(),
        ];
    }

}