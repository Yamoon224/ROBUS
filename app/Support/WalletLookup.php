<?php

namespace App\Support;

use App\Models\Wallets\Wallet;

/**
 * Recherche de wallets mémorisée le temps d'une requête HTTP.
 *
 * Remplace les `Wallet::where('wallet_id', ...)->first()` exécutés ligne par
 * ligne dans les tableaux : prime() charge tous les wallets d'une liste en une
 * seule requête, find() ne relance jamais deux fois la même recherche.
 */
class WalletLookup
{
    private const COLUMNS = ['wallet_id', 'id'];

    private array $found = [];

    public function find(string $column, $value): ?Wallet
    {
        if ($value === null || $value === '') {
            return null;
        }

        $key = $column . ':' . $value;

        if (! array_key_exists($key, $this->found)) {
            $this->found[$key] = Wallet::where($column, $value)->first();
        }

        return $this->found[$key];
    }

    public function prime(iterable $items, string $attribute, string $column = 'wallet_id'): void
    {
        if (! in_array($column, self::COLUMNS, true)) {
            return;
        }

        $values = [];

        foreach ($items as $item) {
            $value = data_get($item, $attribute);

            if ($value !== null && $value !== '' && ! array_key_exists($column . ':' . $value, $this->found)) {
                $values[(string) $value] = true;
            }
        }

        foreach (array_chunk(array_keys($values), 1000) as $chunk) {
            // Seuls les wallets trouvés sont mémorisés : une valeur absente
            // repassera par find() et sa requête d'origine.
            foreach (Wallet::whereIn($column, array_map('strval', $chunk))->get() as $wallet) {
                $this->found[$column . ':' . $wallet->getAttribute($column)] = $wallet;
            }
        }
    }
}
