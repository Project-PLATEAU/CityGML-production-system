<?php
class ksk3d_functions_gml{
  static function gml2DB($filename ,$tbl_attrib ,$tbl_geom ,$set_attrib) {
    ksk3d_console_log("filename1:".$filename);
    global $wpdb;

    $i = 0;
    $i2 = 0;
    $sql_a = "";
    $sql_g = "";

    $sql_a_field = "";
    foreach($set_attrib as $attrib){
      if (preg_match('/geometry/i' ,$attrib["attrib_type"])==0){
        $sql_a_field .= $attrib["field_name"] .",";
      }
    }
    $sql_a_field = substr($sql_a_field ,0 ,-1);
    ksk3d_console_log("sql_a");
    ksk3d_console_log($sql_a_field);


    $doc = new DOMDocument();
    $doc->load($filename);
    $xpath = new DOMXpath($doc);
    $featureMembers = $xpath->query("gml:featureMember");

    foreach ($featureMembers as $featureMember){
      $feature_path = $featureMember->getNodePath()."/";
      $sql_a_value = "";
      $coord = "";
      $coord2 = "";
      foreach($set_attrib as $attrib){
        if (preg_match('/geometry/i' ,$attrib['attrib_type'])==1){
          $tag_path = preg_replace('/^.+?\//' ,'.//' ,$attrib['tag_path']);
          $geom = $xpath->query($tag_path .'//gml:outerBoundaryIs//gml:coordinates' ,$featureMember);

          ksk3d_console_log(array(
            'function' => __FUNCTION__,
            '$attrib[\'tag_path\']' => $attrib['tag_path'],
            '$tag_path' => $tag_path,
            '$geom count' => count($geom)
          ));
          $zmin = "";
          $zmax = "";
          $zmin1 = "";
          $zmax1 = "";
          if (count($geom)>0){
            foreach ($geom as $g){
              $xy = preg_replace('/([^\s]+),([^\s]+)\s+/' ,'${1} ${2},' ,trim($g->nodeValue)." ");
              $xy = preg_replace('/^,|,(\t*?)$/' ,'' ,$xy);
              $coord .= "({$xy}),";
            }
          
            $tag_path = preg_replace('/^.+?\//' ,'.//' ,$attrib['tag_path']);
            $geom = $xpath->query($tag_path .'//gml:innerBoundaryIs//gml:coordinates' ,$featureMember);
            if (count($geom)>0){
              foreach ($geom as $g){
                $xy = implode(" " ,array_reverse(explode(" " ,trim($g->nodeValue))));
                $xy = preg_replace("/([^\s]+),([^\s]+)\s+/" ,'${1} ${2},' ,$xy." ");
                $xy = preg_replace('/^,|,(\t*?)$/' ,'' ,$xy);
                $coord2 .= "({$xy}),";
              }
              $coord2 = "ST_GeomFromText('MULTIPOLYGON((" .substr($coord2 ,0 ,-1) ."))', 4326)";
            } else {
              $coord2 = "NULL";
            }
          
            $sql_g .= "\n(ST_GeomFromText('MULTIPOLYGON((" .substr($coord ,0 ,-1) ."))', 4326) ,{$coord2} ,'0' ,'0'),";
          }
        } else {
          if (isset($attrib['tag_attrib']) && !empty($attrib['tag_attrib'])){
              $tag_path = preg_replace('/(Attribute)\[.*?\]/i' ,'$1[@'.$attrib['tag_attrib'].'=\''.$attrib['tag_attrib_name'].'\']' ,$attrib['tag_path']);
              $tag_path = preg_replace('/^.+?\//' ,'.//' ,$tag_path);
              $query = $xpath->query($tag_path ,$featureMember);
              if ($query->length > 0){
                $tag = $query->item(0)->nodeValue;
              } else {
                $tag = "NULL";
              }
          } else {
            $path = preg_replace("/^.+?\//" ,'.//' ,$attrib['tag_path']);
            $query = $xpath->query($path ,$featureMember);
            if ($query->length > 0){
              $tag = $query->item(0)->nodeValue;
            } else {
              $tag = "NULL";
            }
          }
          if (preg_match('/char/i' ,$attrib["attrib_type"]) and $tag != "NULL"){
            $tag = "'" .$tag ."'";
          }
          $sql_a_value .= "," .$tag ;
        }
      }
      $i++;
      $sql_a .= "(" .substr($sql_a_value ,1) ."),";

      if ($i>99) {
        $i2+=$i;
        $i=0;
        if (!empty($sql_a_field)){
          $sql_a = "INSERT INTO {$tbl_attrib} ({$sql_a_field}) VALUES " .substr($sql_a ,0 ,-1);
          ksk3d_log( "sql:" .$sql_a );
          $wpdb->query($sql_a);
          $sql_a = "";
        }
        if (!empty($sql_g)){
          $sql_g = "INSERT INTO {$tbl_geom} (the_geom ,hole ,z ,m) VALUES " .substr($sql_g ,0 ,-1);
          ksk3d_log( "sql:" .$sql_g );
          $wpdb->query($sql_g);
          $sql_g = "";
        }
      }
    }
    if ($i>0){
      $i2+=$i;
      if (!empty($sql_a_field)){
        $sql_a = "INSERT INTO {$tbl_attrib} ({$sql_a_field}) VALUES " .substr($sql_a ,0 ,-1);
        ksk3d_log( "sql:" .$sql_a );
        $wpdb->query($sql_a);
      }
      if (!empty($sql_g)){
        $sql_g = "INSERT INTO {$tbl_geom} (the_geom ,hole ,z ,m) VALUES " .substr($sql_g ,0 ,-1);
        ksk3d_log( "sql:" .$sql_g );
        $wpdb->query($sql_g);
      }
    }
    $message = "データを".$i2 ."行追加しました。<br>\n";
    ksk3d_log( "insert:{$i2}行追加しました。");

    return array(
      true,
      'message'=>$message
    );
  }

