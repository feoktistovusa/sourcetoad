<?php

declare(strict_types=1);

namespace App\ValueObject;

readonly class Money
{
    private function __construct(
        private int $cents,
    ) {}

    public static function fromCents(int $cents): self
    {
        self::assertNonNegative($cents);

        return new self($cents);
    }

    public static function fromFloat(float $amount): self
    {
        $cents = (int) round($amount * 100);

        self::assertNonNegative($cents);

        return new self($cents);
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->cents * $multiplier);
    }

    public function percentage(float $rate): self
    {
        return new self((int) round($this->cents * $rate));
    }

    public function __toString(): string
    {
        return number_format($this->cents / 100, 2);
    }

    private static function assertNonNegative(int $cents): void
    {
        if ($cents < 0) {
            throw new \InvalidArgumentException('Money value cannot be negative.');
        }
    }
}
