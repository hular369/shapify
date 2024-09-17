<?php
namespace Hular369\GeoShapify;

/**
 * Interface Shape
 *
 * Defines the contract for geometric shapes.
 *
 * @package Hular369\GeoShapify
 */
interface Shape {

    /**
     * Check if a point is inside the shape.
     *
     * @param float $lat The latitude of the point.
     * @param float $long The longitude of the point.
     * @return bool True if the point is inside the shape, false otherwise.
     */
    public function contains($lat, $long);

    /**
     * Get the number of sides of the shape.
     *
     * @return int The number of sides.
     */
    public function sides();

    /**
     * Find the nearest vertex of the shape to a given point.
     *
     * @param float $lat The latitude of the point.
     * @param float $long The longitude of the point.
     * @return array The coordinates of the nearest vertex.
     */
    public function nearestVertex($lat, $long);

    /**
     * Calculate the area of the shape.
     *
     * @return float The area of the shape.
     */
    public function area();
    
}