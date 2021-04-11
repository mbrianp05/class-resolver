<?php

namespace Laton\Framework\Kernel\ClassResolver;

class Token
{
    public function __construct(protected int $id, protected string $token)
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return \token_name($this->id);
    }
}