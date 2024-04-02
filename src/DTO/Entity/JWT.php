<?php

namespace App\DTO\Entity;

class JWT
{
    private ?string $aud;
    private ?int $exp;
    private ?int $iat;
    private ?string $iss;
    private ?string $jti;
    private ?string $sub;
    private ?string $applicationId;
    private ?int $auth_time;
    private ?string $authenticationType;
    private ?string $email;
    private ?bool $emailVerified;
    private ?array $roles;
    private ?string $sid;
    private ?string $tid;

    public function getAud(): ?string
    {
        return $this->aud;
    }

    public function setAud(?string $aud): JWT
    {
        $this->aud = $aud;
        return $this;
    }

    public function getExp(): ?int
    {
        return $this->exp;
    }

    public function setExp(?int $exp): JWT
    {
        $this->exp = $exp;
        return $this;
    }

    public function getIat(): ?int
    {
        return $this->iat;
    }

    public function setIat(?int $iat): JWT
    {
        $this->iat = $iat;
        return $this;
    }

    public function getIss(): ?string
    {
        return $this->iss;
    }

    public function setIss(?string $iss): JWT
    {
        $this->iss = $iss;
        return $this;
    }

    public function getJti(): ?string
    {
        return $this->jti;
    }

    public function setJti(?string $jti): JWT
    {
        $this->jti = $jti;
        return $this;
    }

    public function getSub(): ?string
    {
        return $this->sub;
    }

    public function setSub(?string $sub): JWT
    {
        $this->sub = $sub;
        return $this;
    }

    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): JWT
    {
        $this->applicationId = $applicationId;
        return $this;
    }

    public function getAuthTime(): ?int
    {
        return $this->auth_time;
    }

    public function setAuthTime(?int $auth_time): JWT
    {
        $this->auth_time = $auth_time;
        return $this;
    }

    public function getAuthenticationType(): ?string
    {
        return $this->authenticationType;
    }

    public function setAuthenticationType(?string $authenticationType): JWT
    {
        $this->authenticationType = $authenticationType;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): JWT
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailVerified(): ?bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(?bool $emailVerified): JWT
    {
        $this->emailVerified = $emailVerified;
        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): JWT
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSid(): ?string
    {
        return $this->sid;
    }

    public function setSid(?string $sid): JWT
    {
        $this->sid = $sid;
        return $this;
    }

    public function getTid(): ?string
    {
        return $this->tid;
    }

    public function setTid(?string $tid): JWT
    {
        $this->tid = $tid;
        return $this;
    }


}