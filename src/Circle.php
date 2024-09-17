<?php
namespace Src;
class Circle implements Shape {
    private $centerLat;
    private $centerLong;
    private $radius;

    public function __construct($centerLat, $centerLong, $radius) {
        $this->centerLat = $centerLat;
        $this->centerLong = $centerLong;
        $this->radius = $radius;
    }

    public function contains($lat, $long) {
        $earthRadius = 6371; // Earth's radius in km

        $dLat = deg2rad($lat - $this->centerLat);
        $dLong = deg2rad($long - $this->centerLong);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($this->centerLat)) * cos(deg2rad($lat)) *
             sin($dLong / 2) * sin($dLong / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance <= $this->radius;
    }

    public function sides() {
        return 1;
    }

    public function nearestVertex($lat, $long) {
        return [$this->centerLat, $this->centerLong];
    }

    public function area()
    {
        return pi() * pow($this->radius, 2);
    }
}