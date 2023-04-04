<?php
class ksk3d_functions_pjt{
  static $pattern = [
    // フォルダ名にオプションが付与されているとマッチしない事象の修正
    //'3dcitymodel' => '{[0-9]+/(3d.*|udx)/.+\.(gml|xml|zip)}i',
    //'3dcitymodel_nozip' => '{[0-9]+/(3d.*|udx)/.+\.(gml|xml)}i',
    //'3dcitymodel_zip' => '{[0-9]+/(3d.*|udx)/.+\.(zip)}i'
    '3dcitymodel' => '{[0-9]+(_[^_]+){0,}/(3d.*|udx)/.+\.(gml|xml|zip)}i',
    '3dcitymodel_nozip' => '{[0-9]+(_[^_]+){0,}/(3d.*|udx)/.+\.(gml|xml)}i',
    '3dcitymodel_zip' => '{[0-9]+(_[^_]+){0,}/(3d.*|udx)/.+\.(zip)}i'
  ];
  
  static $feature_temp =[
    'bldg' => array(
      // LOD3の記述を追加
      'feature' => '建築物（LOD1、LOD2、LOD3）、建築物部分、建築物付属物、及びこれらの境界面'
    ),
    'tran' => array(
      'feature' => '道路'
    ),
    'luse' => array(
      'feature' => '土地利用'
    ),
    'fld' => array(
      'feature' => '洪水浸水想定区域'
    ),
    'tnm' => array(
      'feature' => '津波浸水想定'
    ),
    'lsld' => array(
      'feature' => '土砂災害警戒区域'
    ),
    'urf' => array(
      'feature' => '都市計画区域、区域区分、地域地区'
    ),
    'dem' => array(
      'feature' => '起伏'
    ),
    // 以下追加
    'frn' => array(
      'feature' => '都市設備'
    ),
    'htd' => array(
      'feature' => '高潮浸水想定区域'
    ),
    'ifld' => array(
      'feature' => '内水浸水想定区域'
    ),
    'veg' => array(
      'feature' => '植生'
    )
  ];

