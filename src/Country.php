<?php

namespace Hular369\GeoShapify;

use Hular369\GeoShapify\Shape;

class Country implements Shape
{

    private $country;
    private $countryBoundaryPoints = [];
    const EARTH_RADIUS_KM = 6371;

    public function __construct($country)
    {
        $this->country = $country;
        $this->countryBoundaryPoints = $this->getCountryBoundaryPoints($country);
        // print_r($this->countryBoundaryPoints[0]);
        // exit;
    }

    public function contains($lat, $long)
    {
        // If boundaries are empty, return false
        if (empty($this->countryBoundaryPoints)) {
            return false;
        }

        // Iterate through all polygons (for MultiPolygon geometries)
        foreach ($this->countryBoundaryPoints as $polygon) {

            $isInside = false;

            // Each polygon may contain multiple rings:
            //  - ring[0] = outer boundary
            //  - ring[1..n] = inner holes
            foreach ($polygon as $ringIndex => $ring) {
                $inside = $this->pointInRing($lat, $long, $ring);

                // Outer ring → if inside, mark true
                if ($ringIndex === 0 && $inside) {
                    $isInside = true;
                }

                // Inner ring (hole) → if inside, mark false
                if ($ringIndex > 0 && $inside) {
                    $isInside = false;
                }
            }

            // If found inside one polygon (and not in a hole), return true
            if ($isInside) {
                return true;
            }
        }

        // Not inside any polygon
        return false;
    }


    public function sides()
    {
        return null; // Not applicable for countries
    }

    public function nearestVertex($lat, $long)
    {
        $minDistance = PHP_INT_MAX;
        $nearestVertex = null;

        if (empty($this->countryBoundaryPoints)) {
            return null;
        }

        foreach ($this->countryBoundaryPoints as $polygon) {
            foreach ($polygon as $ring) {
                foreach ($ring as $point) {
                    $distance = sqrt(pow($point[0] - $lat, 2) + pow($point[1] - $long, 2));
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $nearestVertex = $point;
                    }
                }
            }
        }

        return $nearestVertex;
    }


    public function area()
    {
        if (empty($this->countryBoundaryPoints)) {
            return 0;
        }

        $totalArea = 0;

        foreach ($this->countryBoundaryPoints as $polygon) {
            $polygonArea = 0;

            foreach ($polygon as $ringIndex => $ring) {
                $ringArea = 0;
                $numPoints = count($ring);

                for ($i = 0; $i < $numPoints; $i++) {
                    $point1 = $ring[$i];
                    $point2 = $ring[($i + 1) % $numPoints];

                    $lat1 = $this->deg2rad_custom($point1[0]);
                    $lon1 = $this->deg2rad_custom($point1[1]);
                    $lat2 = $this->deg2rad_custom($point2[0]);
                    $lon2 = $this->deg2rad_custom($point2[1]);

                    $ringArea += ($lon2 - $lon1) * (2 + sin($lat1) + sin($lat2));
                }

                $ringArea = $ringArea * self::EARTH_RADIUS_KM * self::EARTH_RADIUS_KM / 2;

                // Outer ring → add, holes → subtract
                $polygonArea += ($ringIndex === 0) ? abs($ringArea) : -abs($ringArea);
            }

            $totalArea += $polygonArea;
        }

        return abs($totalArea); // total area in square kilometers
    }


    private function deg2rad_custom($deg)
    {
        return $deg * (M_PI / 180);
    }

    /**
     *  to check if a point is inside a single ring using ray-casting.
     */
    private function pointInRing($lat, $long, $ring)
    {
        $numPoints = count($ring);
        $inside = false;
        $j = $numPoints - 1;

        for ($i = 0; $i < $numPoints; $i++) {
            $lat_i = $ring[$i][0];
            $lon_i = $ring[$i][1];
            $lat_j = $ring[$j][0];
            $lon_j = $ring[$j][1];

            $intersect = (($lon_i > $long) != ($lon_j > $long)) &&
                ($lat < ($lat_j - $lat_i) * ($long - $lon_i) / ($lon_j - $lon_i) + $lat_i);

            if ($intersect) {
                $inside = !$inside;
            }

            $j = $i;
        }

        return $inside;
    }

    private function getCountryBoundaryPoints($country)
    {
        $path = realpath(__DIR__ . "/../data/boundaries/{$country}.geojson");
        if (!$path || !is_readable($path)) {
            return [];
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);
        if (!$data) {
            return [];
        }

        $countryBoundaries = [];

        // recursive coordinate extractor
        $extractCoordinates = function ($geometry) use (&$extractCoordinates) {
            $polygons = [];

            if ($geometry['type'] === 'Polygon') {
                $polygon = [];
                foreach ($geometry['coordinates'] as $ring) {
                    $ringPoints = [];
                    foreach ($ring as $coord) {
                        // GeoJSON uses [lon, lat]
                        $ringPoints[] = [$coord[1], $coord[0]];
                    }
                    $polygon[] = $ringPoints;
                }
                $polygons[] = $polygon;
            } elseif ($geometry['type'] === 'MultiPolygon') {
                foreach ($geometry['coordinates'] as $polyCoords) {
                    $polygon = [];
                    foreach ($polyCoords as $ring) {
                        $ringPoints = [];
                        foreach ($ring as $coord) {
                            $ringPoints[] = [$coord[1], $coord[0]];
                        }
                        $polygon[] = $ringPoints;
                    }
                    $polygons[] = $polygon;
                }
            } elseif ($geometry['type'] === 'FeatureCollection' && isset($geometry['features'])) {
                foreach ($geometry['features'] as $feature) {
                    if (isset($feature['geometry'])) {
                        $polygons = array_merge($polygons, $extractCoordinates($feature['geometry']));
                    }
                }
            } elseif ($geometry['type'] === 'Feature' && isset($geometry['geometry'])) {
                $polygons = $extractCoordinates($geometry['geometry']);
            }

            return $polygons;
        };

        $countryPolygons = $extractCoordinates($data);
        return $countryPolygons;
    }
}
