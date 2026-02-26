<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Contract\ShippingCalculatorInterface;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Customer;
use App\Service\TaxCalculator;
use App\ValueObject\Address;
use App\ValueObject\CustomerName;
use App\ValueObject\Item;
use App\ValueObject\Money;

$taxCalculator = new TaxCalculator();

$shippingCalculator = new class implements ShippingCalculatorInterface {
    public function calculate(Address $destination, array $items): Money
    {
        return Money::fromFloat(9.99);
    }
};

$customer = new Customer(new CustomerName('Jane', 'Doe'));
$customer->addAddress(new Address('123 Main St', null, 'Tampa', 'FL', '33601'));
$customer->addAddress(new Address('456 Oak Ave', 'Apt 7', 'Orlando', 'FL', '32801'));

$cart = new Cart($shippingCalculator, $taxCalculator);
$cart->setCustomer($customer);
$cart->setShippingAddress($customer->getAddresses()[0]);

$cart->addItem(new CartItem(new Item('Widget', Money::fromFloat(19.99)), 2));
$cart->addItem(new CartItem(new Item('Gadget', Money::fromFloat(49.95)), 1));
$cart->addItem(new CartItem(new Item('Doohickey', Money::fromFloat(9.50)), 3));

$formatLine2 = static fn(?string $line2): string => $line2 !== null ? ", {$line2}" : '';

echo "Customer Name: {$cart->getCustomer()->getName()->full()}\n";

echo "\nCustomer Addresses:\n";
foreach ($cart->getCustomer()->getAddresses() as $i => $address) {
    echo "  [{$i}] {$address->line1}{$formatLine2($address->line2)}, {$address->city}, {$address->state} {$address->zip}\n";
}

$shipping = $cart->getShippingAddress();
echo "\nShips To: {$shipping->line1}{$formatLine2($shipping->line2)}, {$shipping->city}, {$shipping->state} {$shipping->zip}\n";

echo "\nItems in Cart:\n";
foreach ($cart->getItems() as $cartItem) {
    echo sprintf(
        "  %-12s  qty: %d  unit: $%s  line total: $%s\n",
        $cartItem->getItem()->name,
        $cartItem->getQuantity(),
        $cartItem->getItem()->price,
        $cartItem->getLineTotal(),
    );
}

echo sprintf("\nSubtotal : $%s\n", $cart->getSubtotal());
echo sprintf("Tax (%d%%) : $%s\n", (int) ($taxCalculator->getRate() * 100), $cart->getTax());
echo sprintf("Shipping : $%s\n", $cart->getShippingCost());
echo sprintf("Total    : $%s\n", $cart->getTotal());
