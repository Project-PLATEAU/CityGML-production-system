<?php
function ksk3d_conv($src_id ,$new_id ,$fn ,$new_recode ,$input_option="" ,$output_option=""){
  $user_id = ksk3d_get_current_user_id();  
  global $wpdb;
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
 
  if (empty($new_id)){
    list($id2, $new_id) = ksk3d_fn_db::ins_dataset();
    ksk3d_console_log("new_id:".$new_id);
  } else {
    $result = ksk3d_fn_db::sel("SELECT id FROM {$tbl_name} WHERE user_id={$user_id} and file_id={$new_id} limit 1");
    $id2 = $result[0]['id'];
  }
  $file_format = "B.`file_format`";
  $file_name = "B.`file_name`";
  if (is_array($new_recode)){
    $add_displayname = $new_recode[0];
    if (isset($new_recode) and isset($new_recode[1])){
      $file_format = "'".$new_recode[1]."'";
      if (isset($new_recode) and isset($new_recode[2])){
        $file_name = "'".$new_recode[2]."'";
      }
    }
  } else {
    $add_displayname = $new_recode;
  }

  $message = "";

  $upload_dir = ksk3d_upload_dir() ."/" .$new_id;

  $sql = "SELECT file_id,file_path,file_name,file_format,zip_path,zip_name FROM {$tbl_name} WHERE id = %d;";
  $prepared = $wpdb->prepare($sql, $src_id);
  $result = $wpdb->get_row($prepared ,ARRAY_A);
  $file1 = $result['file_path']."/".$result['file_name'];
  ksk3d_console_log("file:".$file1);
  $source_dir = ksk3d_upload_dir() ."/" .$result['file_id'];
  ksk3d_functions_zip::fileid_extractTo($result['file_id']);
  if (preg_match('/内部データセット/' ,$result['file_format'])==1){
    $file_path2 = $upload_dir;
  } else {
    $file_path2 = preg_replace('{'.$source_dir.'}' ,$upload_dir ,$result['file_path']);
  }
  if (preg_match('/内部データセット/' ,$result['file_format'])!=1){
    $sw_header = 1;
    foreach(glob($file1) as $f) {
      if (is_file($f)) {
        $filepath = pathinfo($f);
        $dr2 = $upload_dir."/".substr($filepath['dirname'] ,strlen($source_dir)+1);
        ksk3d_console_log("dr2:".$dr2);
        if (!is_dir($dr2)){
          ksk3d_mkdir($dr2);
          chmod($dr2 ,0777);
        }

        if (preg_match('/内部データセット/' ,$file_format)==1){
          $file2 = $new_id;
        } else {
          $file2 = $dr2."/".$filepath['basename'];
        }
        
        $message = ksk3d_conv_export($fn ,$f ,$file2 ,$input_option ,$output_option ,$sw_header);
        $sw_header = 0;
      }
    }
  } else {
    $dr2 = $upload_dir;
    if (!is_dir($dr2)){
      ksk3d_mkdir($dr2);
      chmod($dr2 ,0777);
    }

    if (preg_match('/内部データセット|`file_format`/' ,$file_format)==1){$dr2 = $new_id;}

    $message = ksk3d_conv_export($fn ,$result['file_id'] ,$dr2 ,$input_option ,$output_option ,1);
  }

  if (preg_match('/3DTiles|内部データセット/i' ,$file_format)==0){
    ksk3d_console_log("file_id:".$new_id);
    $zip_file = ksk3d_fileid_zip_Compress($new_id ,true);
    $zip_filepath = pathinfo($zip_file);
    
    $sql2 = "
A.`zip_name` = '{$zip_filepath['basename']}',
A.`zip_path` = '{$zip_filepath['dirname']}',
    ";
  } else {
    $sql2 = "";
  }

  $sql = "UPDATE {$tbl_name} A
,(SELECT * FROM {$tbl_name} WHERE id={$src_id}) B
SET
A.`display_name` = CONCAT(B.`display_name`,'_{$add_displayname}'),
A.`file_format` = {$file_format},
A.`file_name` = {$file_name},
A.`file_path` = '{$file_path2}',
{$sql2}
A.`registration_date` = CURRENT_TIMESTAMP,
A.`meta_name` = B.`meta_name`,
A.`meta_path` = IF(B.`meta_path`is NOT NULL,'{$upload_dir}',NULL),
A.`memo_city` = B.`memo_city`,
A.`memo` = B.`memo`,
A.`meshsize` = 0,
A.`camera_position` = B.`camera_position`
WHERE A.id={$id2};";

  ksk3d_log("sql:".$sql);
  $wpdb->query($sql);
  
  ksk3d_fileid_zip_Compress_unlink($result['file_id']);
  
  ksk3d_DB_update_size($user_id ,$new_id);

  return array(
    $new_id,
    $message
  );
}

function ksk3d_conv_export($fn ,$f1 ,$f2 ,$input_option ,$output_option ,$sw_header){
  $result = ksk3d_v_fn($fn ,$f1 ,$f2 ,$input_option ,$output_option ,$sw_header);
  if ((isset($result)) and (isset($result[1]))){
    $message = $result[1];
  } else {
    $message = "";
  }
  return $message;
}

