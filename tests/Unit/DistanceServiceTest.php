<?php

namespace Tests\Unit;

use App\Services\DistanceService;
use PHPUnit\Framework\TestCase;

class DistanceServiceTest extends TestCase
{
    /**
     * Test Haversine formula calculation.
     */
    public function test_calculate_distance(): void
    {
        $service = new DistanceService();

        // Coordinates for Jakarta (Monas)
        $lat1 = -6.175392;
        $lon1 = 106.827153;

        // Coordinates for Bandung (Gedung Sate)
        $lat2 = -6.902481;
        $lon2 = 107.618810;

        $distance = $service->calculateDistance($lat1, $lon1, $lat2, $lon2);

        // Expected distance is around 118-120 km
        $this->assertGreaterThan(118, $distance);
        $this->assertLessThan(120, $distance);
    }

    /**
     * Test distance to the exact same point should be 0.
     */
    public function test_calculate_distance_same_point(): void
    {
        $service = new DistanceService();

        $lat = -6.175392;
        $lon = 106.827153;

        $distance = $service->calculateDistance($lat, $lon, $lat, $lon);

        $this->assertEquals(0, $distance);
    }
}
