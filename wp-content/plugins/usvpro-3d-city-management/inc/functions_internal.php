<?php
class ksk3d_functions_internal{
  static function attrib_agg_ksk($file_id ,$new_id ,$input_option ,$output_option){
    $user_id = ksk3d_get_current_user_id();
    $meshsize = $output_option['meshcode'];
    static::update_meshcode($user_id ,$file_id ,$meshsize);
    
    $set_attrib = $input_option;
    array_push(
      $set_attrib,
      array(
        'field_name'=>'meshcode',
        'attrib_field'=>'meshcode',
        'attrib_type'=>'varchar',
        'attrib_digit'=>'10',
        'attrib_name'=>'メッシュコード',
        'tag_name'=>'meshcode',
        'field'=>'B.meshcode'
      )
    );
    ksk3d_DB_insert_attrib($user_id ,$new_id ,$set_attrib);
    
    $tbl_attrib2 = KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id;
    ksk3d_DB_create_attrib($tbl_attrib2 ,$set_attrib);

    $tbl_attrib1 = KSK3D_TABLE_ATTRIB .$user_id ."_" .$file_id;
    $sel = "";
    $field = "";
    foreach ($set_attrib as $attrib){
      $sel .= $attrib['field'] .",";
      $field .= $attrib['attrib_field'] .",";
    }
    $sel = substr($sel ,0 ,-1);
    $field = substr($field ,0 ,-1);

    $tbl_geom1 = KSK3D_TABLE_GEOM .$user_id ."_" .$file_id;
    ksk3d_fn_db::sel("insert into {$tbl_attrib2} ({$field}) select {$sel} from {$tbl_attrib1} A left join {$tbl_geom1} B  on A.id=B.id group by B.meshcode;");

    $tbl_geom2 = KSK3D_TABLE_GEOM .$user_id ."_" .$new_id;
    ksk3d_DB_create_geom($tbl_geom2);

    $times = $output_option['high_times'];
    $high = $output_option['high_attrib'];
    $sql = "insert into {$tbl_geom2} (id,the_geom,m,meshcode) select id,ksk_GeomFromMeshcode(meshcode),{$high}*{$times},meshcode from {$tbl_attrib2} order by id;";
    ksk3d_console_log("sql:".$sql);
    ksk3d_fn_db::sel($sql);
  }

  static function export_ksk_bg($user_id ,$form_id ,$set_mesh ,$attrib_high ,$attrib){
    $text = "";
    global $wpdb;
    
    $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;

    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_internal::attrib_agg_ksk" ,array("3Dグラフ","内部データセット","内部データセット") ,$attrib ,$set_mesh);
    $intermediate1 = $result[0];
    $text .= $result[1];

    $set_feature = [
      'template'=>"building",
      'filename'=>'[meshcode]_bldg_6697.gml',
      'feature'=>'bldg:Building',
      'lod'=>'bldg:lod1Solid',
      'geom'=>'gml:Solid',
      'srs'=>'WGS84',
      'mesh'=>1
    ];
    $set_attrib = ksk3d_functions_internal::sel_attrib($user_id ,$intermediate1);
    ksk3d_console_log("set_attrib");
    ksk3d_console_log($set_attrib);
    
    $filename = preg_replace('/\[meshcode\]/' ,'*' ,$set_feature['filename']);
    $result = ksk3d_fn_db::sel("select id from {$tbl_data} where user_id={$user_id} and file_id={$intermediate1};");
    $result = ksk3d_conv($result[0]['id'] ,"" ,"ksk3d_functions_internal::export_mesh_citygml" ,array("citygml","CityGML",$filename) ,"" ,array($set_feature,$set_attrib));
    $intermediate2 = $result[0];
    $text .= $result[1];

    $result = ksk3d_citygml23DTiles_ex($intermediate2, false, "", 1000);
    $text .= $result[0];

    $wpdb->query("delete from {$tbl_data} where user_id={$user_id} and file_id in ({$intermediate1},{$intermediate2})");

    return $text;
  }

