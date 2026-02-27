<?php

declare(strict_types=1);

namespace App\Contract;

use App\ValueObject\Address;
use App\ValueObject\Item;
use App\ValueObject\Money;

interface ShippingCalculatorInterface
{
    /** @param Item[] $items */
    public function calculate(Address $destination, array $items): Money;
}
