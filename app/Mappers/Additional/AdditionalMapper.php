<?php

namespace App\Mappers\Additional;

abstract class AdditionalMapper implements AdditionalMapperInterface
{
    public function __construct(array $args)
    {
        $validated = $this->validateInput($args);
        $this->initialize($validated);
    }

    abstract protected function validateInput(array $args): array;

    abstract protected function initialize(array $args): void;
}
