<?php
class ksk3d_dataset_citygml{
  static function insert_attrib($doc ,$node ,$tag_name ,$tag_value ,$attrib_unit ,$attrib_code ,$attrib_name){
    if (preg_match('/\/?@.+?$/' ,$tag_name ,$attrib)==1){
      $attrib = preg_replace('/\/?@/' ,'' ,$attrib[0]);
      $tag_name = preg_replace('/\/?@.+?$/' ,'' ,$tag_name);
      if (!empty($tag_name)){
        $xpath = new DOMXpath($doc);
        $node = $xpath->query($tag_name ,$node)->item(0);
      } else {
      }
      $node->setAttribute($attrib ,$tag_value);

    } else {
      $node = ksk3d_dataset_gml::node_appendChild_path($doc ,$node ,$tag_name);
      $node->nodeValue = $tag_value;
      if (preg_match('/gen:\S+attribute/i' ,$tag_name) == 1){
        $node = $node->parentNode;
        $node->setAttribute("name" ,$attrib_name);
      } else {
        if (!empty($attrib_unit)){$node->setAttribute("uom" ,$attrib_unit);}
      }
      if (!empty($attrib_code)){$node->setAttribute("codeSpace" ,$attrib_code);}
    }
  }

  static function insert_geom_3d($doc ,$base_node ,$geom ,$hole ,$z ,$m ,$flg_xy_replace){
    if (empty($z)){$z=0;}
    if (empty($m)){$m=0;}
    $node = $base_node;
    foreach(array("gml:surfaceMember","gml:Polygon") as $str_elem){
      $node = $node->appendChild($doc->createElement($str_elem));
    }
    preg_match_all('/\([^\(\)]+?\)/' ,$geom ,$geom_array);
    foreach($geom_array[0] as $g){
      $g = preg_replace('/\(|\)/' ,'' ,$g);
      foreach(array("gml:exterior","gml:LinearRing","gml:posList") as $str_elem){
        $node = $node->appendChild($doc->createElement($str_elem));
      }
      if ($flg_xy_replace){
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.($z+$m) ,$g));
      } else {
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.($z+$m) ,$g));
      }

      $node->nodeValue = $xyz;
      $node = $node->parentNode->parentNode->parentNode;
    }
    if (!empty($hole)){
      preg_match_all('/\([^\(\)]+?\)/' ,$hole ,$geom_array2);
      foreach($geom_array2[0] as $g2){
        $g2 = preg_replace('/\(|\)/' ,'' ,$g2);
        foreach(array("gml:interior","gml:LinearRing","gml:posList") as $str_elem){
          $node = $node->appendChild($doc->createElement($str_elem));
        }
        if ($flg_xy_replace){
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.($z+$m) ,$g2));
        } else {
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.($z+$m) ,$g2));
        }
        $xyz = ksk3d_dataset_gml::poslist_verticesReverse($xyz);

        $node->nodeValue = $xyz;
        $node = $node->parentNode->parentNode->parentNode;
      }
    }
    $node = $node->parentNode->parentNode;

    foreach(array("gml:surfaceMember","gml:Polygon") as $str_elem){
      $node = $node->appendChild($doc->createElement($str_elem));
    }
    foreach($geom_array[0] as $g){
      $g = preg_replace('/\(|\)/' ,'' ,$g);
      foreach(array("gml:exterior","gml:LinearRing","gml:posList") as $str_elem){
        $node = $node->appendChild($doc->createElement($str_elem));
      }
      if ($flg_xy_replace){
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.$z ,$g));
        $xyz = ksk3d_dataset_gml::poslist_verticesReverse($xyz);
      } else {
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.$z ,$g));
        $xyz = ksk3d_dataset_gml::poslist_verticesReverse($xyz);
      }
      
      $node->nodeValue = $xyz;
      $node = $node->parentNode->parentNode->parentNode;
    }
    if (!empty($hole)){
      foreach($geom_array2[0] as $g2){
        $g2 = preg_replace('/\(|\)/' ,'' ,$g2);
        foreach(array("gml:interior","gml:LinearRing","gml:posList") as $str_elem){
          $node = $node->appendChild($doc->createElement($str_elem));
        }
        if ($flg_xy_replace){
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.$z ,$g2));
        } else {
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.$z ,$g2));
        }
        $node->nodeValue = $xyz;
        $node = $node->parentNode->parentNode->parentNode;
      }
    }
    $node = $node->parentNode->parentNode;

    foreach($geom_array[0] as $g){
      $g = preg_replace('/\(|\)/' ,'' ,$g);
      if ($flg_xy_replace){
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.$z ,$g));
      } else {
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.$z ,$g));
      }
      static::insert_geom_3d_wall($doc ,$node ,$xyz ,$z ,$m);
    }

    if (!empty($hole)){
      foreach($geom_array2[0] as $g){
        $g = preg_replace('/\(|\)/' ,'' ,$g);
        if ($flg_xy_replace){
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.$z ,$g));
        } else {
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.$z ,$g));
        }
        $xyz = ksk3d_dataset_gml::poslist_verticesReverse($xyz);
        static::insert_geom_3d_wall($doc ,$node ,$xyz ,$z ,$m);
      }
    }

  }

  static function insert_geom_3d_wall($doc ,$node ,$poslist ,$z ,$m){
    $xyz = array_chunk(explode(' ' ,$poslist) ,3);
    $h = $z +$m;
    for ($i=0 ; $i<count($xyz)-1 ; $i++) {
      foreach(array("gml:surfaceMember","gml:Polygon","gml:exterior","gml:LinearRing","gml:posList") as $str_elem){
        $node = $node->appendChild($doc->createElement($str_elem));
      }
      $node->nodeValue = "{$xyz[$i][0]} {$xyz[$i][1]} {$z} {$xyz[$i+1][0]} {$xyz[$i+1][1]} {$z} {$xyz[$i+1][0]} {$xyz[$i+1][1]} {$h} {$xyz[$i][0]} {$xyz[$i][1]} {$h} {$xyz[$i][0]} {$xyz[$i][1]} {$z}";
      $node = $node->parentNode->parentNode->parentNode->parentNode->parentNode;
    }
  }
  
  static function insert_geom_2d($doc ,$base_node ,$geom ,$hole ,$z ,$flg_xy_replace){
    if (empty($z)){$z=0;}

    $node = $base_node;
    foreach(array("gml:surfaceMember","gml:Polygon") as $str_elem){
      $node = $node->appendChild($doc->createElement($str_elem));
    }

    preg_match_all('/\([^\(\)]+?\)/' ,$geom ,$geom_array);
    foreach($geom_array[0] as $g){
      $g = preg_replace('/\(|\)/' ,'' ,$g);
      foreach(array("gml:exterior","gml:LinearRing","gml:posList") as $str_elem){
        $node = $node->appendChild($doc->createElement($str_elem));
      }
      if ($flg_xy_replace){
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.$z ,$g));
      } else {
        $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.$z ,$g));
      }
      $node->nodeValue = $xyz;
      $node = $node->parentNode->parentNode->parentNode;
    }

    if (!empty($hole)){
      preg_match_all('/\([^\(\)]+?\)/' ,$hole ,$geom_array2);
      foreach($geom_array2[0] as $g){
        $g = preg_replace('/\(|\)/' ,'' ,$g);
        foreach(array("gml:interior","gml:LinearRing","gml:posList") as $str_elem){
          $node = $node->appendChild($doc->createElement($str_elem));
        }
        if ($flg_xy_replace){
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+) ([0-9\.\-]+)/' ,'$2 $1 '.$z ,$g));
        } else {
          $xyz = preg_replace('/,/' ,' ' ,preg_replace('/([0-9\.\-]+ [0-9\.\-]+)/' ,'$1 '.$z ,$g));
        }
        $node->nodeValue = $xyz;
        $node = $node->parentNode->parentNode->parentNode;
      }
    }
  }
  
}