<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\TaxCalculatorInterface;
use App\ValueObject\Money;

class TaxCalculator implements TaxCalculatorInterface
{
    private const RATE = 0.07;

    public function calculate(Money $amount): Money
    {
        return $amount->percentage(self::RATE);
    }

    public function getRate(): float
    {
        return self::RATE;
    }
}