function ksk3d_v_fn($fn ,$f1 ,$dr2 ,$input_option ,$output_option ,$sw_header){
  if (preg_match('/\:\:/' ,$fn)==1){
    ksk3d_log("$fn($f1 ,$dr2 ,input_option ,output_option ,$sw_header)");
    ksk3d_console_log("$fn($f1 ,$dr2 ,input_option ,output_option ,$sw_header)");
    ksk3d_console_log($input_option);
    ksk3d_console_log($output_option);
    list ($fn1 ,$fn2) = explode("::" ,$fn);
    $result = $fn1::$fn2($f1 ,$dr2 ,$input_option ,$output_option ,$sw_header);
  } else {
    $result = $fn($f1 ,$dr2 ,$input_option ,$output_option ,$sw_header);
  }
  return $result;

}

function ksk3d_citygml_getattrib($path){
  $result = [];
  foreach($path as $node){
    if (get_class($node) == "DOMElement" ){
      if ($node->hasChildNodes()){
        $result = array_merge($result ,ksk3d_citygml_getattrib($node->childNodes));
      }
    } else if (get_class($node) == "DOMText"){
      if (preg_match('/\S/' ,$node->nodeValue)){
        $tag_path = preg_replace('/^\/.+?\/.+?\//' ,'' ,$node->parentNode->getNodePath());
        $tag_name = preg_replace('/^.+\//' ,'' ,$tag_path);
        $field = mb_strtolower(substr(preg_replace('/:/' ,'_' ,$tag_name) ,0 ,7));
        $result[] = [
          'tag_name' => $tag_name,  
          'tag_path' => $tag_path,
          'field_name' => $field,  
          'attrib_type' => "",
          'attrib_name' => $node->parentNode->getAttribute("name"), 
          'attrib_unit' => $node->parentNode->getAttribute("uom"),
          'codelist' => $node->parentNode->getAttribute("codeSpace"), 
          'attrib_value' => $node->nodeValue 
        ];
        if (preg_match('/gen\:.+Attribute/i' ,$tag_path)){
          $i = count($result)-1;
          if (preg_match('/intAttribute/i' ,$tag_path)){
            $result[$i]['attrib_type'] = "INT";
          } else if (preg_match('/doubleAttribute/i' ,$tag_path)){
            $result[$i]['attrib_type'] = "DOUBLE";
          } else {
          }
          $result[$i]['tag_attrib'] = "name";
          if (empty($result[$i]['attrib_name'])){
            $result[$i]['attrib_name'] = $node->parentNode->parentNode->getAttribute("name");
          }
          $result[$i]['tag_attrib_name'] = $result[$i]['attrib_name'];
          $result[$i]['tag_path'] = preg_replace('/(Attribute)\[.+?\]/' ,"$1[@name='{$result[$i]['attrib_name']}']" ,$result[$i]['tag_path']);
        }
     }
    }
  }
  return $result;
}

function ksk3d_citygml_getattrib_($nodeList ,$ct=[]){
  $result = [];
  foreach($nodeList as $node){
    if (get_class($node) == "DOMElement" ){
      if ($node->hasChildNodes()){
        list($result_ ,$ct) = ksk3d_citygml_getattrib_($node->childNodes ,$ct);
        $result = array_merge($result ,$result_);
      }
    } else if (get_class($node) == "DOMText"){
      if (preg_match('/\S/' ,$node->nodeValue)){
        $tag_path = preg_replace('/^\/.+?\/.+?\//' ,'' ,$node->parentNode->getNodePath());
        $tag_path = preg_replace('/\[\d+\]/' ,'' ,$tag_path);
        $tag_lastpath = preg_replace('/.+\//' ,'' ,$tag_path);
        if (preg_match('/gml:posList|gml:pos/i' ,$tag_lastpath)==0){
          if (preg_match('/gen\:.+Attribute/i' ,$tag_path)==1){
            $name1 = $node->parentNode->parentNode->getAttribute("name");
            $tag_path = preg_replace('/(gen\:.+Attribute)/i' ,"$1[@name='{$name1}']" ,$tag_path);
            $name2 = $node->parentNode->parentNode->parentNode->getAttribute("name");
            if (!empty($name2)){
              $tag_path = preg_replace('/^(.+\/[^\/]+)(\/[^\/]+\/[^\/]+)$/' ,"$1[@name='{$name2}']$2" ,$tag_path);
            }
          } else if (preg_match('/KeyValuePair/i' ,$tag_path)==1){
            $name1 = $node->parentNode->parentNode->childNodes->item(1)->getAttribute("codeSpace");
            $v1 = $node->parentNode->parentNode->childNodes->item(1)->firstChild->nodeValue;
            $tag_path = $node->parentNode->parentNode->childNodes->item(1)->getNodePath();
            $tag_path = preg_replace('/^\/.+?\/.+?\//' ,'' ,$tag_path);
            $tag_path = preg_replace('/\[\d+\]/' ,'' ,$tag_path);
            $tag_path .= "[@codeSpace='{$name1}' and text()='{$v1}']";
          }
          if (!isset($ct[$tag_path])){
            $ct[$tag_path]=1;
          
            $tag_name = preg_replace('/^.+\//' ,'' ,$tag_path);

            $field_ct=2;
            $field = mb_strtolower(substr(preg_replace('/:/' ,'_' ,$tag_name) ,0 ,7));
            if (isset($ct2[$field])){
              $field2 = $field.$field_ct;
              while (isset($ct2[$field2])){
                $field_ct++;
                $field2 = $field.$field_ct;
              }
              $field = $field2;
            }
            $ct2[$field]=1;

            $result[] = [
              'tag_name' => $tag_name,  
              'tag_path' => $tag_path,
              'field_name' => $field,  
              'attrib_type' => "",
              'attrib_name' => $node->parentNode->getAttribute("name"), 
              'attrib_unit' => $node->parentNode->getAttribute("uom"),
              'codelist' => $node->parentNode->getAttribute("codeSpace"), 
              'attrib_value' => $node->nodeValue 
            ];
            $i = count($result)-1;
            if (preg_match('/gen\:.+Attribute/i' ,$tag_path)==1){
              if (preg_match('/intAttribute/i' ,$tag_path)){
                $result[$i]['attrib_type'] = "INT";
              } else if (preg_match('/doubleAttribute/i' ,$tag_path)){
                $result[$i]['attrib_type'] = "DOUBLE";
              } else {
              }
              $result[$i]['tag_attrib'] = "name";
              if (empty($result[$i]['attrib_name'])){
                if (!empty($name2)){
                  $result[$i]['attrib_name'] = $name1."_".$name2;
                } else {
                  $result[$i]['attrib_name'] = $name1;
                }
              }
              $result[$i]['tag_attrib_name'] = $result[$i]['attrib_name'];
            } else if (preg_match('/KeyValuePair/i' ,$tag_path)==1){
              $tag_name = preg_replace('/^.+\//' ,'' ,$node->parentNode->parentNode->getNodePath());
              $result[$i]['tag_name'] = $tag_name;
              $v2 = preg_replace('/:/' ,'_' ,$node->parentNode->parentNode->childNodes->item(3)->firstChild->nodeValue);
              $result[$i]['attrib_value'] = $v2;
              $result[$i]['attrib_name'] = preg_replace('/:/' ,'_' ,$tag_name."_".$v1);

            }
          } else {
          }
        } else {
        }
      } else {
      }
    }
  }
  return array($result ,$ct);
}

