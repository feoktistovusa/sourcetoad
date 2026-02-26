<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Address;
use App\ValueObject\CustomerName;

class Customer
{
    /** @var Address[] */
    private array $addresses = [];

    public function __construct(
        private readonly CustomerName $name,
    ) {}

    public function getName(): CustomerName
    {
        return $this->name;
    }

    public function addAddress(Address $address): void
    {
        $this->addresses[] = $address;
    }

    /** @return Address[] */
    public function getAddresses(): array
    {
        return $this->addresses;
    }
}
