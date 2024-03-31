<?php

namespace App\DTO\Request;

class EmailTemplateSearchRequest
{

    private ?int $numberOfResults = 1;

    public function __construct(private ?string $name)
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): EmailTemplateSearchRequest
    {
        $this->name = $name;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'search' => [
                'name' => $this->name,
                'numberOfResults' => $this->numberOfResults,
            ]
        ];
    }
}