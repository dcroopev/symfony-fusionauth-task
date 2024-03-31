<?php

namespace App\DTO\Request;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class SearchRequest
{
    #[Groups(['next-result'])]
    #[Assert\NotBlank(groups: ['next-result'])]
    private ?string $nextResults = null;

    #[Groups(['query'])]
    #[Assert\NotBlank(groups: ['query'])]
    private ?string $queryString = null;

    #[Groups(['query'])]
    private ?string $sortField;

    #[Groups(['query'])]
    private ?string $order = 'asc';

    #[Groups(['query', 'next-result'])]
    #[Assert\Positive(groups: ['query', 'next-result'])]
    private ?int $numberOfResults;

    #[Groups(['query', 'next-result'])]
    #[Assert\PositiveOrZero(groups: ['query', 'next-result'])]
    private ?int $startRow;

    public function getNumberOfResults(): ?int
    {
        return $this->numberOfResults;
    }

    public function setNumberOfResults(?int $numberOfResults): SearchRequest
    {
        $this->numberOfResults = $numberOfResults;
        return $this;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function setQueryString(?string $queryString): SearchRequest
    {
        $this->queryString = $queryString;
        return $this;
    }

    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    public function setSortField(?string $sortField): SearchRequest
    {
        $this->sortField = $sortField;
        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(?string $order): SearchRequest
    {
        $this->order = $order;
        return $this;
    }

    public function getNextResults(): ?string
    {
        return $this->nextResults;
    }

    public function setNextResults(?string $nextResults): SearchRequest
    {
        $this->nextResults = $nextResults;
        return $this;
    }

    public function getStartRow(): ?int
    {
        return $this->startRow;
    }

    public function setStartRow(?int $startRow): SearchRequest
    {
        $this->startRow = $startRow;
        return $this;
    }

    public function toArray(): array
    {
        $array = [
            "numberOfResults" => isset($this->numberOfResults) ? $this->getNumberOfResults() : null,
        ];

        if (isset($this->nextResults) && $this->nextResults) {
            $array['nextResults'] = $this->getNextResults();
        } else {
            $array['queryString'] = isset($this->queryString) ? $this->getQueryString() : null;

            if (isset($this->sortField)) {
                $array['sortFields'] =
                    [
                        [
                            "name" => $this->getSortField(),
                            "order" => $this->getOrder(),
                        ],
                    ];
            }
        }

        return [
            "search" => $array
        ];
    }
}