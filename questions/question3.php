<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Exception/CartException.php';
require_once __DIR__ . '/../src/ValueObject/Money.php';
require_once __DIR__ . '/../src/ValueObject/Address.php';
require_once __DIR__ . '/../src/ValueObject/CustomerName.php';
require_once __DIR__ . '/../src/ValueObject/Item.php';
require_once __DIR__ . '/../src/Entity/Customer.php';
require_once __DIR__ . '/../src/Contract/ShippingCalculatorInterface.php';
require_once __DIR__ . '/../src/Entity/Cart.php';

use App\Contract\ShippingCalculatorInterface;
use App\Entity\Cart;
use App\Entity\Customer;
use App\ValueObject\Address;
use App\ValueObject\CustomerName;
use App\ValueObject\Item;
use App\ValueObject\Money;

$shippingCalculator = new class implements ShippingCalculatorInterface {
    public function calculate(Address $destination, array $items): Money
    {
        return Money::fromFloat(9.99);
    }
};

$customer = new Customer(new CustomerName('Jane', 'Doe'));
$customer->addAddress(new Address('123 Main St', null, 'Tampa', 'FL', '33601'));
$customer->addAddress(new Address('456 Oak Ave', 'Apt 7', 'Orlando', 'FL', '32801'));

$cart = new Cart($shippingCalculator);
$cart->setCustomer($customer);
$cart->setShippingAddress($customer->getAddresses()[0]);

$cart->addItem(new Item(1, 'Widget', 2, Money::fromFloat(19.99)));
$cart->addItem(new Item(2, 'Gadget', 1, Money::fromFloat(49.95)));
$cart->addItem(new Item(3, 'Doohickey', 3, Money::fromFloat(9.50)));

$formatLine2 = static fn(?string $line2): string => $line2 !== null ? ", {$line2}" : '';

echo "Customer Name: {$cart->getCustomer()->getName()->getFullName()}\n";

echo "\nCustomer Addresses:\n";
foreach ($cart->getCustomer()->getAddresses() as $i => $address) {
    echo "  [{$i}] {$address->line1}{$formatLine2($address->line2)}, {$address->city}, {$address->state} {$address->zip}\n";
}

$shipping = $cart->getShippingAddress();
echo "\nShips To: {$shipping->line1}{$formatLine2($shipping->line2)}, {$shipping->city}, {$shipping->state} {$shipping->zip}\n";

echo "\nItems in Cart:\n";
foreach ($cart->getItems() as $item) {
    echo sprintf(
        "  [id:%s] %-12s  qty: %d  unit: $%s  line total: $%s\n",
        $item->id,
        $item->name,
        $item->quantity,
        $item->price,
        $item->price->multiply($item->quantity),
    );
}

echo "\nCost per item (incl. tax & shipping):\n";
foreach ($cart->getItems() as $item) {
    echo sprintf("  %-12s $%s\n", $item->name, $cart->getItemCostWithShippingAndTax($item));
}

echo sprintf("\nSubtotal : $%s\n", $cart->getSubtotal());
echo sprintf("Tax (7%%) : $%s\n", $cart->getTax());
echo sprintf("Shipping : $%s\n", $cart->getShippingCost());
echo sprintf("Total    : $%s\n", $cart->getTotal());
