<?php

namespace Tests\Unit;

use App\Models\LedgerEntry;
use PHPUnit\Framework\TestCase;

class LedgerEntryTest extends TestCase
{
    /**
     * @dataProvider categoryProvider
     */
    public function test_it_resolves_the_entry_category(array $attributes, string $category): void
    {
        $entry = new LedgerEntry($attributes);

        $this->assertSame($category, $entry->category);
    }

    public function categoryProvider(): array
    {
        return [
            'bank commission' => [['is_comission' => true], LedgerEntry::CATEGORY_COMMISSION],
            'admin commission' => [['is_admin' => true], LedgerEntry::CATEGORY_ADMIN],
            'uv commission' => [['is_uv' => true], LedgerEntry::CATEGORY_UV_COMMISSION],
            'uv purchase' => [['is_wallet' => true], LedgerEntry::CATEGORY_UV_PURCHASE],
            'standard' => [[], LedgerEntry::CATEGORY_STANDARD],
        ];
    }

    public function test_amount_returns_the_non_zero_movement_side(): void
    {
        $this->assertSame(1500.0, (new LedgerEntry(['debit' => 1500, 'credit' => 0]))->amount);
        $this->assertSame(2400.0, (new LedgerEntry(['debit' => 0, 'credit' => 2400]))->amount);
    }
}
