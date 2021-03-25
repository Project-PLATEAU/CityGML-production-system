<?php
function ksk3d_db_maplay_insert($user_id="" ,$map_id ,$layer_id="" ,$file_id ,$display_name ,$argv){
  global $wpdb;
  $tbl_maplay = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;
  if (empty($user_id)){$user_id = ksk3d_get_current_user_id();}
  if (empty($layer_id)){$layer_id = ksk3d_get_max($tbl_maplay ,"layer_id" ,"map_id={$map_id}");}
  
  $sql_field = "";
  $sql_value = "";
  if (is_array($argv)){
    foreach($argv as $key=>$v){
      $sql_field .= "," .$key ;
      $sql_value .= ",'" .$v ."'";
    }
  }
  
  $sql = "INSERT INTO {$tbl_maplay} (user_id ,map_id ,layer_id ,file_id ,display_name {$sql_field}) VALUES ({$user_id} ,{$map_id} ,{$layer_id} ,{$file_id} ,'{$display_name}' {$sql_value})";
  ksk3d_log("sql:".$sql);
  $wpdb->query($sql);
}

class ksk3d_fn_db{
  static function ct_file_id($user_id,$file_id){
    global $wpdb;
    $table = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "SELECT COUNT(id) as ct,MIN(id) as min1 FROM {$table} WHERE user_id={$user_id} and file_id={$file_id};";
    return $wpdb->get_row($sql, ARRAY_N);
  }
  
  static function ins_dataset(){
    $user_id = ksk3d_get_current_user_id();
    global $wpdb;
    $table = $wpdb->prefix .KSK3D_TABLE_DATA;

    $file_id = ksk3d_get_max_file_id();
    $wpdb->insert($table,array('user_id'=>$user_id,'file_id'=>$file_id),array('%d','%d'));
    $id = $wpdb->insert_id;
    list($ct ,$min) = static::ct_file_id($user_id,$file_id);
    while($id != $min){
      $file_id = ksk3d_get_max_file_id();
      $wpdb->update($table,array('file_id'=>$file_id),array('%d'),array('id'=>$new_id),array('%d'));
      $id = $wpdb->insert_id;
      list($ct ,$min) = static::ct_file_id($user_id,$file_id);
    }

    return array($id ,$file_id);
  }

  static function sel($sql){
    global $wpdb;
    return $wpdb->get_results($sql ,ARRAY_A);
  }

  static function sel_data($id){
    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $id);
    return $wpdb->get_row($prepared ,ARRAY_A);
  }

  static function tbl_exists($tbl){
    global $wpdb;
    $sql = "show tables where Tables_in_".DB_NAME."='{$tbl}'";
    $result = $wpdb->get_row($sql ,ARRAY_A);
    return (!empty($result));
  }
}