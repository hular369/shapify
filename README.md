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


For polygons:
$egpt = [[31.597741, 25.112545], [22.152357, 24.958816], [22.081148, 36.719048], [31.269823, 31.338550]];
$shape = new ShapeFactory();
$drawShape = $shape::create('polygon', $egpt);
$isInside  = $drawShape->contains(27.701853, 85.319418);
$area = $drawShape->area(); 

For circles:
$shape = new ShapeFactory();
$drawShape = $shape::create('circle', null, 27.710258, 85.279664, 10);
$isInside  = $drawShape->contains(27.710258, 85.279664);
$area = $drawShape->area(); 