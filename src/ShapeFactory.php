<?php
namespace Hular369\GeoShapify;

use Hular369\GeoShapify\Circle;
use Hular369\GeoShapify\Irregular;

class ShapeFactory {

    public static function create($type, $polygonPoints, $centerLat = null, $centerLong = null, $radius = null) {

        switch ($type) {
            case 'circle':
                return new Circle($centerLat, $centerLong, $radius);
            case 'irregular':
                return new Irregular($polygonPoints);
            default:
                throw new \Exception('Invalid shape type');
        }
        
    }
}