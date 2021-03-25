<?php
class ksk3d_geometryPrimitives{
  static $content = array(
    'gml:AbstractGriddedSurface' => array(
      'gml:rows' => 1
    ),
    'gml:AbstractSolid' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1
    ),
    'gml:AffinePlacement' => array(
      'gml:location' => 1,
      'gml:refDirection' => 1,
      'gml:inDimension' => 1,
      'gml:outDimension' => 1
    ),
    'gml:Arc' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1
    ),
    'gml:ArcByBulge' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:bulge' => 1,
      'gml:normal' => 1
    ),
    'gml:ArcByCenterPoint' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:radius' => 1,
      'gml:startAngle' => 1,
      'gml:endAngle' => 1
    ),
    'gml:ArcString' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1
    ),
    'gml:ArcStringByBulge' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:bulge' => 1,
      'gml:normal' => 1
    ),
    'gml:baseCurve' => array(
      'gml:LineString' => 1,
      'gml:CompositeCurve' => 1,
      'gml:Curve' => 1,
      'gml:OrientableCurve' => 1
    ),
    'gml:baseSurface' => array(
      'gml:Polygon' => 1,
      'gml:CompositeSurface' => 1,
      'gml:Surface' => 1,
      'gml:PolyhedralSurface' => 1,
      'gml:TriangulatedSurface' => 1,
      'gml:Tin' => 1,
      'gml:OrientableSurface' => 1
    ),
    'gml:Bezier' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:degree' => 1,
      'gml:knot' => 1
    ),
    'gml:breakLines' => array(
      'gml:LineStringSegment' => 1
    ),
    'gml:BSpline' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:degree' => 1,
      'gml:knot' => 1
    ),
    'gml:Circle' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1
    ),
    'gml:CircleByCenterPoint' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:radius' => 1
    ),
    'gml:Clothoid' => array(
      'gml:refLocation' => 1,
      'gml:scaleFactor' => 1,
      'gml:startParameter' => 1,
      'gml:endParameter' => 1
    ),
    'gml:Cone' => array(
      'gml:rows' => 1
    ),
    'gml:controlPoint' => array(
      'gml:posList' => 1,
      'gml:pos' => 1,
      'gml:pointProperty' => 1
    ),
    'gml:CubicSpline' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1,
      'gml:vectorAtStart' => 1,
      'gml:vectorAtEnd' => 1
    ),
    'gml:Curve' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:segments' => 1
    ),
    'gml:curveMember' => array(
      'gml:LineString' => 1,
      'gml:CompositeCurve' => 1,
      'gml:Curve' => 1,
      'gml:OrientableCurve' => 1
    ),
    'gml:Cylinder' => array(
      'gml:rows' => 1
    ),
    'gml:exterior' => array(
      'gml:Shell' => 1
    ),
    'gml:Geodesic' => array(
      'gml:posList' => 1,
      'gml:pos' => 1,
      'gml:pointProperty' => 1
    ),
    'gml:GeodesicString' => array(
      'gml:posList' => 1,
      'gml:pos' => 1,
      'gml:pointProperty' => 1
    ),
    'gml:interior' => array(
      'gml:Shell' => 1
    ),
    'gml:knot' => array(
      'gml:knot' => 1
    ),
    'gml:Knot' => array(
      'gml:value' => 1,
      'gml:multiplicity' => 1,
      'gml:weight' => 1
    ),
    'gml:LineStringSegment' => array(
      'gml:pos' => 1,
      'gml:pointProperty' => 1,
      'gml:pointRep' => 1,
      'gml:posList' => 1,
      'gml:coordinates' => 1
    ),
    'gml:offsetBase' => array(
      'gml:LineString' => 1,
      'gml:CompositeCurve' => 1,
      'gml:Curve' => 1,
      'gml:OrientableCurve' => 1
    ),
    'gml:OffsetCurve' => array(
      'gml:offsetBase' => 1,
      'gml:distance' => 1,
      'gml:refDirection' => 1
    ),
    'gml:OrientableCurve' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:baseCurve' => 1
    ),
    'gml:OrientableSurface' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:baseSurface' => 1
    ),
    'gml:patches' => array(
      'gml:PolygonPatch' => 1,
      'gml:Triangle' => 1,
      'gml:Rectangle' => 1,
      'gml:Cone' => 1,
      'gml:Cylinder' => 1,
      'gml:Sphere' => 1
    ),
    'gml:PolygonPatch' => array(
      'gml:exterior' => 1,
      'gml:interior' => 1
    ),
    'gml:PolyhedralSurface' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:patches' => 1,
      'gml:polygonPatches' => 1,
      'gml:trianglePatches' => 1
    ),
    'gml:Rectangle' => array(
      'gml:exterior' => 1
    ),
    'gml:refLocation' => array(
      'gml:AffinePlacement' => 1
    ),
    'gml:Ring' => array(
      'gml:curveMember' => 1
    ),
    'gml:Row' => array(
      'gml:posList' => 1,
      'gml:pos' => 1,
      'gml:pointProperty' => 1
    ),
    'gml:rows' => array(
      'gml:Row' => 1
    ),
    'gml:segments' => array(
      'gml:LineStringSegment' => 1,
      'gml:ArcString' => 1,
      'gml:Arc' => 1,
      'gml:Circle' => 1,
      'gml:ArcStringByBulge' => 1,
      'gml:ArcByBulge' => 1,
      'gml:ArcByCenterPoint' => 1,
      'gml:CircleByCenterPoint' => 1,
      'gml:CubicSpline' => 1,
      'gml:BSpline' => 1,
      'gml:Bezier' => 1,
      'gml:OffsetCurve' => 1,
      'gml:Clothoid' => 1,
      'gml:GeodesicString' => 1,
      'gml:Geodesic' => 1
    ),
    'gml:Shell' => array(
      'gml:surfaceMember' => 1
    ),
    'gml:Solid' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:exterior' => 1,
      'gml:interior' => 1
    ),
    'gml:solidProperty' => array(
      'gml:CompositeSolid' => 1,
      'gml:Solid' => 1
    ),
    'gml:Sphere' => array(
      'gml:rows' => 1
    ),
    'gml:stopLines' => array(
      'gml:LineStringSegment' => 1
    ),
    'gml:Surface' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:patches' => 1,
      'gml:polygonPatches' => 1,
      'gml:trianglePatches' => 1
    ),
    'gml:surfaceMember' => array(
      'gml:Polygon' => 1,
      'gml:CompositeSurface' => 1,
      'gml:Surface' => 1,
      'gml:PolyhedralSurface' => 1,
      'gml:TriangulatedSurface' => 1,
      'gml:Tin' => 1,
      'gml:OrientableSurface' => 1
    ),
    'gml:Tin' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:patches' => 1,
      'gml:polygonPatches' => 1,
      'gml:trianglePatches' => 1,
      'gml:stopLines' => 1,
      'gml:breakLines' => 1,
      'gml:maxLength' => 1,
      'gml:controlPoint' => 1
    ),
    'gml:Triangle' => array(
      'gml:exterior' => 1
    ),
    'gml:TriangulatedSurface' => array(
      'gml:metaDataProperty' => 1,
      'gml:description' => 1,
      'gml:descriptionReference' => 1,
      'gml:identifier' => 1,
      'gml:name' => 1,
      'gml:patches' => 1,
      'gml:polygonPatches' => 1,
      'gml:trianglePatches' => 1
    )
  );
}