  static function internal($file1 ,$new_id ,$set_attrib ,$dmy ,$sw_header){
    ksk3d_console_log(array(
      'function'=>__FUNCTION__,
      '$file1'=>$file1,
      '$new_id'=>$new_id,
      '$set_attrib'=>$set_attrib,
      '$sw_header'=>$sw_header
    ));
    $user_id = ksk3d_get_current_user_id();  
    if ($sw_header==1){
      ksk3d_DB_create_attrib(
        KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
        $set_attrib
      );
      
      ksk3d_DB_create_geom(
        KSK3D_TABLE_GEOM .$user_id ."_" .$new_id
      );

      ksk3d_DB_insert_attrib(
        $user_id,
        $new_id,
        $set_attrib
      );
    }

    $result = static::gml2DB(
      $file1,
      KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
      KSK3D_TABLE_GEOM .$user_id ."_" .$new_id,
      $set_attrib
    );
    
    return $result;
  }

  static function vertices_xy_replace($file1 ,$file2){
    ksk3d_log("fn:ksk3d_gml_xy_replace:({$file1} ,{$file2})");

    $doc = new DOMDocument();
    $doc->load($file1);
    $xpath = new DOMXpath($doc);
  
    foreach(array("//gml:Envelope/gml:lowerCorner","//gml:Envelope/gml:upperCorner") as $tag_coord){
      $posNodeList = $xpath->query($tag_coord);
      $posNodeList->item(0)->nodeValue = ksk3d_dataset_gml::poslist_xyz_yxz($posNodeList->item(0)->nodeValue);
    }

    $posList = $xpath->query('//gml:posList');
    $pos = $xpath->query('//gml:pos');
    foreach(array($posList ,$pos) as &$posNodeList){
      for($i=0; $i<$posNodeList->length; $i++){
        $posNodeList->item($i)->nodeValue = ksk3d_dataset_gml::poslist_xyz_yxz($posNodeList->item($i)->nodeValue);
      }
    }
    $doc->save($file2);
    return true;
  }