function ksk3d_citygml_test($filename,$zip=true) {
  if ($zip){
    $upload_dir = ksk3d_upload_dir();
    $file_id = preg_replace('{^(.+?)/.+$}',"$1",substr($filename ,mb_strlen($upload_dir)+1));
    $upload_dir2 = $upload_dir ."/" .$file_id;
    $zip_pathinfo = ksk3d_functions_zip::pathinfo($file_id);
    if (is_file($zip_pathinfo['fullpath'])){
      $file = substr($filename ,mb_strlen($upload_dir2)+1);
      $filename = ksk3d_zip_extractTo1($zip_pathinfo['fullpath'] ,"" ,$file);
    }
  }

  if (!file_exists($filename)){
    ksk3d_console_log("file is not found.");
    return false;
  }

  global $wpdb;
  $result = [];
  $i = 0;

  $doc = new DOMDocument();
  $doc->load($filename);
  $cityObjectMember = $doc->getElementsByTagName('cityObjectMember');
  $test = $cityObjectMember[0];
  $feature = "";
  $items = $test->childNodes;
  for ($i = 0; $i < $items->length; $i++){
    if (get_class($items->item($i)) == "DOMElement" ){
      $feature = $items->item($i);
      break;
    }
  }
  if (empty($feature)){
    return false;
  }
  $feature_path = preg_replace('/^(\/.+?\/.+?\/).*/' ,"$1" ,$feature->getNodePath());
  $tag = preg_replace('/^\/.+?\/.+?\//' ,'' ,$feature->getNodePath());
  $path = $feature_path ."".$tag;

  $gml_id = $feature->getAttribute("gml:id");
  if (!empty($gml_id)){
    $field = mb_strtolower(substr(preg_replace('/^.+(:|\/)/' ,'' ,$tag) ,0 ,7));
    if (isset($ct[$field])){$ct[$field]++;} else {$ct[$field]=1;}
    $result[] = [
      'tag_name' => "gml:id",  
      'tag_path' => $tag."/@gml:id",
      'tag_attrib' => "gml:id",  
      'field_name' => "gml_id",  
      'attrib_type' => "",
      'attrib_name' => "gml_id", 
      'codelist' => "", 
      'attrib_value' => $gml_id 
    ];
  }

  $xpath = new DOMXpath($doc);
  $ct_geom = 0;
  $attributes = $feature->childNodes;
  foreach ($attributes as $attrib){
    if (get_class($attrib) == "DOMElement" ){
      if (preg_match('/:lod[\-0-4]/i',$attrib->getNodePath())==1){
        $tag_path = preg_replace('/^\/.+?\/.+?\//' ,'' ,$attrib->getNodePath());
        $tag_name = preg_replace('/^.+\//' ,'' ,$tag_path);
        $result[] = [
          'tag_name' => $tag_name,
          'tag_path' => $tag_path,
          'field_name' => "the_geom",
          'attrib_type' => "GEOMETRY",
          'attrib_name' => "空間データ",
          'codelist' => "",
          'attrib_value' => $attrib->nodeValue 
        ];
      } else if (preg_match('/boundedBy|:lod[\-0-4]/i',$attrib->getNodePath())==0){
        $result = array_merge($result ,ksk3d_citygml_getattrib($xpath->query($attrib->getNodePath())));
      }
    }
  }

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

  if (is_file($zip_pathinfo['fullpath'])){
    $userrootdir = ksk3d_userrootdir($filename);
    ksk3d_console_log("userrootdir:".$userrootdir);
    if ($userrootdir != false){
      chmod($userrootdir ,0777);
      ksk3d_delTree($userrootdir);
    }
  }

  return $result;
}

