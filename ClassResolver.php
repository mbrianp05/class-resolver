<?php

namespace Laton\Framework\Kernel\ClassResolver;

class ClassResolver
{
    protected ?string $namespace = null;
    protected ?string $class = null;
    protected ?string $shortClass = null;

    public function __construct(protected string $code)
    {
        $this->extract();
    }

    public function getName(): ?string
    {
        return $this->class;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getShortName(): ?string
    {
        return $this->shortClass;
    }

    private function getTokens(): array
    {
        return \token_get_all($this->code);
    }

    private function extract(): void
    {
        $tokens = $this->getTokens();
        $currentToken = null;

        for ($i = 0; $i <= \count($tokens) - 1; $i++) {
            $token = $tokens[$i];

            if (\is_string($token)) {
                // Skip tokens like ;
                continue;
            }

            $currentToken = new Token($token[0], $token[1]);

            if ('T_NAMESPACE' == $currentToken->getName()) {
                if (
                    \array_key_exists($i + 2, $tokens) 
                    && ('T_STRING' == \token_name($tokens[$i + 2][0])
                    || 'T_NAME_QUALIFIED' == \token_name($tokens[$i + 2][0]))
                ) {
                    $this->namespace = $tokens[$i + 2][1];
                }
            }

            if ('T_CLASS' == $currentToken->getName()) {
                if (
                    \array_key_exists($i + 2, $tokens) 
                    && 'T_STRING' == \token_name($tokens[$i + 2][0])
                ) {
                    $this->class = $this->namespace . '\\' . $tokens[$i + 2][1];
                    $this->shortClass = $tokens[$i + 2][1];
                }
            }
        }
    }
}