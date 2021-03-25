<?php
class ksk3d_dataset_internal{
  static function attribute_copy($src_file_id ,$new_file_id){
    global $wpdb;
    $tbl_attribute = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
    $attribute_field = ksk3d_fn_db::sel("DESCRIBE ".$tbl_attribute);
    $field = "";
    $sel = "";
    foreach($attribute_field as $field){
      if ($field['Field']!='id'){
        $field .= $field['Field'] .",";
        if ($field['Field']=='file_id'){
          $sel .= "{$new_file_id} as file_id" .",";
        } else if ($field['Field']!='id'){
          $sel .= $field['Field'] .",";
        }
      }
    }
    $field = substr($field ,0 ,-1);
    $sel = substr($sel ,0 ,-1);
    $sql = <<<EOL
INSERT INTO {$tbl_attribute}({$field})
select {$sel}
from {$tbl_attribute}
WHERE user_id={$user_id} and file_id={$src_file_id};

EOL
;
    ksk3d_console_log("sql:".$sql);
    $wpdb->query($sql);
  }
  
  static function attributes_filter_not_geometry($attrib){
    return (
      (!isset($attrib['attrib_type'])) or
      (preg_match('/geometry/i' ,$attrib['attrib_type'])!=1)
    );
  }

  static function attributes_filter_int_or_double($attrib){
    return (
      (!isset($attrib['attrib_type'])) or
      (preg_match('/int|double/i' ,$attrib['attrib_type'])==1)
    );
  }
  
  static function sel_box($tbl ,$flg_xy_replace=false){
    if ($flg_xy_replace){
      $xmin = "ymin";
      $ymin = "xmin";
      $xmax = "ymax";
      $ymax = "xmax";
    } else {
      $xmin = "xmin";
      $ymin = "ymin";
      $xmax = "xmax";
      $ymax = "ymax";
    }
    
    $sql =<<< EOL
SELECT
  min(ksk_XMin(the_geom)) as {$xmin},
  min(ksk_YMin(the_geom)) as {$ymin},
  max(ksk_XMax(the_geom)) as {$xmax},
  max(ksk_YMax(the_geom)) as {$ymax},
  min(IF(m<0 ,COALESCE(z,0)+COALESCE(m,0) ,COALESCE(z,0))) as zmin,
  max(IF(m>0 ,COALESCE(z,0)+COALESCE(m,0) ,COALESCE(z,0))) as zmax
FROM {$tbl}
EOL
;
    global $wpdb;
    return $wpdb->get_row($sql ,ARRAY_A);
  }

  static function update_geom_ForceLHR($table){
    global $wpdb;
    $sql = "UPDATE {$table} SET the_geom=ksk_Reverce(the_geom) WHERE NOT(ksk_IsLeftHand(the_geom));";
    ksk3d_log("sql:".$sql);
    $result = $wpdb->query($sql);

    $sql = "UPDATE {$table} SET hole=ksk_Reverce(hole) WHERE NOT(ksk_IsLeftHand(hole));";
    ksk3d_log("sql:".$sql);
    $result += $wpdb->query($sql);

    return $result;
  }

  static function used_meshcode_list($table){
    global $wpdb;
    $sql = "SELECT distinct(meshcode) as meshcode FROM {$table}";
    $result = $wpdb->get_results($sql ,ARRAY_A);
    return $result;
  }

}