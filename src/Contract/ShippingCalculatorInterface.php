<?php

declare(strict_types=1);

namespace App\Contract;

use App\Entity\CartItem;
use App\ValueObject\Address;
use App\ValueObject\Money;

interface ShippingCalculatorInterface
{
    /** @param CartItem[] $items */
    public function calculate(Address $destination, array $items): Money;
}
