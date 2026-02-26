<?php

declare(strict_types=1);

namespace App\ValueObject;

readonly class CustomerName
{
    public function __construct(
        public string $first,
        public string $last,
    ) {
        if (trim($first) === '') {
            throw new \InvalidArgumentException('First name cannot be empty.');
        }

        if (trim($last) === '') {
            throw new \InvalidArgumentException('Last name cannot be empty.');
        }
    }

    public function full(): string
    {
        return "{$this->first} {$this->last}";
    }
}
