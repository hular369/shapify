<?php
namespace Src;

use Src\Circle;
use Src\Irregular;

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