function ksk3d_citygml_test_onefile($src_id) {
  ksk3d_console_log("ksk3d_citygml_test_onefile");

  global $wpdb;
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $sql = "SELECT file_id,file_path,file_name,file_format,zip_path,zip_name FROM {$tbl_name} WHERE id = %d;";
  $prepared = $wpdb->prepare($sql, $src_id);
  $tbl_data = $wpdb->get_row($prepared ,ARRAY_A);
  if (preg_match('/内部データセット/' ,$tbl_data['file_format'])==1){
    return false;
  }

  $file1 = $tbl_data['file_path']."/".$tbl_data['file_name'];
  ksk3d_console_log("file:".$file1);
  $source_dir = ksk3d_upload_dir() ."/" .$tbl_data['file_id'];
  ksk3d_functions_zip::fileid_extractTo($tbl_data['file_id']);

  $result = [];
  $i = 0;
  $flg_gml_id = true;
  $result_name = [];
  $ct = [];

  $doc = new DOMDocument();
  foreach(glob($file1) as $filename) {
    if (is_file($filename)) {
      ksk3d_console_log("filename:".$filename);
      
      $doc->load($filename);
      $cityObjectMembers = $doc->getElementsByTagName('cityObjectMember');
      foreach ($cityObjectMembers as $cityObjectMember){
        foreach ($cityObjectMember->childNodes as $cityObject){
          if (get_class($cityObject) == "DOMElement" ){
            $feature = $cityObject; 
            if (!empty($cityObject)){
              $cityObject_path = preg_replace('/^(\/.+?\/.+?\/).*/' ,"$1" ,$cityObject->getNodePath());
              $tag = preg_replace('/^\/.+?\/.+?\//' ,'' ,$cityObject->getNodePath());  
              $path = $cityObject_path ."".$tag;

              if ($flg_gml_id){
                $gml_id = $cityObject->getAttribute("gml:id");
                if (!empty($gml_id)){
                  $field = mb_strtolower(substr(preg_replace('/^.+(:|\/)/' ,'' ,$tag) ,0 ,7));
                  if (isset($ct[$field])){$ct[$field]++;} else {$ct[$field]=1;}
                  $result[] = [
                    'tag_name' => "gml:id",  
                    'tag_path' => $tag."/@gml:id",
                    'tag_attrib' => "gml:id",  
                    'field_name' => "gml_id",  
                    'attrib_type' => "",
                    'attrib_name' => "gml_id", 
                    'codelist' => "", 
                    'attrib_value' => $gml_id 
                  ];
                  $flg_gml_id = false;
                }
              }
              
              $xpath = new DOMXpath($doc);
              $ct_geom = 0;
              $attributes = $cityObject->childNodes;
              foreach ($attributes as $attrib){
                if (get_class($attrib) == "DOMElement" ){
                  $tag_path = preg_replace('/^\/.+?\/.+?\//' ,'' ,$attrib->getNodePath());
                  if (preg_match('/:lod[\-0-4]/i',$attrib->getNodePath())==1){
                    if (!isset($result_name[$tag_path])){
                      $result_name[$tag_path]=1;
                        $tag_name = preg_replace('/^.+\//' ,'' ,$tag_path);
                        $result[] = [
                          'tag_name' => $tag_name,
                          'tag_path' => $tag_path,
                          'field_name' => "the_geom",
                          'attrib_type' => "GEOMETRY",
                          'attrib_name' => "空間データ",
                          'codelist' => "",
                          'attrib_value' => $attrib->nodeValue 
                        ];
                    }
                  } else if (preg_match('/boundedBy|:lod[\-0-4]/i',$attrib->getNodePath())==0){
                    list($result_ ,$ct) = ksk3d_citygml_getattrib_($xpath->query($attrib->getNodePath()) ,$ct);
                    $result = array_merge($result ,$result_);
                  }
                }
              }
            }
          }
        }
      }
      ksk3d_console_log("result-fileによる更新");
      ksk3d_console_log($result);
      break;
    }
  }
  for($i=0;$i<count($result);$i++){
    $field = $result[$i]['field_name'];
    if (isset($ct[$result[$i]['field_name']])){
      $ct[$field]++;
    } else {
      $ct[$field] = 1;
    }
    if ($ct[$field]>1){
      $result[$i]['field_name'] = $field .$ct[$result[$i]['field_name']];
    }
  }
  ksk3d_console_log("result-属性の重複調整");
  ksk3d_console_log($result);
  
  ksk3d_fileid_zip_Compress_unlink($tbl_data['file_id']);
  
  return $result;
}

