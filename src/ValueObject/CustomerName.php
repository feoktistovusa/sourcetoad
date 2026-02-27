<?php

declare(strict_types=1);

namespace App\ValueObject;

readonly class CustomerName
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {
        if (trim($firstName) === '') {
            throw new \InvalidArgumentException('First name cannot be empty.');
        }

        if (trim($lastName) === '') {
            throw new \InvalidArgumentException('Last name cannot be empty.');
        }
    }

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
