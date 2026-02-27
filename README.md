# Sourcetoad PHP Assessment

PHP 8.2, PSR-12. Three exercises.

## Running

```bash
php questions/question1.php
php questions/question2.php
php questions/question3.php
```

## Structure

```
src/
├── Contract/       # Interfaces (ShippingCalculatorInterface)
├── Entity/         # Cart (aggregate), Customer
├── Exception/      # CartException
└── ValueObject/    # Address, CustomerName, Item, Money
questions/
├── data.php        # Shared guest data array (used by Q1 and Q2)
├── question1.php
├── question2.php
└── question3.php
```

#### Q1 — self-contained recursive function that prints all nested key-value pairs at any depth.
#### Q2 — self-contained recursive function that sorts by one or more keys at any depth, with ASC/DESC support.
#### Q3 — full cart: customer, addresses, items, per-item cost (incl. tax & shipping), subtotal, tax, total.

Money is stored as integer cents throughout to avoid float precision issues.
Tax rate is 7%, defined as a constant in `Cart`.
Shipping is injected via `ShippingCalculatorInterface`; the demo uses an anonymous class stub.
