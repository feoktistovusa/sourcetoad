<?php

declare(strict_types=1);

namespace App\Support;

class ArraySorter
{
    public function sortByKeys(array $data, array|string $keys): array
    {
        $keys = (array) $keys;

        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = $this->sortByKeys($value, $keys);
            }
        }
        unset($value);

        if ($this->hasMatchingKey($data, $keys)) {
            usort($data, $this->makeComparator($keys));
        }

        return $data;
    }

    private function hasMatchingKey(array $data, array $keys): bool
    {
        foreach ($data as $item) {
            if (!is_array($item)) {
                continue;
            }
            foreach ($keys as $key) {
                if (array_key_exists($key, $item)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function makeComparator(array $keys): \Closure
    {
        return static function (mixed $a, mixed $b) use ($keys): int {
            if (!is_array($a) || !is_array($b)) {
                return 0;
            }
            foreach ($keys as $key) {
                if (array_key_exists($key, $a) && array_key_exists($key, $b)) {
                    $result = $a[$key] <=> $b[$key];
                    if ($result !== 0) {
                        return $result;
                    }
                }
            }
            return 0;
        };
    }
}
