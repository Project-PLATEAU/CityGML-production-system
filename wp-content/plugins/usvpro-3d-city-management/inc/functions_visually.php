<?php
class ksk3d_functions_visually{
  static $ini_color = [
    'bldg'  =>"{'color':'rgba(255 ,255  ,128  ,1)'}",
    'tran'  =>"{'color':'rgba(64  ,64   ,64   ,1)'}",
    'luse'  =>"{'color':'rgba(128 ,128  ,0    ,1)'}",
    'fld'   =>"{'color':'rgba(0   ,128  ,255  ,0.8)'}",
    'tnm'   =>"{'color':'rgba(0   ,0    ,255  ,0.8)'}",
    'lsld'  =>"{'color':'rgba(255 ,128  ,0    ,0.8)'}",
    'urf'   =>"{'color':'rgba(255 ,0    ,0    ,0.8)'}",
    'dem'   =>"{'color':'rgba(0   ,128  ,0    ,1)'}"
  ];
  
  static function pre_convert_data_bgexec($userID ,$pjt_id ,$dataset){
    $text = "";
    global $wpdb;

    $tbl_maplay = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;
    $tbl_ck_result = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;

    $tbl_name = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
    $sql = "SELECT * FROM {$tbl_name} WHERE user_id={$userID} and file_id={$dataset['dataset_id2']} order by attrib_id;";
    ksk3d_console_log($sql);
    $attrib = $wpdb->get_results($sql, ARRAY_A);
    ksk3d_console_log($attrib);
    $result = ksk3d_citygml23DTiles_ex($dataset['file_id'] ,true ,$attrib);
    $text .= $result[0];
    $file_id2 = $result[1];
    
    $sql = "UPDATE {$tbl_ck_result} SET dataset_id2={$file_id2} WHERE user_id={$userID} and pjt_id={$pjt_id} and dataset_id={$dataset['file_id']}";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);
    
    $sql = "DELETE FROM {$tbl_maplay} WHERE user_id={$userID} and layer_id=1 and map_id in (SELECT map_id FROM {$tbl_ck_result} WHERE user_id={$userID} and pjt_id={$pjt_id} and dataset_id2={$file_id2})";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);
    
    $name = explode('_' ,$dataset['file_name']."_");
    ksk3d_log("name:".$name[1]);
    if (isset(static::$ini_color[$name[1]])){
      $color_exp = "'".preg_replace("/'/" ,"\'" ,static::$ini_color[$name[1]])."'";
    } else {
      $color_exp = "null";
    }
    ksk3d_log("color_exp:".$color_exp);
    
    $sql = "INSERT INTO {$tbl_maplay} (user_id, map_id, layer_id, file_id, display_name, color_exp)
      SELECT {$userID}, map_id, 1, {$file_id2},'{$dataset['display_name']}',{$color_exp}
      FROM {$tbl_ck_result} WHERE user_id={$userID} and pjt_id={$pjt_id} and dataset_id2={$file_id2}
      ";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);

    return $text;
  }
  
  static function pre_create_mesh_exec($user_id, $file_id, $map_id_array){
    global $wpdb;
    $tbl_maplay = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;

    $result = ksk3d_conv($file_id ,"" ,"ksk3d_dataset_file::file2mesh" ,array("メッシュ","内部データセット") ,"" ,"");
    $mesh_id = $result[0];
    $text = $result[1];

    $result = ksk3d_conv($file_id ,"" ,"ksk3d_dataset_file::file2submesh" ,array("サブメッシュ","内部データセット") ,"" ,array(10,10));
    $smesh_id = $result[0];
    $text .= $result[1];

    foreach($map_id_array as $map_id){
      $sql = "DELETE FROM {$tbl_maplay} WHERE user_id={$user_id} and map_id={$map_id} and (display_name like '%メッシュ%' or display_name is null);";
      ksk3d_console_log($sql);
      ksk3d_log($sql);
      $wpdb->query($sql);

      $color = "{'material':'0,0,0,0','outline':'1','outlineWidth':'1','outlineColor':'1,0,1,1'}";
      $color2 = preg_replace("/'/" ,"\'" ,$color);
      ksk3d_db_maplay_insert(
        $user_id,
        $map_id,
        "",
        $mesh_id,
        "メッシュ",
        array(
          'color_exp'=>$color2
        )
      );

      $color = "{'material':'0,0,0,0','outline':'1','outlineWidth':'1','outlineColor':'0,0,0,1'}";
      $color2 = preg_replace("/'/" ,"\'" ,$color);
      ksk3d_db_maplay_insert(
        $user_id,
        $map_id,
        "",
        $smesh_id,
        "サブメッシュ",
        array(
          'color_exp'=>$color2
        )
      );
    }
  }
    
  static function set_attrib_bgexec($pjtdata_id ,$set_attrib){

    $user_id = ksk3d_get_current_user_id();
    global $wpdb;
    $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;
    $tbl_pjtdata = $wpdb->prefix .KSK3D_TABLE_PJT_DATA;
    $sql = "SELECT id FROM {$tbl_data} WHERE user_id={$user_id} and file_id = (SELECT dataset_id FROM {$tbl_pjtdata} WHERE id={$pjtdata_id});";
    $src_id = $wpdb->get_var($sql);
    ksk3d_log("src_id:".$src_id);

    $result = ksk3d_conv(
        $src_id,
        "",
        "ksk3d_functions_citygml::internal",
        array("属性項目設定","内部データセット"),
        $set_attrib
    );

    $sql = "UPDATE {$tbl_pjtdata} SET dataset_id2={$result[0]} WHERE id={$pjtdata_id}";
    ksk3d_log($sql);
    $wpdb->query($sql);
    
  }
  
}