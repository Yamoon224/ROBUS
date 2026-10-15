<?php

namespace Tests\Unit;

use App\Http\Services\HomeService;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class HomeServicePeriodTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_current_month_mode_uses_the_complete_current_month(): void
    {
        Carbon::setTestNow('2026-10-06 12:30:00');

        $service = (new HomeService())->forCurrentMonth();
        $method = new ReflectionMethod($service, 'dashboardRange');
        $method->setAccessible(true);

        [$start, $end] = $method->invoke($service, 'day');

        $this->assertSame('2026-10-01 00:00:00', $start->format('Y-m-d H:i:s'));
        $this->assertSame('2026-10-31 23:59:59', $end->format('Y-m-d H:i:s'));
    }

    public function test_default_mode_keeps_the_daily_range_for_other_dashboards(): void
    {
        Carbon::setTestNow('2026-10-06 12:30:00');

        $service = new HomeService();
        $method = new ReflectionMethod($service, 'dashboardRange');
        $method->setAccessible(true);

        [$start, $end] = $method->invoke($service, 'day');

        $this->assertSame('2026-10-06 00:00:00', $start->format('Y-m-d H:i:s'));
        $this->assertSame('2026-10-06 23:59:59', $end->format('Y-m-d H:i:s'));
    }
}
