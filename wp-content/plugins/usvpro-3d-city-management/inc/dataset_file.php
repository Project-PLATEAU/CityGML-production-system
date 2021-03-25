<?php
class ksk3d_dataset_file{

  static function file2mesh($file1 ,$new_id ,$dmy1 ,$dmy2 ,$sw_header){
    $user_id = ksk3d_get_current_user_id();  
    $tbl_geom = KSK3D_TABLE_GEOM .$user_id ."_" .$new_id;

    if ($sw_header==1){
      ksk3d_DB_create_geom(
        $tbl_geom
      );
    }

    $filepath = pathinfo($file1);
    $fileinfo = explode('_' ,$filepath['filename']);

    $box = meshcode2box($fileinfo[0]);
    $coord = $box[1]." ".$box[0].",".$box[1]." ".$box[2].",".$box[3]." ".$box[2].",".$box[3]." ".$box[0].",".$box[1]." ".$box[0];

    global $wpdb;
    $sql = "INSERT INTO {$tbl_geom} (`the_geom`,z) VALUES (ST_GeomFromText('MULTIPOLYGON(((" .$coord .")))', 4326),1);";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);

    return true;
  }

  static function file2submesh($file1 ,$new_id ,$dmy ,$option2 ,$sw_header){
    $nx = $option2[0];
    $ny = $option2[1];

    $user_id = ksk3d_get_current_user_id();  
    $tbl_geom = KSK3D_TABLE_GEOM .$user_id ."_" .$new_id;

    if ($sw_header==1){
      ksk3d_DB_create_geom(
        $tbl_geom
      );
    }

    $filepath = pathinfo($file1);
    $fileinfo = explode('_' ,$filepath['filename']);

    $box = meshcode2box($fileinfo[0]);
    $dx = $box[3]-$box[1];
    $dy = $box[2]-$box[0];

    global $wpdb;
    $sql = "INSERT INTO {$tbl_geom} (`the_geom`) VALUES";
    for ($iy=0; $iy<$ny; $iy++){
    for ($ix=0; $ix<$ny; $ix++){
      $box2[0] = $box[0]+$dy/$ny*$iy;
      $box2[1] = $box[1]+$dx/$nx*$ix;
      $box2[2] = $box[0]+$dy/$ny*($iy+1);
      $box2[3] = $box[1]+$dx/$nx*($ix+1);
      $coord = $box2[1]." ".$box2[0].",".$box2[1]." ".$box2[2].",".$box2[3]." ".$box2[2].",".$box2[3]." ".$box2[0].",".$box2[1]." ".$box2[0];
      $sql .= "\n(ST_GeomFromText('MULTIPOLYGON(((" .$coord .")))', 4326)),";
    }}
    $sql = substr($sql ,0 ,-1) .";";
    ksk3d_log("sql:".$sql);
    $wpdb->query($sql);

    return true;
  }

}