function ksk3d_citygml_test_all($src_id) {
  ksk3d_console_log("ksk3d_citygml_test_all");

  global $wpdb;
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $sql = "SELECT file_id,file_path,file_name,file_format,zip_path,zip_name FROM {$tbl_name} WHERE id = %d;";
  $prepared = $wpdb->prepare($sql, $src_id);
  $tbl_data = $wpdb->get_row($prepared ,ARRAY_A);
  if (preg_match('/内部データセット/' ,$tbl_data['file_format'])==1){
    return false;
  }

  $file1 = $tbl_data['file_path']."/".$tbl_data['file_name'];
  ksk3d_console_log("file:".$file1);
  $source_dir = ksk3d_upload_dir() ."/" .$tbl_data['file_id'];
  ksk3d_functions_zip::fileid_extractTo($tbl_data['file_id']);

  $result = [];
  $i = 0;
  $flg_gml_id = true;
  $result_name = [];
  $ct = [];

  $doc = new DOMDocument();
  foreach(glob($file1) as $filename) {
    if (is_file($filename)) {
      ksk3d_console_log("filename:".$filename);
      
      $doc->load($filename);
      $cityObjectMembers = $doc->getElementsByTagName('cityObjectMember');
      foreach ($cityObjectMembers as $cityObjectMember){
        foreach ($cityObjectMember->childNodes as $cityObject){
          if (get_class($cityObject) == "DOMElement" ){
            $feature = $cityObject; 
            if (!empty($cityObject)){
              $cityObject_path = preg_replace('/^(\/.+?\/.+?\/).*/' ,"$1" ,$cityObject->getNodePath());
              $tag = preg_replace('/^\/.+?\/.+?\//' ,'' ,$cityObject->getNodePath());  
              $path = $cityObject_path ."".$tag;

              if ($flg_gml_id){
                $gml_id = $cityObject->getAttribute("gml:id");
                if (!empty($gml_id)){
                  $field = mb_strtolower(substr(preg_replace('/^.+(:|\/)/' ,'' ,$tag) ,0 ,7));
                  if (isset($ct[$field])){$ct[$field]++;} else {$ct[$field]=1;}
                  $result[] = [
                    'tag_name' => "gml:id",  
                    'tag_path' => $tag."/@gml:id",
                    'tag_attrib' => "gml:id",  
                    'field_name' => "gml_id",  
                    'attrib_type' => "",
                    'attrib_name' => "gml_id", 
                    'codelist' => "", 
                    'attrib_value' => $gml_id 
                  ];
                  $flg_gml_id = false;
                }
              }
              
              $xpath = new DOMXpath($doc);
              $ct_geom = 0;
              $attributes = $cityObject->childNodes;
              foreach ($attributes as $attrib){
                if (get_class($attrib) == "DOMElement" ){
                  $tag_path = preg_replace('/^\/.+?\/.+?\//' ,'' ,$attrib->getNodePath());
                  if (preg_match('/:lod[\-0-4]/i',$attrib->getNodePath())==1){
                    if (!isset($result_name[$tag_path])){
                      $result_name[$tag_path]=1;
                        $tag_name = preg_replace('/^.+\//' ,'' ,$tag_path);
                        $result[] = [
                          'tag_name' => $tag_name,
                          'tag_path' => $tag_path,
                          'field_name' => "the_geom",
                          'attrib_type' => "GEOMETRY",
                          'attrib_name' => "空間データ",
                          'codelist' => "",
                          'attrib_value' => $attrib->nodeValue 
                        ];
                    }
                  } else if (preg_match('/boundedBy|:lod[\-0-4]/i',$attrib->getNodePath())==0){
                    list($result_ ,$ct) = ksk3d_citygml_getattrib_($xpath->query($attrib->getNodePath()) ,$ct);
                    $result = array_merge($result ,$result_);
                  }
                }
              }
            }
          }
        }
      }
      ksk3d_console_log("result-fileによる更新");
      ksk3d_console_log($result);
    }
  }
  for($i=0;$i<count($result);$i++){
    $field = $result[$i]['field_name'];
    if (isset($ct[$result[$i]['field_name']])){
      $ct[$field]++;
    } else {
      $ct[$field] = 1;
    }
    if ($ct[$field]>1){
      $result[$i]['field_name'] = $field .$ct[$result[$i]['field_name']];
    }
  }
  ksk3d_console_log("result-属性の重複調整");
  ksk3d_console_log($result);
  
  ksk3d_fileid_zip_Compress_unlink($tbl_data['file_id']);
  
  return $result;
}

