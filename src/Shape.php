<?php
namespace Src;

interface Shape {

    public function contains($lat, $long);
    public function sides();
    public function nearestVertex($lat, $long);
    public function area();
    
}