<?php

namespace Tests\Unit;

use App\Models\OperatorService;
use PHPUnit\Framework\TestCase;

class OperatorServiceCommissionTest extends TestCase
{
    public function test_it_returns_the_reward_when_all_configured_goals_are_reached(): void
    {
        $service = new OperatorService([
            'numbers' => 10,
            'amount' => 50000,
            'commission' => 2500,
        ]);

        $this->assertSame(2500.0, $service->calculateComZoneCommission(10, 50000));
    }

    public function test_it_returns_zero_when_one_of_the_goals_is_not_reached(): void
    {
        $service = new OperatorService([
            'numbers' => 10,
            'amount' => 50000,
            'commission' => 2500,
        ]);

        $this->assertSame(0.0, $service->calculateComZoneCommission(9, 50000));
        $this->assertSame(0.0, $service->calculateComZoneCommission(10, 49999));
    }

    public function test_it_supports_a_single_goal_rule(): void
    {
        $service = new OperatorService([
            'numbers' => 5,
            'amount' => 0,
            'commission' => 1000,
        ]);

        $this->assertSame(1000.0, $service->calculateComZoneCommission(5, 0));
    }
}
