<?php

namespace OpenIDConnect\ValueObject;

class State
{
    private string $signature;
    private array $additionalState = [];

    /**
     * @param string $signature
     * @param array $additionalState
     */
    public function __construct(string $signature, array $additionalState)
    {
        $this->signature = $signature;
        $this->additionalState = $additionalState;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return array
     */
    public function getAdditionalState(): array
    {
        return $this->additionalState;
    }
}