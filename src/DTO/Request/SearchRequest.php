<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class SearchRequest
{
    #[Assert\Positive]
    private ?int $numberOfResults;

    private ?string $queryString;

    private ?string $sortField;

    private ?string $order = 'asc';

    #[Assert\PositiveOrZero]
    private ?int $startRow;

    private ?string $nextResults;

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