  static function zip_sortingTo($zipfile ,$user_id ,$pjt_id ,$file_id){
    ksk3d_log(__METHOD__."(".__LINE__.")");
    ksk3d_log("zipFile: $zipfile");
    ksk3d_log("user_id: $user_id");
    ksk3d_log("pjt_id: $pjt_id");
    ksk3d_log("file_id: $file_id");

    global $wpdb;
    $zip = new ZipArchive;
    $dr = "";
    $file_id0 = $file_id;
    if ($zip->open($zipfile) === TRUE) {
      $feature_temp = static::$feature_temp;

      $upload_dir = ksk3d_upload_dir();
      foreach($feature_temp as &$feature){
        $feature['file_id'] = $file_id++;
        $feature['directory'] = $upload_dir ."/" .$feature['file_id'];
        if (file_exists($feature['directory'])){
          $tmp = false;
        } else {
          $tmp = mkdir($feature['directory']);
          ksk3d_log(__METHOD__."(".__LINE__."): mkdir:".$feature['directory']);
        }
        while (! $tmp){
          $feature['file_id'] = $file_id++;
          $feature['directory'] = $upload_dir ."/" .$feature['file_id'];
          if (file_exists($feature['directory'])){
            $tmp = false;
          } else {
            $tmp = mkdir($feature['directory']);
            ksk3d_log(__METHOD__."(".__LINE__."): mkdir:".$feature['directory']);
          }
        }
        chmod($feature['directory'], 0777);
        $feature['count'] = 0;
      }
      ksk3d_console_log($feature_temp);

      $codelists = [];
      $count = $zip->numFiles;
      ksk3d_log("ファイル数カウント: $count");
      ksk3d_log("マッチパターン:" . static::$pattern['3dcitymodel']);
      if ($count > 0){
        $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;
        $tbl_pjt_data = $wpdb->prefix .KSK3D_TABLE_PJT_DATA;
        for ($i = 0; $i < $count; $i++) {
          $file_info = pathinfo($zip->getNameIndex($i));

          if (preg_match(static::$pattern['3dcitymodel'] ,$zip->getNameIndex($i))==1){
            ksk3d_log("File match: ".($zip->getNameIndex($i)));

            $geom_info = explode('_' ,$file_info['filename'] ,3);
            if (!isset($geom_info[2])){
              $geom_info[2] = "";
              if (!isset($geom_info[1])){
                $geom_info[1] = "";
              }
            }
            if (! array_key_exists($geom_info[1] ,$feature_temp)){
              $file_id++;
              $feature_temp[$geom_info[1]] = array(
                'feature' => 'その他',
                'file_id' => $file_id,
                'directory' => $upload_dir ."/" .$file_id
              );
              if (file_exists($feature_temp[$geom_info[1]]['directory'])){
                $tmp = false;
              } else {
                $tmp = mkdir($feature_temp[$geom_info[1]]['directory']);
              }
              while (! $tmp){
                $feature_temp[$geom_info[1]]['file_id'] = $file_id++;
                $feature_temp[$geom_info[1]]['directory'] = $upload_dir ."/" .$feature['file_id'];
                if (file_exists($feature_temp[$geom_info[1]]['directory'])){
                  $tmp = false;
                } else {
                  $tmp = mkdir($feature_temp[$geom_info[1]]['directory']);
                }
              }
              chmod($feature_temp[$geom_info[1]]['directory'], 0777);
              $feature_temp[$geom_info[1]]['count'] = 0;
            }
            
            $feature_temp[$geom_info[1]]['count']++;
            ksk3d_console_log("zip->extractTo({$feature_temp[$geom_info[1]]['directory']} ,{$file_info['dirname']}/{$file_info['basename']})");
            $zip->extractTo($feature_temp[$geom_info[1]]['directory'] ,$file_info['dirname']."/".$file_info['basename']);
            $extract_file1 = $feature_temp[$geom_info[1]]['directory']."/".$file_info['dirname']."/".$file_info['basename'];
            ksk3d_console_log("extract_file1:".$extract_file1);
            if (preg_match('/\.zip$/i' ,$extract_file1)==1){
              $extract_file1 = ksk3d_zip_extractTo1($extract_file1);
              ksk3d_console_log("extract_file1:".$extract_file1);
              $file_info = pathinfo(substr($extract_file1 ,strlen($feature_temp[$geom_info[1]]['directory'])+1));
              $file = ksk3d_format($extract_file1);
              unlink($extract_file1);
            } else {
              $file = ksk3d_format($extract_file1);
            }
            $feature_temp[$geom_info[1]]['format'] = $file['format'];
            
            if ($feature_temp[$geom_info[1]]['count'] == 1){
              $feature_temp[$geom_info[1]]['filename1'] = $geom_info[0];
              $feature_temp[$geom_info[1]]['filename2'] = $geom_info[1];
              $feature_temp[$geom_info[1]]['filename3'] = $geom_info[2];
              $feature_temp[$geom_info[1]]['extension'] = $file_info['extension'];
              $feature_temp[$geom_info[1]]['directory2'] = $file_info['dirname'];
              $feature_temp[$geom_info[1]]['format'] = $file['format'];
            } else if ($feature_temp[$geom_info[1]]['count'] > 1){
              if ($feature_temp[$geom_info[1]]['filename1'] != "*"){
              if ($feature_temp[$geom_info[1]]['filename1'] != $geom_info[0]){
                $feature_temp[$geom_info[1]]['filename1'] = "*";
              }}
              if ($feature_temp[$geom_info[1]]['filename2'] != "*"){
              if ($feature_temp[$geom_info[1]]['filename2'] != $geom_info[1]){
                $feature_temp[$geom_info[1]]['filename2'] = "*";
              }}
              if ($feature_temp[$geom_info[1]]['filename3'] != "*"){
              if ($feature_temp[$geom_info[1]]['filename3'] != $geom_info[2]){
                $feature_temp[$geom_info[1]]['filename3'] = "*";
              }}
              if ($feature_temp[$geom_info[1]]['extension'] != "*"){
              if ($feature_temp[$geom_info[1]]['extension'] != $file_info['extension']){
                $feature_temp[$geom_info[1]]['extension'] = "*";
              }}
            }
            // フォルダ名にオプションが付与されているとマッチしない事象の修正
          //} else if (preg_match('/[0-9]+\/codelists/' ,$file_info['dirname'])==1){
          } else if (preg_match('/[0-9]+(_[^_]+){0,}\/codelists/' ,$file_info['dirname'])==1) {
            ksk3d_log("File match2: ".($zip->getNameIndex($i)));
            array_push($codelists ,$zip->getNameIndex($i));
          } else {
            ksk3d_log("File not match: ".($zip->getNameIndex($i)));
          }
        }
        
        foreach($feature_temp as &$feature){
          if ($feature['count'] > 0){
            if (count($codelists)>0){
              foreach($codelists as $codelist){
                $file_info = pathinfo($codelist);
                if (!is_dir($feature['directory']."/".$file_info['dirname'])){
                  ksk3d_console_log("mkdir:".$feature['directory']."/".$file_info['dirname']);
                  mkdir($feature['directory']."/".$file_info['dirname']);
                  chmod($feature['directory']."/".$file_info['dirname'], 0777);
                }
                $zip->extractTo($feature['directory'] ,$codelist);
              }
            }

            $file_name = $feature['filename1'];
            if ((!empty($feature['filename2'])) or (!empty($feature['filename3']))){
              $file_name .= "_".$feature['filename2'];
              if (!empty($feature['filename3'])){
                $file_name .= "_".$feature['filename3'];
              }
            }
            $file_name .= ".".$feature['extension'];
            $zip_file = ksk3d_fileid_zip_Compress($feature['file_id'] ,true);
            $zip_filepath = pathinfo($zip_file);
            
            $file_size = ksk3d_dir_size($feature['directory']);

            $result = $wpdb->insert(
              $tbl_data,
              array(
                'user_id' =>  $user_id,
                'display_name' =>  $file_name,
                'file_id' =>  $feature['file_id'],
                'file_format' =>  $feature['format'],
                'file_name' =>  $file_name,
                'file_path' =>  $feature['directory']."/".$feature['directory2'],
                'file_size' =>  $file_size,
                'zip_name' =>  $zip_filepath['basename'],
                'zip_path' =>  $zip_filepath['dirname'],
                'registration_date' =>  current_time('mysql')
              )
            );
            $result = $wpdb->insert(
              $tbl_pjt_data,
              array(
                'user_id' =>  $user_id,
                'pjt_id' =>  $pjt_id,
                'features_name' =>  $feature['feature'],
                'dataset_id' =>  $feature['file_id']
              )
            );
          } else {
            ksk3d_delTree($feature['directory']);
          }
        }
      } else {
        return false;
      }
      $zip->close();
      ksk3d_console_log($feature_temp);
      ksk3d_dataset_delete($file_id0);
      return true;
    } else {
      
      ksk3d_log(__METHOD__."(".__LINE__."): Zipファイルの展開に失敗しました。");
      ksk3d_console_log(__METHOD__."(".__LINE__."): Zipファイルの展開に失敗しました。");
      return false;
    }
  }