function ksk3d_citygml23DTiles_ex($dataset_id1, $op_xy_replace=false, $attrib, $geometricError=40) {
  $userID = ksk3d_get_current_user_id();
  $file_id2 = ksk3d_get_max_file_id();
  $upload_dir = ksk3d_upload_dir() ."/" .$file_id2;

  $text = "";

  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();  
  ksk3d_console_log("charset_collate:".$charset_collate);
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $sql = "INSERT INTO {$tbl_name} (
  `user_id`,
  `file_id`,
  `display_name`,
  `file_format`,
  `file_name`,
  `file_path`,
  `registration_date`,
  `meta_name`,
  `meta_path`,
  `memo_city`,
  `memo`,
  `meshsize`,
  `camera_position`
  )
  SELECT 
    `user_id`,
    {$file_id2},
    CONCAT(`display_name`,'(3DTiles)'),
    '3DTiles',
    'tileset.json',
    '{$upload_dir}',
    CURRENT_TIMESTAMP,
    `meta_name`,
    IF(`meta_path`is NOT NULL,'{$upload_dir}',NULL),
    `memo_city`,
    `memo`,
    0,
    ''
  FROM {$tbl_name}
  WHERE user_id={$userID} and file_id={$dataset_id1};";

  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql = "SELECT file_id,file_path,file_name,file_format,zip_path,zip_name FROM {$tbl_name} WHERE user_id={$userID} and file_id={$dataset_id1};";
  $result = $wpdb->get_row($sql ,ARRAY_A);
  $file1 = $result['file_path']."/".$result['file_name'];
  ksk3d_console_log("file:".$file1);
  $source_dir = ksk3d_upload_dir() ."/" .$result['file_id'];
  ksk3d_functions_zip::fileid_extractTo($result['file_id']);


  $dr9 = $upload_dir."/tileset";
  ksk3d_mkdir($dr9);
  chmod($dr9 ,0777);

  $dr2 = $upload_dir."/intermediate2";
  ksk3d_mkdir($dr2);
  chmod($dr2 ,0777);

  $dr3 = $upload_dir."/intermediate3";
  ksk3d_mkdir($dr3);
  chmod($dr3 ,0777);

  $tileset_mrg_json = $upload_dir."/tileset.json";
  $handle = fopen($tileset_mrg_json, "w");
  $ct = 0;
  $header = "";
  $xmin = "";
  $ymin = "";
  $xmax = "";
  $ymax = "";
  $zmin = "";
  $zmax = "";

  foreach(glob($file1) as $f) {
    if (is_file($f)) {
      $filepath = pathinfo($f);

      if (preg_match('/_bldg_/' ,$f)==1){
        if (($op_xy_replace)=='on'){
          $dr2_filepath = $dr2."/".substr($filepath['dirname'] ,strlen($source_dir)+1);
          if (!is_dir($dr2_filepath)){
            ksk3d_mkdir($dr2_filepath);
            chmod($dr2_filepath ,0777);
          }
          $file2 = $dr2_filepath."/".$filepath['basename'];
          ksk3d_functions_gml::vertices_xy_replace($f ,$file2);
        } else {
          $file2 = $f;
        }

        if (!empty($attrib)){
          $dr3_filepath= $dr3."/".substr($filepath['dirname'] ,strlen($source_dir)+1);
          if (!is_dir($dr3_filepath)){
            ksk3d_mkdir($dr3_filepath);
            chmod($dr3_filepath ,0777);
          }
          $file3 = $dr3_filepath."/".$filepath['basename'];
          ksk3d_functions_citygml::attrib2generic($file2 ,$file3 ,$attrib);
        } else {
          $file3 = $file2;
        }
      } else {
        $dr2_filepath= $dr2."/".substr($filepath['dirname'] ,strlen($source_dir)+1);
        if (!is_dir($dr2_filepath)){
          ksk3d_mkdir($dr2_filepath);
          chmod($dr2_filepath ,0777);
        }
        $file2 = $dr2_filepath."/".$filepath['basename'];
        ksk3d_functions_citygml::cityobject2building($f ,$file2 ,$attrib);

        $dr3_filepath= $dr3."/".substr($filepath['dirname'] ,strlen($source_dir)+1);
        if (!is_dir($dr3_filepath)){
          ksk3d_mkdir($dr3_filepath);
          chmod($dr3_filepath ,0777);
        }
        $file3 = $dr3_filepath."/".$filepath['basename'];
        ksk3d_functions_citygml::generate_backside($file2 ,$file3 ,$attrib);
      }

      $dr9_filepath = $dr9."/".$filepath['filename'];
      if (!is_dir($dr9_filepath)){
        ksk3d_mkdir($dr9_filepath);
        chmod($dr9_filepath ,0777);
      }
      
      $ksk3d_option = get_option('ksk3d_option');
      $bin = $ksk3d_option['citygml_to_3dtiles'];
      if (empty($bin)){
        $cmd = KSK3D_BIN_CITYGML_TO_3DTILES ." \"{$file3}\" \"{$dr9_filepath}/\" 2>&1";
      } else {
        $cmd = $bin ." \"{$file3}\" \"{$dr9_filepath}/\" 2>&1";
      }
      
      ksk3d_console_log("exec:".$cmd);
      $result = exec($cmd ,$output ,$return_var);
      $text = "3dTilesへの変換を開始しました。<br>";
      $text .= "{$result}<br>";
      ksk3d_console_log($output);
      ksk3d_console_log($return_var);
      if ($return_var){
        $text .= "失敗しました。<br>";
      } else {
        $text .= "成功しました。<br>";
        $ct++;
        $out = "";
        $tileset_json = $dr9_filepath."/tileset.json";
        $fgc = file_get_contents($tileset_json);

        $json = json_decode($fgc ,true);
        if ($ct==1){
          $header = preg_replace('/^(.+?"geometricError").+/s' ,"$1" ,$fgc);
          $xmin = $json['root']['boundingVolume']['region'][0];
          $ymin = $json['root']['boundingVolume']['region'][1];
          $xmax = $json['root']['boundingVolume']['region'][2];
          $ymax = $json['root']['boundingVolume']['region'][3];
          $zmin = $json['root']['boundingVolume']['region'][4];
          $zmax = $json['root']['boundingVolume']['region'][5];
        } else {
          $out = ",";
          if ($xmin > $json['root']['boundingVolume']['region'][0]){$xmin = $json['root']['boundingVolume']['region'][0];}
          if ($ymin > $json['root']['boundingVolume']['region'][1]){$ymin = $json['root']['boundingVolume']['region'][1];}
          if ($xmax < $json['root']['boundingVolume']['region'][2]){$xmax = $json['root']['boundingVolume']['region'][2];}
          if ($ymax < $json['root']['boundingVolume']['region'][3]){$ymax = $json['root']['boundingVolume']['region'][3];}
          if ($zmin > $json['root']['boundingVolume']['region'][4]){$zmin = $json['root']['boundingVolume']['region'][4];}
          if ($zmax < $json['root']['boundingVolume']['region'][5]){$zmax = $json['root']['boundingVolume']['region'][5];}
        }
        $bounding = preg_replace('/^.+?("boundingVolume".+?"geometricError").+/s' ,"$1" ,$fgc);
        
        $out .=<<<EOL
      {
      {$bounding}: 10,
    "content": {
      "uri": "tileset/{$filepath['filename']}/full.b3dm"
    }
  }

EOL
;
        fwrite($handle ,$out);
      }
    }
  }
  $out =<<<EOL
    ]
  }
}
EOL
;
  fwrite($handle ,$out);
  fclose($handle);

  $f_array = explode('_' ,$filepath['basename']."_");
  ksk3d_console_log($filepath['basename']);
  ksk3d_console_log($f_array[0]);
  $scl = pow((10-strlen($f_array[0])),2)*20;
  if ($scl < $geometricError){$scl = $geometricError;}

  $out = $header;
  $out .=<<<EOL
  : {$scl},
  "root": {
    "refine": "REPLACE",
    "boundingVolume": {
      "region": [
      {$xmin},
      {$ymin},
      {$xmax},
      {$ymax},
      {$zmin},
      {$zmax}
      ]
    },
    "geometricError": {$scl}
    ,"children": [

EOL
;
  ksk3d_file_prepend($tileset_mrg_json ,$out);
  ksk3d_delTree($dr2);
  ksk3d_delTree($dr3);
  ksk3d_fileid_zip_Compress_unlink($dataset_id1);

  $file_size = ksk3d_dir_size($upload_dir);
  $sql = "UPDATE {$tbl_name} SET
    file_size={$file_size}
    WHERE user_id={$userID} and file_id={$file_id2};";
  ksk3d_log("sql:".$sql);
  $wpdb->query($sql);
  return array(
    $text,
    $file_id2
  );
}

function ksk3d_citygml2DB($filename ,$tbl_attrib ,$tbl_geom ,$set_attrib ,$set_high="") {
  ksk3d_console_log("filename:".$filename);

  foreach(glob($filename) as $f) {
    if (is_file($f)) {
      ksk3d_citygml2DB_($f ,$tbl_attrib ,$tbl_geom ,$set_attrib ,$set_high);
    }
  }

  $userID = preg_replace('/.*_([0-9]+)_([0-9]+)$/' ,"$1" ,$tbl_attrib);
  $file_id = preg_replace('/.*_([0-9]+)_([0-9]+)$/' ,"$2" ,$tbl_attrib);
  ksk3d_DB_update_size($userID ,$file_id);

  return true;
}

function ksk3d_citygml2DB_($filename ,$tbl_attrib ,$tbl_geom ,$set_attrib ,$set_high) {
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
  $cityObjectMember = $doc->getElementsByTagName('cityObjectMember');
  $xpath = new DOMXpath($doc);

  foreach ($cityObjectMember as $cityObject){
    $feature_path = $cityObject->getNodePath()."/";
    $sql_a_value = "";
    $coord = "";
    $coord2 = "";
    foreach($set_attrib as $attrib){
      if (preg_match('/geometry/i' ,$attrib['attrib_type'])==1){
        $tag_path = preg_replace('/^.+?\//' ,'.//' ,$attrib['tag_path']);
        $geom = $xpath->query($tag_path .'//gml:exterior//gml:posList' ,$cityObject);


        $zmin = "";
        $zmax = "";
        $zmin1 = "";
        $zmax1 = "";
        if (count($geom)>0){
          foreach ($geom as $g){
            $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/' ,'${3},' ,trim($g->nodeValue)." ");
            $z_array = explode(',' ,substr($z,0,-1));
            $zmin1 = min($z_array);
            $zmax1 = max($z_array);
            if (empty($zmin)){
              $zmin = $zmin1; 
              $zmax = $zmax1; 
            } else {
              if ($zmin > $zmin1) {$zmin = $zmin1;}
              if ($zmax < $zmax1) {$zmax = $zmax1;}
            }
            $xy = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/' ,'${2} ${1},' ,trim($g->nodeValue)." ");
            $xy = preg_replace('/^,|,(\t*?)$/' ,'' ,$xy);
            $coord .= "({$xy}),";
          }
        
          $tag_path = preg_replace('/^.+?\//' ,'.//' ,$attrib['tag_path']);
          $geom = $xpath->query($tag_path .'//gml:interior//gml:posList' ,$cityObject);
          if (count($geom)>0){
            foreach ($geom as $g){
              $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/' ,'${3},' ,trim($g->nodeValue)." ");
              $z_array = explode(',' ,substr($z,0,-1));
              $zmin1 = min($z_array);
              $zmax1 = max($z_array);
              if (empty($zmin)){
                $zmin = $zmin1; 
                $zmax = $zmax1; 
              } else {
                if ($zmin > $zmin1) {$zmin = $zmin1;}
                if ($zmax < $zmax1) {$zmax = $zmax1;}
              }
              $xy = implode("\n" ,array_reverse(explode("\n" ,trim($g->nodeValue))));
              $xy = preg_replace("/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/" ,'${2} ${1},' ,$xy." ");
              $xy = preg_replace('/^,|,(\t*?)$/' ,'' ,$xy);
              $coord2 .= "({$xy}),";
            }
            $coord2 = "ST_GeomFromText('MULTIPOLYGON((" .substr($coord2 ,0 ,-1) ."))', 4326)";
          } else {
            $coord2 = "NULL";
          }
        
          if (empty($zmin)){
            $zmin = 0;
            $zmax = 0;
          }
          $sql_g .= "\n(ST_GeomFromText('MULTIPOLYGON((" .substr($coord ,0 ,-1) ."))', 4326) ,{$coord2} ,'{$zmin}' ,'" .($zmax-$zmin) ."'),";
        }
      } else {
        if (isset($attrib['tag_attrib']) && !empty($attrib['tag_attrib'])){
            $tag_path = preg_replace('/(Attribute)\[.*?\]/i' ,'$1[@'.$attrib['tag_attrib'].'=\''.$attrib['tag_attrib_name'].'\']' ,$attrib['tag_path']);
            $tag_path = preg_replace('/^.+?\//' ,'.//' ,$tag_path);
            $query = $xpath->query($tag_path ,$cityObject);
            if ($query->length > 0){
              $tag = $query->item(0)->nodeValue;
            } else {
              $tag = "NULL";
            }
        } else {
          $path = preg_replace("/^.+?\//" ,'.//' ,$attrib['tag_path']);
          $query = $xpath->query($path ,$cityObject);
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

function ksk3d_dataset_delete($file_id){
    $userID = ksk3d_get_current_user_id();
    if (empty($file_id) and ($file_id!=0)) {return false;}

    $target = ksk3d_upload_dir() ."/" .$file_id;
    if (file_exists($target)){
      chmod($target, 0777);
      ksk3d_log("ksk3d_delTree:".$target);
      ksk3d_delTree($target);
    }

    global $wpdb;

    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "DELETE FROM {$tbl_name} WHERE user_id={$userID} and file_id={$file_id};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    $tbl_name1 = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
    $sql = "DELETE FROM {$tbl_name1} WHERE user_id={$userID} and file_id={$file_id};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    $tbl_name1 = KSK3D_TABLE_ATTRIB .$userID ."_" .$file_id;
    $sql = "DROP TABLE IF EXISTS {$tbl_name1};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    $tbl_name1 = KSK3D_TABLE_GEOM .$userID ."_" .$file_id;
    $sql = "DROP TABLE IF EXISTS {$tbl_name1};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);
    
    return true;
}

function ksk3d_DB_create_attrib($tbl_attrib ,$set_attrib){
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();  
  $sql = "";
    ksk3d_console_log($set_attrib);
  foreach($set_attrib as $attrib){
    if (isset($attrib['field_name'])){$attrib['attrib_field'] = $attrib['field_name'];}
    if (preg_match('/^the_geom$/i' ,$attrib['attrib_field'])!=1){
      $attrib_type = $attrib['attrib_type'];
      if (preg_match('/char/i' ,$attrib_type)){
        $attrib_type .= "({$attrib['attrib_digit']})";
      }
      $sql .= "`{$attrib['attrib_field']}` {$attrib_type},\n";
    }
  }
  $sql =<<<EOL
CREATE TABLE {$tbl_attrib} (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
{$sql}
UNIQUE KEY id (id)
) $charset_collate;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);
}

function ksk3d_DB_create_geom($tbl_geom){
  global $wpdb;
  $sql =<<<EOL
CREATE TABLE {$tbl_geom} (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
`the_geom` GEOMETRY NOT NULL,
`hole` GEOMETRY,
`z` double,
`m` double,
`meshcode` varchar(12),
UNIQUE KEY id (id)
);
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);
}

function ksk3d_DB_insert_attrib($userID ,$file_id ,$set_attrib){
  
  global $wpdb;

  $tbl = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
  $sql = "DELETE FROM {$tbl} WHERE user_id={$userID} and file_id={$file_id};";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $i = 0;
  ksk3d_console_log($set_attrib);
  foreach($set_attrib as $attrib){
    ksk3d_console_log("attrib");
    ksk3d_console_log($attrib);
    if (isset($attrib['field_name'])){$attrib['attrib_field'] = $attrib['field_name'];}
    if (preg_match('/^the_geom$/i' ,$attrib['attrib_field'])!=1){
      $i++;
      if (!isset($attrib['attrib_unit'])){$attrib['attrib_unit']="";}
      if (!isset($attrib['tag_path'])){$attrib['tag_path']="";}
      if (!isset($attrib['codelist'])){$attrib['codelist']="";}
      $result = $wpdb->insert(
        $tbl,
        array(
          'user_id' =>  $userID,
          'file_id' =>  $file_id,
          'attrib_id' =>  $i,
          'attrib_field' =>  $attrib['attrib_field'],
          'attrib_type' =>  $attrib['attrib_type'],
          'attrib_digit' =>  $attrib['attrib_digit'],
          'attrib_unit' =>  $attrib['attrib_unit'],
          'attrib_name' =>  $attrib['attrib_name'],
          'codelist_name' =>  $attrib['codelist'],
          'tag_path' =>  preg_replace("/\\\\+\'/" ,"'" ,$attrib['tag_path']),
          'tag_name' =>  $attrib['tag_name']
        )
      );
    }
  }
}

function ksk3d_DB_update_size($userID ,$file_id){
  global $wpdb;
  $sql =<<< EOL
SELECT sum(data_length+index_length)
  FROM information_schema.tables  
  WHERE table_name like 'wp_ksk3d_%_{$userID}_{$file_id}';
EOL
;
  $upload_dir = ksk3d_upload_dir() ."/" .$file_id;
  $size = $wpdb->get_var($sql) +ksk3d_dir_size($upload_dir);
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $wpdb->update( 
    $tbl_name,
    array(
      'file_size' => $size
    ), 
    array(
      'user_id' =>  $userID,
      'file_id' =>  $file_id,
    ),
    array(
      '%d'
    ), 
    array(
      '%d',
      '%d'
    )
  );
}

function ksk3d_fgd2DB($filename ,$tbl_attrib ,$tbl_geom) {
  ksk3d_console_log("filename:".$filename);

  $userID = preg_replace('/.*_([0-9]+)_([0-9]+)$/' ,"$1" ,$tbl_attrib);
  $file_id = preg_replace('/.*_([0-9]+)_([0-9]+)$/' ,"$2" ,$tbl_attrib);

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
  ksk3d_DB_create_attrib($tbl_attrib ,$set_attrib);

  ksk3d_DB_create_geom($tbl_geom);

  ksk3d_DB_insert_attrib($userID ,$file_id ,$set_attrib);

  foreach(glob($filename) as $f) {
    if (is_file($f)) {
      ksk3d_fgd2DB_($f ,$tbl_attrib ,$tbl_geom);
    }
  }

  ksk3d_console_log("$userID ,$file_id");
  ksk3d_DB_update_size($userID ,$file_id);

  return true;
}

function ksk3d_fgd2DB_($filename ,$tbl_attrib ,$tbl_geom) {
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
