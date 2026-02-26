<?php

declare(strict_types=1);

namespace App\Contract;

use App\ValueObject\Money;

interface TaxCalculatorInterface
{
    public function calculate(Money $amount): Money;

    public function getRate(): float;
}
