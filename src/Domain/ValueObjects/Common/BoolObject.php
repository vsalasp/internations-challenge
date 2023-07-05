<?php

namespace App\Domain\ValueObjects\Common;

class BoolObject
{
    public function __construct(
        private bool $value
    ) {
    }

    protected function validate(): void
    {
    }

    public function isValue(): bool
    {
        return $this->value;
    }

    public function setValue(bool $value): void
    {
        $this->value = $value;
    }
}
