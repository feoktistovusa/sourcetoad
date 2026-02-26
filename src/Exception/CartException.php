<?php

declare(strict_types=1);

namespace App\Exception;

class CartException extends \RuntimeException
{
    public static function customerNotSet(): self
    {
        return new self('No customer has been added to the cart.');
    }

    public static function shippingAddressNotSet(): self
    {
        return new self('Shipping address has not been set.');
    }
}