  static function export_mesh_citygml($file_id ,$dr2 ,$dmy ,$output_option){
    $user_id = ksk3d_get_current_user_id();
    $set_feature = $output_option[0];
    $set_attrib = $output_option[1];

    ksk3d_console_log("set_attrib");
    ksk3d_console_log($set_attrib);

    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE_TPL_VALUE;
    $sql="";
    $ct = array();
    $set_attrib2 = array();
    foreach($set_attrib as $attrib){
      ksk3d_console_log("attrib");
      ksk3d_console_log($attrib);
      ksk3d_console_log("tag_name:".$attrib['tag_name']);
      $sql = "SELECT rank FROM {$tbl_name} WHERE tag_name = '".$attrib["tag_name"]."';";
      ksk3d_console_log("sql:".$sql);
      
      $rank = $wpdb->get_var($sql);
      if (empty($rank)){
        if (preg_match('/gml:/',$attrib["tag_name"])==1){
          $rank=100;
        } else if (preg_match('/core:/',$attrib["tag_name"])==1){
          $rank=200;
        } else if (preg_match('/gen:/',$attrib["tag_name"])==1){
          $rank=300;
        } else if (preg_match('/bldg:/',$attrib["tag_name"])==1){
          $rank=400;
        } else if (preg_match('/uro:/',$attrib["tag_name"])==1){
          $rank=500;
        } else {
          $rank=9999;
        }
      }
      while (isset($ct) and isset($ct[$rank]) and $ct[$rank]==1){$rank++;}
      $ct[$rank]=1;
      ksk3d_console_log("rank:".$rank);
      $set_attrib2[$rank] = $attrib;
    }
    ksort($set_attrib2);
      ksk3d_console_log("set_attrib2");
      ksk3d_console_log($set_attrib2);
    

    if (preg_match('/gml:Solid/i' ,$set_feature['geom'])==1){
      $dim = 3;
      $tag_path2 = "/gml:exterior/gml:CompositeSurface";
    } else {
      $dim = 2;
      $tag_path2 = "";
    }

    $flg_xy_replace = (preg_match('/\/6668|\/6697/i' ,$set_feature['srs'])==1);

    $tbl_geom = KSK3D_TABLE_GEOM .$user_id ."_" .$file_id;

    static::update_meshcode($user_id ,$file_id ,$set_feature['mesh']);

    ksk3d_dataset_internal::update_geom_ForceLHR($tbl_geom);

    $meshcodes = ksk3d_dataset_internal::used_meshcode_list($tbl_geom);
    ksk3d_console_log($meshcodes);

    $sel = "ST_AsTEXT(A.the_geom) as the_geom,ST_AsTEXT(A.hole) as hole,A.z,A.m";
    $tbl_attrib = KSK3D_TABLE_ATTRIB .$user_id ."_" .$file_id;
    $tbl_attrib_exists = ksk3d_fn_db::tbl_exists($tbl_attrib);
    if ($tbl_attrib_exists){
      $join = "LEFT OUTER JOIN {$tbl_attrib} B ON A.id=B.id";
      $sel .= ",B.*";
    } else {
      $join = "";
    }
    mkdir ($dr2."/Intermediate/");
    foreach($meshcodes as $meshcode){
      $meshcode = $meshcode['meshcode'];
      
      $doc = new DOMDocument();
      $doc->preserveWhiteSpace = false;
      $doc->formatOutput = true;
      $doc->load(KSK3D_PATH ."/storage/citygml/" .$set_feature['template'] .".gml");
      $xpath = new DOMXpath($doc);

      $CityModel = $doc->getElementsByTagName('CityModel')->item(0);
      $cityObjectMember_1 = $doc->getElementsByTagName('cityObjectMember')->item(0);
      $str_cityObjectMember = $cityObjectMember_1->nodeName;
      $CityModel->removeChild($cityObjectMember_1);
      
      $tag_path =  $set_feature['lod'] ."/" .$set_feature['geom'] .$tag_path2;
      
      $xpath->query("//gml:Envelope")->item(0)->setAttribute("srsName" ,$set_feature['srs']);

      if (empty($meshcode)){
        $wh = "A.meshcode is null";
      } else {
        $wh = "A.meshcode=".$meshcode;
      }

      $box = ksk3d_dataset_internal::sel_box("{$tbl_geom} A WHERE {$wh}" ,$flg_xy_replace);
      $xpath->query("//gml:Envelope/gml:lowerCorner")->item(0)->nodeValue = $box['xmin'] ." " .$box['ymin'] ." " .$box['zmin'];
      $xpath->query("//gml:Envelope/gml:upperCorner")->item(0)->nodeValue = $box['xmax'] ." " .$box['ymax'] ." " .$box['zmax'];

      $rows = ksk3d_fn_db::sel("SELECT {$sel} FROM {$tbl_geom} A {$join} WHERE {$wh} ORDER BY A.id");
      foreach($rows as $row){
        $base_node = ksk3d_dataset_gml::node_appendChild_path($doc ,$CityModel ,$str_cityObjectMember ."/" .$set_feature['feature']);
        $geom_node = ksk3d_dataset_gml::node_appendChild_path($doc ,$base_node ,$tag_path);

        if ($dim==3){
          ksk3d_dataset_citygml::insert_geom_3d($doc ,$geom_node ,$row['the_geom'] ,$row['hole'] ,$row['z'] ,$row['m'] ,$flg_xy_replace);
        } else {
          ksk3d_dataset_citygml::insert_geom_2d($doc ,$geom_node ,$row['the_geom'] ,$row['hole'] ,$row['z'] ,$flg_xy_replace);
        }
        foreach($set_attrib2 as $attrib){
          ksk3d_console_log("attrib");
          ksk3d_console_log($attrib);
          if (
            ($row[$attrib['attrib_field']]!='')
            and (!empty($attrib['tag_name']))
          ){
            ksk3d_dataset_citygml::insert_attrib($doc ,$base_node ,$attrib['tag_name'] ,$row[$attrib['attrib_field']] ,$attrib['attrib_unit'] ,$attrib['codelist_name'] ,$attrib['attrib_name']);
          }
        }

      }

      $f2 = preg_replace('/\[meshcode\]/' ,$meshcode ,$set_feature['filename']);
      $doc->save($dr2."/Intermediate/".$f2);

      ksk3d_functions_citygml::tagsort($dr2."/Intermediate/".$f2, $dr2."/".$f2);
      
    }
    ksk3d_deltree($dr2."/Intermediate/");
  }

