<?php

declare(strict_types=1);

$guests = require __DIR__ . '/data.php';

function sortByKeys(array $data, array|string $keys, bool $ascending = true): array
{
    $keys = (array) $keys;

    foreach ($data as &$value) {
        if (is_array($value)) {
            $value = sortByKeys($value, $keys, $ascending);
        }
    }
    unset($value);

    $hasMatch = false;
    foreach ($data as $item) {
        if (!is_array($item)) {
            continue;
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $item)) {
                $hasMatch = true;
                break 2;
            }
        }
    }

    if ($hasMatch) {
        usort($data, static function (mixed $a, mixed $b) use ($keys, $ascending): int {
            if (!is_array($a) || !is_array($b)) {
                return 0;
            }
            foreach ($keys as $key) {
                if (array_key_exists($key, $a) && array_key_exists($key, $b)) {
                    $result = $a[$key] <=> $b[$key];
                    if ($result !== 0) {
                        return $ascending ? $result : -$result;
                    }
                }
            }
            return 0;
        });
    }

    return $data;
}

$printGuests = static function (array $sorted): void {
    foreach ($sorted as $guest) {
        $accountIds = array_column($guest['guest_account'], 'account_id');
        echo "  {$guest['last_name']}  accounts: [" . implode(', ', $accountIds) . "]\n";
    }
};

echo "=== Sorted by last_name (ASC) ===\n";
$printGuests(sortByKeys($guests, 'last_name'));

echo "\n=== Sorted by last_name (DESC) ===\n";
$printGuests(sortByKeys($guests, 'last_name', false));

echo "\n=== Sorted by last_name + account_id (ASC) ===\n";
$printGuests(sortByKeys($guests, ['last_name', 'account_id']));
