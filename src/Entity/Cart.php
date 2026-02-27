<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\ShippingCalculatorInterface;
use App\Exception\CartException;
use App\ValueObject\Address;
use App\ValueObject\Item;
use App\ValueObject\Money;

class Cart
{
    private const TAX_RATE = 0.07;

    private ?Customer $customer = null;

    /** @var Item[] */
    private array $items = [];

    private ?Address $shippingAddress = null;

    public function __construct(
        private readonly ShippingCalculatorInterface $shippingCalculator,
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

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    /** @return Item[] */
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
            fn(Money $carry, Item $item) => $carry->add($item->price->multiply($item->quantity)),
            Money::fromCents(0),
        );
    }

    public function getTax(): Money
    {
        return $this->getSubtotal()->percentage(self::TAX_RATE);
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
        return $this->getSubtotal()
            ->add($this->getTax())
            ->add($this->getShippingCost());
    }

    public function getItemCostWithShippingAndTax(Item $item): Money
    {
        $lineTotal = $item->price->multiply($item->quantity);
        $tax = $lineTotal->percentage(self::TAX_RATE);
        $itemShipping = $this->getShippingCost()->percentage(1 / count($this->items));

        return $lineTotal->add($tax)->add($itemShipping);
    }
}