  static function join_attrib($src_id ,$new_id ,$input_option ,$output_option){
    $result = "";
    $user_id = ksk3d_get_current_user_id();
    $ref_id = $input_option['ref_id'];

    global $wpdb;

    $set_attrib1 = static::sel_attrib($user_id ,$src_id);
    $sel1 = ",";
    foreach($set_attrib1 as $attrib){
      $sel1 .= $attrib['attrib_field'].",";
    }
    $ct1 = count($set_attrib1);

    $set_attrib2 = static::sel_attrib($user_id ,$ref_id);
    $sel2 = "";
    foreach($set_attrib2 as $attrib){
      if (preg_match('/,'.$attrib['attrib_field'].',/' ,$sel1)==1){
        $sel2 .= "B.{$attrib['attrib_field']} as {$attrib['attrib_field']}_2,";
      } else {
        $sel2 .= "B.".$attrib['attrib_field'].",";
      }
    }
    $sel2 = substr($sel2 ,0 ,-1);

    $tbl_attribute = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
    $attribute_field = ksk3d_fn_db::sel("DESCRIBE ".$tbl_attribute);
    $sel0 = "";
    foreach($attribute_field as $field){
      if ($field['Field']!='id'){
        $sel0 .= $field['Field'] .",";
      }
    }
    $sel0 = substr($sel0 ,0 ,-1);
    $sel = $sel0;
    $sel = preg_replace('/file_id/' ,"{$new_id} as file_id" ,$sel);
    $sql = <<<EOL
INSERT INTO {$tbl_attribute}({$sel0})
select {$sel}
from {$tbl_attribute}
WHERE user_id={$user_id} and file_id={$src_id};

EOL
;
    ksk3d_console_log("sql:".$sql);
    $wpdb->query($sql);

    $sel = preg_replace('/attrib_id/' ,"attrib_id+{$ct1} as attrib_id" ,$sel);
    $sql = <<<EOL
INSERT INTO {$tbl_attribute}({$sel0})
select {$sel}
from {$tbl_attribute}
WHERE user_id={$user_id} and file_id={$ref_id};

EOL
;
    ksk3d_console_log("sql:".$sql);
    $wpdb->query($sql);

    $tbl_geom1 = KSK3D_TABLE_GEOM .$user_id ."_" .$src_id;
    $tbl_geom2 = KSK3D_TABLE_GEOM .$user_id ."_" .$new_id;
    $sql = <<<EOL
create table {$tbl_geom2} as select * from {$tbl_geom1};

EOL
;
    ksk3d_console_log("sql:".$sql);
    $wpdb->query($sql);

    $tbl_attrib1 = KSK3D_TABLE_ATTRIB .$user_id ."_" .$src_id;
    $tbl_attrib3 = KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id;
    $tbl_attrib2 = KSK3D_TABLE_ATTRIB .$user_id ."_" .$ref_id;
    $dataset1_key = $input_option['dataset1_key'];
    $dataset2_key = $input_option['dataset2_key'];
    $sql = <<<EOL
create table {$tbl_attrib3} as
select A.*,{$sel2} from {$tbl_attrib1} A left join {$tbl_attrib2} B
on A.{$dataset1_key}=B.{$dataset2_key}
where A.id;

EOL
;
    ksk3d_console_log("sql:".$sql);
    $wpdb->query($sql);

    return $result;
  }

