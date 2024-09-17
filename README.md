This package enables you to check if a coordinate is inside a defined boundary of a polygon. It also facilitates with a feature to calculate area of a given boundary defined by polygon coordinates (Lat,long).

  - Same facilities are there for circular boundaries as well defined by a center and radius.


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