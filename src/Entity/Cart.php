<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\ShippingCalculatorInterface;
use App\Contract\TaxCalculatorInterface;
use App\Exception\CartException;
use App\ValueObject\Address;
use App\ValueObject\Money;

class Cart
{
    private ?Customer $customer = null;

    private array $items = [];

    private ?Address $shippingAddress = null;

    public function __construct(
        private readonly ShippingCalculatorInterface $shippingCalculator,
        private readonly TaxCalculatorInterface $taxCalculator,
    ) {}

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @throws CartException if no customer has been set.
     */
    public function getCustomer(): Customer
    {
        if ($this->customer === null) {
            throw CartException::customerNotSet();
        }

        return $this->customer;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    /** @return CartItem[] */
    public function getItems(): array
    {
        return $this->items;
    }

    public function setShippingAddress(Address $address): void
    {
        $this->shippingAddress = $address;
    }

    /**
     * @throws CartException if no shipping address has been set.
     */
    public function getShippingAddress(): Address
    {
        if ($this->shippingAddress === null) {
            throw CartException::shippingAddressNotSet();
        }

        return $this->shippingAddress;
    }

    public function getSubtotal(): Money
    {
        return array_reduce(
            $this->items,
            fn(Money $carry, CartItem $item) => $carry->add($item->getLineTotal()),
            Money::fromCents(0),
        );
    }

    public function getTax(): Money
    {
        return $this->taxCalculator->calculate($this->getSubtotal());
    }

    /**
     * @throws CartException if no shipping address has been set.
     */
    public function getShippingCost(): Money
    {
        return $this->shippingCalculator->calculate($this->getShippingAddress(), $this->items);
    }

    public function getTotal(): Money
    {
        $subtotal = $this->getSubtotal();

        return $subtotal
            ->add($this->taxCalculator->calculate($subtotal))
            ->add($this->getShippingCost());
    }
}
