<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use PHPUnit\Framework\TestCase;

class WeatherServiceTest extends TestCase
{
    public function test_significant_change_detection()
    {
        $service = new WeatherService();

        $old = [
            'temp_high' => 20,
            'temp_low' => 10,
            'precip_chance' => 0,
        ];

        // No change
        $new = $old;
        $this->assertFalse($service->isSignificantChange($old, $new));

        // Small change
        $new = $old;
        $new['temp_high'] = 22; // +2
        $this->assertFalse($service->isSignificantChange($old, $new));

        // Significant temp change
        $new = $old;
        $new['temp_high'] = 23; // +3
        $this->assertTrue($service->isSignificantChange($old, $new));

        // Significant precip change
        $new = $old;
        $new['precip_chance'] = 20; // +20
        $this->assertTrue($service->isSignificantChange($old, $new));
    }
}

