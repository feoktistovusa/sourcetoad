<?php

declare(strict_types=1);

namespace App\ValueObject;

readonly class Address
{
    public function __construct(
        public string $line1,
        public ?string $line2,
        public string $city,
        public string $state,
        public string $zip,
    ) {
        if (trim($line1) === '') {
            throw new \InvalidArgumentException('Address line 1 cannot be empty.');
        }

        if (trim($city) === '') {
            throw new \InvalidArgumentException('City cannot be empty.');
        }

        if (trim($state) === '') {
            throw new \InvalidArgumentException('State cannot be empty.');
        }

        if (trim($zip) === '') {
            throw new \InvalidArgumentException('ZIP code cannot be empty.');
        }
    }
}