  static function verticesReverse($file1 ,$file2){
    ksk3d_log("fn:ksk3d_functions_citygml::verticesReverse:({$file1} ,{$file2})");

    $doc = new DOMDocument();
    $doc->load($file1);
    $xpath = new DOMXpath($doc);

    $posList = $xpath->query('//gml:posList');
    foreach($posList as &$posNodeList){
      for($i=0; $i<$posNodeList->length; $i++){
        $poslist = $posNodeList->item($i)->nodeValue;
        $posNodeList->item($i)->nodeValue = ksk3d_dataset_gml::poslist_verticesReverse($poslist);
      }
    }
    $doc->save($file2);
    return true;
  }

  static function test($filename)
  {
    if (preg_match('/\*/', $filename) == 1) {
      foreach (glob($filename) as $f) {
        if (is_file($f)) {
          $filename = $f;
          break 1;
        }
      }
    }

    ksk3d_console_log("filename:" . $filename);
    if (!file_exists($filename)) {
      ksk3d_console_log("file is not found.");
      return false;
    }

    global $wpdb;
    $result = [];
    $i = 0;

    $doc = new DOMDocument();
    $doc->load($filename);
    $xpath = new DOMXpath($doc);
    $featureMember = $xpath->query("gml:featureMember")->item(0);

    $feature = "";
    $items = $featureMember->childNodes;
    for ($i = 0; $i < $items->length; $i++) {
      if (get_class($items->item($i)) == "DOMElement") {
        $feature = $items->item($i);
        break;
      }
    }
    if (empty($feature)) {
      return false;
    }

    $feature_path = preg_replace('/^(\/.+?\/.+?\/).*/', "$1", $feature->getNodePath());
    $tag = preg_replace('/^\/.+?\/.+?\//', '', $feature->getNodePath());
    $path = $feature_path . "" . $tag;
    
    ksk3d_console_log(array(
      '$feature_path'=>$feature_path,
      '$tag'=> $tag,
      '$path'=> $path
    ));
    

    $gml_id = $feature->getAttribute("fid");
    if(empty($gml_id)) {
      $gml_id = $feature->getAttribute("gml:id");
    }
    
    if (!empty($gml_id)) {
      $field = mb_strtolower(substr(preg_replace('/^.+(:|\/)/', '', $tag), 0, 7));
      if (isset($ct[$field])) {
        $ct[$field]++;
      } else {
        $ct[$field] = 1;
      }
      $result[] = [
        'tag_name' => "@gml:id",
        'tag_path' => $tag . "/@fid",
        'tag_attrib' => "gml:id",
        'field_name' => "gml_id",
        'attrib_type' => "",
        'attrib_name' => "gml_id",
        'codelist' => "",
        'attrib_value' => $gml_id
      ];
    }

    $ct_geom = 0;
    $attributes = $feature->childNodes;
    foreach ($attributes as $attrib) {
      if (get_class($attrib) == "DOMElement") {
        if (preg_match('/:geometryProperty/i', $attrib->getNodePath()) == 1) {
          $tag_path = preg_replace('/^\/.+?\/.+?\//', '', $attrib->getNodePath());
          $tag_name = preg_replace('/^.+\/|.+\:/', '', $tag_path);
          $result[] = [
            'tag_name' => $tag_name,
            'tag_path' => $tag_path,
            'field_name' => "the_geom",
            'attrib_type' => "GEOMETRY",
            'attrib_name' => "空間データ",
            'codelist' => "",
            'attrib_value' => $attrib->nodeValue
          ];
        } else {
          $result = array_merge($result, ksk3d_citygml_getattrib($xpath->query($attrib->getNodePath())));
        }
      }
    }

    for ($i = 0; $i < count($result); $i++) {
      $result[$i]['tag_name'] = preg_replace('/^.+\:/', '', $result[$i]['tag_name']);
      if (isset($ct[$result[$i]['field_name']])) {
        $ct[$result[$i]['field_name']]++;
      } else {
        $ct[$result[$i]['field_name']] = 1;
      }
      if ($ct[$result[$i]['field_name']] > 1) {
        $result[$i]['field_name'] .= $ct[$result[$i]['field_name']];
      }
    }

    return $result;
  }

}