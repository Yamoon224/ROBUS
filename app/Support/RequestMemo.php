<?php

namespace App\Support;

/**
 * Mémo de valeurs calculées, valable le temps d'une requête HTTP (ou d'un job).
 * Sert aux helpers appelés plusieurs fois par page (menu, formulaires).
 */
class RequestMemo
{
    private array $values = [];

    public function remember(string $key, callable $callback)
    {
        if (! array_key_exists($key, $this->values)) {
            $this->values[$key] = $callback();
        }

        return $this->values[$key];
    }
}
