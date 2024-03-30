<?php

namespace App\DTO\Response;

use App\DTO\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class SearchResponse
{
    private ?string $nextResults;

    #[Assert\PositiveOrZero]
    private ?int $total;

    /**
     * @var User[]
     */
    private array $users;

    public function getNextResults(): ?string
    {
        return $this->nextResults;
    }

    public function setNextResults(?string $nextResults): SearchResponse
    {
        $this->nextResults = $nextResults;
        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): SearchResponse
    {
        $this->total = $total;
        return $this;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): SearchResponse
    {
        $this->users = $users;
        return $this;
    }

}