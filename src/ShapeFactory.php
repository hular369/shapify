<?php
namespace Hular369\GeoShapify;

use Hular369\GeoShapify\Circle;
use Hular369\GeoShapify\Irregular;

/**
 * Class ShapeFactory
 *
 * Factory class to create different types of shapes.
 *
 * @package Hular369\GeoShapify
 */
class ShapeFactory {

    /**
     * Create a shape instance based on the type.
     *
     * @param string $type The type of shape to create ('circle' or 'polygon').
     * @param array|null $polygonPoints The points defining the polygon (required for 'polygon' type).
     * @param float|null $centerLat The latitude of the circle's center (required for 'circle' type).
     * @param float|null $centerLong The longitude of the circle's center (required for 'circle' type).
     * @param float|null $radius The radius of the circle (required for 'circle' type).
     * @return Circle|Irregular The created shape instance.
     * @throws \Exception If an invalid shape type is provided.
     */
    public static function create($type, $polygonPoints, $centerLat = null, $centerLong = null, $radius = null) {

        switch ($type) {
            case 'circle':
                return new Circle($centerLat, $centerLong, $radius);
            case 'polygon':
                return new Irregular($polygonPoints);
            default:
                throw new \Exception('Invalid shape type');
        }
        
    }
}