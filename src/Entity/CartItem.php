<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Item;
use App\ValueObject\Money;

readonly class CartItem
{
    public function __construct(
        private Item $item,
        private int $quantity,
    ) {}

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getLineTotal(): Money
    {
        return $this->item->price->multiply($this->quantity);
    }
}