  static function sel_attrib($user_id ,$file_id){
    if (empty($user_id)){$user_id = ksk3d_get_current_user_id();}
    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
    $sql = "SELECT * FROM {$tbl_name} WHERE user_id={$user_id} and file_id={$file_id} order by attrib_id;";
    ksk3d_console_log("sql:".$sql);
    $result = $wpdb->get_results($sql ,ARRAY_A);
    return $result;
  }

  static function update_elevation($src_id ,$set_high){
    $user_id = ksk3d_get_current_user_id();
    
    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "SELECT file_path,file_name,file_id FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $src_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    $file_id = $result['file_id'];

    $tbl_g = KSK3D_TABLE_GEOM .$user_id ."_" .$file_id;
    if ($set_high['menu']==1){
      $tbl_a = KSK3D_TABLE_ATTRIB .$user_id ."_" .$file_id;
      $sql = "UPDATE {$tbl_g} a LEFT JOIN {$tbl_a} b ON a.id=b.id SET Z={$set_high['value1_field']}*{$set_high['value1_times']};";
    } else {
      $sql = "UPDATE {$tbl_g} SET Z={$set_high['value2']};";
    }
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    return true;
  }

  static function update_feature_height($src_id ,$set_high){
    $user_id = ksk3d_get_current_user_id();
    
    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "SELECT file_path,file_name,file_id FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $src_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    $file_id = $result['file_id'];

    $tbl_g = KSK3D_TABLE_GEOM .$user_id ."_" .$file_id;
    if ($set_high['menu']==1){
      $tbl_a = KSK3D_TABLE_ATTRIB .$user_id ."_" .$file_id;
      $sql = "UPDATE {$tbl_g} a LEFT JOIN {$tbl_a} b ON a.id=b.id SET M={$set_high['value1_field']}*{$set_high['value1_times']};";
    } else {
      $sql = "UPDATE {$tbl_g} SET M={$set_high['value2']};";
    }
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    return true;
  }

  static function update_meshcode($user_id ,$file_id ,$meshsize){
    if (empty($user_id)){$user_id = ksk3d_get_current_user_id();}
    global $wpdb;
    
    $tbl_geom = KSK3D_TABLE_GEOM .$user_id ."_" .$file_id;
    $sql = "UPDATE {$tbl_geom} SET meshcode=ksk_MeshcodeByCentroid(the_geom ,{$meshsize});";
    ksk3d_log("sql:".$sql);
    $wpdb->query($sql);
    
    $box = ksk3d_dataset_internal::sel_box($tbl_geom);
    $pos = (($box['xmax']+$box['xmin'])/2).",".(($box['ymax']+$box['ymin'])/2)."," .round(($box['xmax']-$box['xmin'])*250000);
    
    $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "UPDATE {$tbl_data} SET meshsize={$meshsize},camera_position='{$pos}'  WHERE user_id={$user_id} and file_id={$file_id};";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);
    
    return true;
  }

}