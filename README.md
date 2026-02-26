# Sourcetoad PHP Assessment

PHP 8.2, PSR-12, Composer autoload. Three exercises.

## Setup

```bash
composer install
```

## Running

```bash
php questions/question1.php
php questions/question2.php
php questions/question3.php
```

## Structure

```
src/
├── Contract/       # Interfaces (ShippingCalculatorInterface, TaxCalculatorInterface)
├── Entity/         # Cart (aggregate), CartItem, Customer
├── Exception/      # CartException
├── Service/        # TaxCalculator
├── Support/        # ArrayPrinter, ArraySorter
└── ValueObject/    # Address, CustomerName, Item, Money
questions/          # Runnable scripts for each exercise
```

#### Q1 uses `ArrayPrinter` to recursively dump a nested array.
#### Q2 uses `ArraySorter` to sort by one or more keys at any depth.
#### Q3 wires up the full cart: customer, addresses, items, tax, shipping.

Money is stored as integer cents throughout to avoid float precision issues.
Shipping is injected via interface, the demo uses an anonymous class stub.
