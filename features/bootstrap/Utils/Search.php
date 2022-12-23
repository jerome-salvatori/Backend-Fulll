<?php

namespace Features\bootstrap\Utils;

class Search {
    public function execute(array $array, string $key, mixed $value): mixed {
        $results = [];

        foreach ($array as $subArray) {
            if (array_key_exists($key, $subArray) && $subArray[$key] === $value) {
                $results[] = $subArray;
            }
        }

        return $results;
    }
}