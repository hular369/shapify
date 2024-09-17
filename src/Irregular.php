<?php

namespace Hular369\GeoShapify;

use Hular369\GeoShapify\Shape;

class Irregular implements Shape
{

    private $points;
    const EARTH_RADIUS_KM = 6371;

    public function __construct($points)
    {
        $this->points = $points;
    }

    public function contains($lat, $long)
    {
        $i = 0;
        $j = count($this->points) - 1;
        $c = false;
        for ($i = 0; $i < count($this->points); $i++) {
            if (($this->points[$i][1] > $long) != ($this->points[$j][1] > $long) &&
                ($lat < ($this->points[$j][0] - $this->points[$i][0]) * ($long - $this->points[$i][1]) / ($this->points[$j][1] - $this->points[$i][1]) + $this->points[$i][0])
            ) {
                $c = !$c;
            }
            $j = $i;
        }
        return $c;
    }

    public function sides()
    {
        return count($this->points);
    }

    public function nearestVertex($lat, $long)
    {
        $minDistance = PHP_INT_MAX;
        $nearestVertex = null;
        foreach ($this->points as $point) {
            $distance = sqrt(pow($point[0] - $lat, 2) + pow($point[1] - $long, 2));
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestVertex = $point;
            }
        }
        return $nearestVertex;
    }

    public function area()
    {
        $area = 0;
        $numPoints = count($this->points);

        // Loop through each edge of the polygon
        for ($i = 0; $i < $numPoints; $i++) {
            // Current vertex
            $point1 = $this->points[$i];
            // Next vertex (wraps around to the first vertex if we are at the last one)
            $point2 = $this->points[($i + 1) % $numPoints];

            // Convert degrees to radians for latitude and longitude
            $lat1 = $this->deg2rad_custom($point1[0]);
            $lon1 = $this->deg2rad_custom($point1[1]);
            $lat2 = $this->deg2rad_custom($point2[0]);
            $lon2 = $this->deg2rad_custom($point2[1]);

            // Calculate the area using spherical excess formula
            $area += ($lon2 - $lon1) * (2 + sin($lat1) + sin($lat2));
        }

        // Multiply by the radius of the earth squared
        $area = $area * self::EARTH_RADIUS_KM * self::EARTH_RADIUS_KM / 2;

        return abs($area); // Return absolute value of the area in square kilometers
    }

    private function deg2rad_custom($deg)
    {
        return $deg * (M_PI / 180);
    }
}
