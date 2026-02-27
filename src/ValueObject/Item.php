<?php

declare(strict_types=1);

namespace App\ValueObject;

readonly class Item
{
    public function __construct(
        public int|string $id,
        public string $name,
        public int $quantity,
        public Money $price,
    ) {}
}
