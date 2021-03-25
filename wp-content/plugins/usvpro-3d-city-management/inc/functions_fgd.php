<?php
class ksk3d_functions_fgd{
  static function internal($file1 ,$new_id ,$dmy1 ,$dmy2 ,$sw_header) {
    ksk3d_log("ksk3d_functions_fgd::internal($file1 ,$new_id ,$dmy1 ,$dmy2 ,$sw_header)");
    $user_id = ksk3d_get_current_user_id();  
    $set_attrib = [
      array(
        'field_name' =>  "fid",
        'attrib_type' =>  "varchar",
        'attrib_digit' =>  "50",
        'attrib_name' =>  "fid",
        'codelist' =>  "",
        'tag_name' =>  "gen:value",
        'tag_path' =>  "BldA/gen:stringAttribute"
      ),
      array(
        'field_name' =>  "type",
        'attrib_type' =>  "varchar",
        'attrib_digit' =>  "50",
        'attrib_name' =>  "type",
        'codelist' =>  "",
        'tag_name' =>  "gen:value",
        'tag_path' =>  "BldA/gen:stringAttribute"
      ),
      array(
        'field_name' =>  "gml_id",
        'attrib_type' =>  "varchar",
        'attrib_digit' =>  "50",
        'attrib_name' =>  "gml_id",
        'codelist' =>  "",
        'tag_name' =>  "gen:value",
        'tag_path' =>  "BldA/gen:stringAttribute"
      )
    ];
    
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
    
    static::fgd2DB_(
      $file1,
      KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
      KSK3D_TABLE_GEOM .$user_id ."_" .$new_id
    );

    return true;
  }

  static function fgd2DB_($filename ,$tbl_attrib ,$tbl_geom) {
    $message = "";
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();  
    $xml = new DOMDocument();
    $xml->load($filename);
    $gml_name = $xml->getElementsByTagNameNS("*",'name');
    $xml = simplexml_load_file($filename);
    $xml->registerXPathNamespace('a', 'http://fgd.gsi.go.jp/spec/2008/FGD_GMLSchema');

    $tbl_name1 = $tbl_attrib;

    $i=0; $i2=0; $sql="";
    foreach ($xml as $e){
      $fid = $e->{'fid'};
      $typ = $e->{'type'};
      $gmlid = $e->attributes('gml',true)->id->__toString();
      $sql .= "\n('{$fid}','{$typ}','{$gmlid}'),";
      $i++;
      if ($i>99) {
        $i2+=$i;
        $i=0;
        $sql = "INSERT INTO {$tbl_name1} (fid,type,gml_id) VALUES " .substr($sql ,0 ,-1);
        $wpdb->query($sql);
        $sql="";
      }
    }
    if ($i>0){
      $i2+=$i;
      $sql = "INSERT INTO {$tbl_name1} (fid,type,gml_id) VALUES " .substr($sql ,0 ,-1);
      ksk3d_log( "sql:" .$sql );
      $wpdb->query($sql);
    }
    $message .= "属性データを".$i2 ."行追加しました。<br>\n";
    ksk3d_log( "insert:{$i2}行追加しました。");

    $tbl_name1 = $tbl_geom;

    $i=0; $i2=0; $sql="";
    foreach ($xml as $e){
      $e->registerXPathNamespace('a', 'http://fgd.gsi.go.jp/spec/2008/FGD_GMLSchema');

      $coord="";
      $geom = $e->{'area'}->xpath('gml:Surface//gml:exterior//gml:posList');
      foreach ($geom as $g){
        $xy = preg_replace('/([^\s]+)\s+([^\s]+)\n/' ,'${2} ${1},' ,$g->__toString());
        $xy = preg_replace('/^,|,$/' ,'' ,$xy);
        $coord .= "({$xy}),";
      }
      
      $geom = $e->{'area'}->xpath('gml:Surface//gml:interior//gml:posList');
      foreach ($geom as $g){
        $xy = implode("\n" ,array_reverse(explode("\n" ,$g->__toString())));
        $xy = preg_replace("/([^\s]+)\s+([^\s]+)\n/" ,'${2} ${1},' ,$xy);
        $xy = preg_replace('/^,|,$/' ,'' ,$xy);
        $coord .= "({$xy}),";
      }

      $sql .= "\n(ST_GeomFromText('MULTIPOLYGON((" .substr($coord ,0 ,-1) ."))', 4326)),";
      $i++;
      if ($i>99) {
        $i2+=$i;
        $i=0;
        $sql = "INSERT INTO {$tbl_name1} (the_geom) VALUES " .substr($sql ,0 ,-1);
        $wpdb->query($sql);
        $sql="";
      }
    }
    if ($i>0){
      $i2+=$i;
      $sql = "INSERT INTO {$tbl_name1} (the_geom) VALUES " .substr($sql ,0 ,-1);
      ksk3d_log( "sql:" .$sql );
      $wpdb->query($sql);
    }
    $message .= "図形データを".$i2 ."行追加しました。<br>\n";
    ksk3d_log( "insert:{$i2}行追加しました。");
    
    return array(
      true,
      'message'=>$message
    );
  }

}