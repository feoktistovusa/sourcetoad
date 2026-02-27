<?php

declare(strict_types=1);

$guests = require __DIR__ . '/data.php';

function printNestedArray(array $data, int $depth = 0): void
{
    $indent = str_repeat('  ', $depth);

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            echo "{$indent}{$key}:\n";
            printNestedArray($value, $depth + 1);
        } else {
            $formatted = match (true) {
                is_null($value)  => 'null',
                is_bool($value)  => $value ? 'true' : 'false',
                default          => (string) $value,
            };
            echo "{$indent}{$key}: {$formatted}\n";
        }
    }
}

printNestedArray($guests);
