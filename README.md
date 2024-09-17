## A PHP library that allows to check if a coordinate(lat,long) is inside or outside of a circle or a polygon


This package enables you to check if a coordinate is inside a defined boundary of a polygon. It also provides a feature to calculate the area of a given boundary defined by polygon coordinates (latitude, longitude).

## Features

- **Polygon Boundaries**: 
  - Check if a coordinate is inside a polygon.
  - Calculate the area of a polygon.

- **Circular Boundaries**:
  - Check if a coordinate is inside a circle defined by a center and radius.
  - Calculate the area of a circle.

## Usage

### For Polygons:
1. Define the polygon coordinates.
2. Create a polygon shape using `ShapeFactory`.
3. Check if a coordinate is inside the polygon using `contains`.
4. Calculate the area of the polygon using `area`.

### For Circles:
1. Create a circle shape using `ShapeFactory` with center coordinates and radius.
2. Check if a coordinate is inside the circle using `contains`.
3. Calculate the area of the circle using `area`.
This package enables you to check if a coordinate is inside a defined boundary of a polygon. It also facilitates with a feature to calculate area of a given boundary defined by polygon coordinates (Lat,long).


To create and work with polygons, you can use the `ShapeFactory` class as follows:

```php
<?php
// Define the coordinates of the polygon vertices
$egpt = [
    [31.597741, 25.112545], 
    [22.152357, 24.958816], 
    [22.081148, 36.719048], 
    [31.269823, 31.338550]
];

// Create a ShapeFactory instance
$shape = new ShapeFactory();

// Create a polygon shape
$drawShape = $shape::create('polygon', $egpt);

// Check if a point is inside the polygon
$isInside = $drawShape->contains(27.701853, 85.319418);

// Calculate the area of the polygon
$area = $drawShape->area();

// get nearest vertex of the polygon
$nearestVertex = $drawShape->nearestVertex(27.701853, 85.319418)

// Output the results
echo "Nearest vertex of the polygon: " . json_encode($nearestVertex) . PHP_EOL;
echo "Is inside: " . ($isInside ? "Yes" : "No") . PHP_EOL;
echo "Area: " . $area . PHP_EOL;
?>

For circles:
<?php
// Create a ShapeFactory instance
$shape = new ShapeFactory();

// Create a circle shape with center coordinates and radius
$drawShape = $shape::create('circle', null, 27.710258, 85.279664, 10); // radius=10 in km

// Check if a point is inside the circle
$isInside = $drawShape->contains(27.710258, 85.279664);

// Calculate the area of the circle
$area = $drawShape->area(); // area in sq. km

// Output the results
echo "Is inside: " . ($isInside ? "Yes" : "No") . PHP_EOL;
echo "Area: " . $area . PHP_EOL;
?>; 