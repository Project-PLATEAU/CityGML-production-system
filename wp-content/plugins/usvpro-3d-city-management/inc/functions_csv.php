<?php
class ksk3d_functions_csv{
  static function csv2attrib($file1 ,$tbl_attrib ,$set_attrib){
    ksk3d_console_log ("test11");
    global $wpdb;
    
    $sql0 = "";
    foreach($set_attrib as $attrib){
      $sql0 .= $attrib['field_name'] .",";
    }
    $sql0 = "INSERT INTO {$tbl_attrib}(" .substr($sql0 ,0 ,-1) .") VALUES ";

    $file = new SplFileObject($file1); 
    $file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE); 
    ksk3d_console_log($file);

    $i=0; $i2=0; $sql="";
    foreach ($file as $line) {
      if ($file->key() > 0 && ! $file->eof()) {
        $sql .= "\n(".$line."),";
        $i++;
        if ($i>99) {
          $i2+=$i;
          $i=0;
          $sql = $sql0 .substr($sql ,0 ,-1);
          $wpdb->query($sql);
          $sql="";
        }
      }
    } 
    if ($i>0){
      $i2+=$i;
      $sql = $sql0 .substr($sql ,0 ,-1);
      ksk3d_log("sql:".$sql);
      $wpdb->query($sql);
    }
    $message = "属性データを".$i2 ."行追加しました。<br>\n";
    ksk3d_log( "insert:{$i2}行追加しました。");
    
    return array(
      true,
      'message'=>$message
    );
  }

  static function internal($file1 ,$new_id ,$set_attrib ,$dmy ,$sw_header){
    $user_id = ksk3d_get_current_user_id();  
    if ($sw_header==1){
      ksk3d_DB_create_attrib(
        KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
        $set_attrib
      );
      
      ksk3d_DB_insert_attrib(
        $user_id,
        $new_id,
        $set_attrib
      );
    }
      
    $result = static::csv2attrib(
      $file1,
      KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
      $set_attrib
    );
    
    return $result;
  }

  static function test($filename) {
    if (preg_match('/\*/' ,$filename)==1){
      foreach(glob($filename) as $f) {
        if (is_file($f)) {
          $filename = $f;
          break 1;
        }
      }
    }
    
    ksk3d_console_log("filename:".$filename);
    if (!file_exists($filename)){
      ksk3d_console_log("file is not found.");
      return false;
    }

    global $wpdb;
    $ct_field_name = [];
    $result = [];
    $i = 0;

    $fh = fopen($filename, "r");
    $header = fgets($fh);
    $sample = fgets($fh);
    $header_array = explode(',' ,$header);
    $sample_array = explode(',' ,$sample);
    for($i=0; $i<count($header_array); $i++){
      //$header_array[$i] = preg_replace('/\'|\"/' ,'' ,$header_array[$i]);
      $header_array[$i] = preg_replace('/\'|\"|\r|\n/' ,'' ,$header_array[$i]);
      //$sample_array[$i] = preg_replace('/\'|\"/' ,'' ,$sample_array[$i]);
      $sample_array[$i] = preg_replace('/\'|\"|\r|\n/' ,'' ,$sample_array[$i]);
      $field = mb_strtolower(substr(preg_replace('/:/' ,'_' ,$header_array[$i]) ,0 ,7));

      $result[] = [
        'tag_name' => $header_array[$i],
        'field_name' => $field,
        'codelist' => "",
        'attrib_value' => $sample_array[$i] 
      ];
    }
    fclose($fh);

    for($i=0;$i<count($result);$i++){
      if (isset($ct[$result[$i]['field_name']])){
        $ct[$result[$i]['field_name']]++;
      } else {
        $ct[$result[$i]['field_name']] = 1;
      }
      if ($ct[$result[$i]['field_name']]>1){
        $result[$i]['field_name'] .= $ct[$result[$i]['field_name']];
      }
    }
    return $result;
  }


}