  static function zip_extractTo($zipfile ,$user_id ,$pjt_id ,$file_id){
    global $wpdb;
    $zip = new ZipArchive;
    $dr = "";
    if ($zip->open($zipfile) === TRUE) {
      $feature_temp = static::$feature_temp;

      $upload_dir = ksk3d_upload_dir();
      foreach($feature_temp as &$feature){
        $feature['file_id'] = $file_id++;
        $feature['directory'] = $upload_dir ."/" .$feature['file_id'];
        if (file_exists($feature['directory'])){
          $tmp = false;
        } else {
          $tmp = mkdir($feature['directory']);
        }
        while (! $tmp){
          $feature['file_id'] = $file_id++;
          $feature['directory'] = $upload_dir ."/" .$feature['file_id'];
          if (file_exists($feature['directory'])){
            $tmp = false;
          } else {
            $tmp = mkdir($feature['directory']);
          }
        }
        chmod($feature['directory'], 0777);
        $feature['count'] = 0;
      }
      ksk3d_console_log($feature_temp);

      $codelists = [];
      $count = $zip->numFiles;
      if ($count > 0){
        $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;
        $tbl_pjt_data = $wpdb->prefix .KSK3D_TABLE_PJT_DATA;
        for ($i = 0; $i < $count; $i++) {
          $file_info = pathinfo($zip->getNameIndex($i));
          if (preg_match(static::$pattern['3dcitymodel'] ,$zip->getNameIndex($i))==1){

            $geom_info = explode('_' ,$file_info['filename'] ,3);
            if (!isset($geom_info[2])){
              $geom_info[2] = "";
              if (!isset($geom_info[1])){
                $geom_info[1] = "";
              }
            }
            if (! array_key_exists($geom_info[1] ,$feature_temp)){
              $file_id++;
              $feature_temp[$geom_info[1]] = array(
                'feature' => 'その他',
                'file_id' => $file_id,
                'directory' => $upload_dir ."/" .$file_id
              );
              if (file_exists($feature_temp[$geom_info[1]]['directory'])){
                $tmp = false;
              } else {
                $tmp = mkdir($feature_temp[$geom_info[1]]['directory']);
              }
              while (! $tmp){
                $feature_temp[$geom_info[1]]['file_id'] = $file_id++;
                $feature_temp[$geom_info[1]]['directory'] = $upload_dir ."/" .$feature['file_id'];
                if (file_exists($feature_temp[$geom_info[1]]['directory'])){
                  $tmp = false;
                } else {
                  $tmp = mkdir($feature_temp[$geom_info[1]]['directory']);
                }
              }
              chmod($feature_temp[$geom_info[1]]['directory'], 0777);
              $feature_temp[$geom_info[1]]['count'] = 0;
            }
            
            $feature_temp[$geom_info[1]]['count']++;
            ksk3d_console_log("zip->extractTo({$feature_temp[$geom_info[1]]['directory']} ,{$file_info['dirname']}/{$file_info['basename']})");
            $zip->extractTo($feature_temp[$geom_info[1]]['directory'] ,$file_info['dirname']."/".$file_info['basename']);
            if ($feature_temp[$geom_info[1]]['count'] == 1){
              $feature_temp[$geom_info[1]]['filename1'] = $geom_info[0];
              $feature_temp[$geom_info[1]]['filename2'] = $geom_info[1];
              $feature_temp[$geom_info[1]]['filename3'] = $geom_info[2];
              $feature_temp[$geom_info[1]]['extension'] = $file_info['extension'];
              $feature_temp[$geom_info[1]]['directory2'] = $file_info['dirname'];
              $file = ksk3d_format($feature_temp[$geom_info[1]]['directory']."/".$file_info['dirname']."/".$file_info['basename']);
              $feature_temp[$geom_info[1]]['format'] = $file['format'];
            } else if ($feature_temp[$geom_info[1]]['count'] > 1){
              if ($feature_temp[$geom_info[1]]['filename1'] != "*"){
              if ($feature_temp[$geom_info[1]]['filename1'] != $geom_info[0]){
                $feature_temp[$geom_info[1]]['filename1'] = "*";
              }}
              if ($feature_temp[$geom_info[1]]['filename2'] != "*"){
              if ($feature_temp[$geom_info[1]]['filename2'] != $geom_info[1]){
                $feature_temp[$geom_info[1]]['filename2'] = "*";
              }}
              if ($feature_temp[$geom_info[1]]['filename3'] != "*"){
              if ($feature_temp[$geom_info[1]]['filename3'] != $geom_info[2]){
                $feature_temp[$geom_info[1]]['filename3'] = "*";
              }}
              if ($feature_temp[$geom_info[1]]['extension'] != "*"){
              if ($feature_temp[$geom_info[1]]['extension'] != $file_info['extension']){
                $feature_temp[$geom_info[1]]['extension'] = "*";
              }}
            }
          // フォルダ名にオプションが付与されているとマッチしない事象の修正
          //} else if (preg_match('/[0-9]+\/codelists/' ,$file_info['dirname'])==1){
          } else if (preg_match('/[0-9]+(_[^_]+){0,}\/codelists/' ,$file_info['dirname'])==1){
            array_push($codelists ,$zip->getNameIndex($i));
          } else {
          }
        }
        
        foreach($feature_temp as &$feature){
          if ($feature['count'] > 0){
            if (count($codelists)>0){
              foreach($codelists as $codelist){
                $file_info = pathinfo($codelist);
                if (!is_dir($feature['directory']."/".$file_info['dirname'])){
                  ksk3d_console_log("mkdir:".$feature['directory']."/".$file_info['dirname']);
                  mkdir($feature['directory']."/".$file_info['dirname']);
                  chmod($feature['directory']."/".$file_info['dirname'], 0777);
                }
                $zip->extractTo($feature['directory'] ,$codelist);
              }
            }

            $file_name = $feature['filename1'];
            if ((!empty($feature['filename2'])) or (!empty($feature['filename3']))){
              $file_name .= "_".$feature['filename2'];
              if (!empty($feature['filename3'])){
                $file_name .= "_".$feature['filename3'];
              }
            }
            $file_name .= ".".$feature['extension'];
            $file_size = ksk3d_dir_size($feature['directory']);
            $result = $wpdb->insert(
              $tbl_data,
              array(
                'user_id' =>  $user_id,
                'display_name' =>  $file_name,
                'file_id' =>  $feature['file_id'],
                'file_format' =>  $feature['format'],
                'file_name' =>  $file_name,
                'file_path' =>  $feature['directory']."/".$feature['directory2'],
                'file_size' =>  $file_size,
                'registration_date' =>  current_time('mysql')
              )
            );
            $result = $wpdb->insert(
              $tbl_pjt_data,
              array(
                'user_id' =>  $user_id,
                'pjt_id' =>  $pjt_id,
                'features_name' =>  $feature['feature'],
                'dataset_id' =>  $feature['file_id']
              )
            );
          } else {
            ksk3d_delTree($feature['directory']);
          }
        }
      } else {
        return false;
      }
      $zip->close();
      ksk3d_console_log($feature_temp);
      return true;
    } else {
      return false;
    }
  }
}
