<?php

declare(strict_types=1);

namespace App\Support;

class ArrayPrinter
{
    public function output(array $data, int $depth = 0): void
    {
        $indent = str_repeat('  ', $depth);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                echo "{$indent}{$key}:\n";
                $this->output($value, $depth + 1);
            } else {
                echo "{$indent}{$key}: {$this->formatValue($value)}\n";
            }
        }
    }

    private function formatValue(mixed $value): string
    {
        return match (true) {
            $value === null => 'null',
            is_bool($value) => $value ? 'true' : 'false',
            default => (string) $value,
        };
    }
}
