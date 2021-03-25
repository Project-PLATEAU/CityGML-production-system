<?php
class ksk3d_functions_logic{
  static function bg_exec($userID ,$pjt_id, $ck_item, $ck_target_unit, $ck_target_filter, $ck_mes){
    ksk3d_console_log("ksk3d_functions_logic::bg_exec:($userID ,$pjt_id, $ck_item, $ck_target_unit, $ck_target_filter, $ck_mes)");
    global $wpdb;

    $tbl_ref_tbl = $wpdb->prefix .ksk3d_pjt::$tbl_ref[0]['table'];
    $tbl_ref_ref = $wpdb->prefix .ksk3d_pjt::$tbl_ref[0]['ref'];
    $tbl_ref_on = ksk3d_pjt::$tbl_ref[0]['on'];
    $tbl_id_name = ksk3d_pjt::$setting['id'];
    $wh = "";

    $rslt_err = 0;
    $rslt_err_mes = "";
    $rslt_err_mes_tmp = "";
    $rslt_mes = "";
    $rslt_err_file = 0;
    $rslt_err_fileall = 0;
    $takeover = "";
    $upload_dir = ksk3d_upload_dir();

    if (preg_match('/C02/i' ,$ck_item)){
      $rslt_err_mes .= <<<EOL
■地物型ごとのインスタンス数は次のとおりです。<br>
参照データに含まれるデータ数とインスタンス数の差を計算し、その絶対値の和をエラーの数としてください。<br>
EOL
;
    }
          
    $tab = 'ck_logic2|'.$ck_target_unit.'|'.$ck_item;

    $sql = "a.id as id ,b.file_name as file_name ,b.file_path as file_path";
    foreach(ksk3d_pjt::$field_ref as $list){
      if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
        if (isset($list['dbField'])){
          $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
        }
      }
    }

    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.user_id={$userID} and a.{$tbl_id_name}={$pjt_id} {$wh} ORDER BY a.id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql);

    if (count($rows)>0){
      foreach($rows as $row) {
        $rslt_mes_ct = [];
        
        if ($ck_target_unit=='ck_logic2_file'){
          $file1 = $row->{'file_path'}."/".$row->{'file_name'};
          $targets = glob($file1);
          $rslt_err_mes_tmp = "■".$row->{'features_name'}."について<br>\n";
          $rslt_mes .= "■".$row->{'features_name'}."について、".count($targets)."ファイルを検査対象にしました。<br>\n";
        } else {
          $targets = [$row->{'file_path'}."/".$row->{'file_name'}];
          $rslt_err_mes .= "・".$row->{'features_name'}."=";
          $rslt_mes .= "■".$row->{'features_name'}."について、検査しました。<br>\n";
        }
        $rslt_err_fileall += count(glob($row->{'file_path'}."/".$row->{'file_name'}));
        
        if (preg_match('/L05/i' ,$ck_item)){
          $rslt_err_mes_tmp .= "空間座標参照系のURIが、製品仕様書に示された二つのURIのいずれとも合致しない箇所がありました。空間座標参照系を製品仕様書に示されたURIと一致するように見直してください。<br>\n";
        } else if (preg_match('/L06/i' ,$ck_item)){
          $rslt_err_mes_tmp .= "boundedByにより指定された、緯度、経度及び標高の下限値及び上限値を超える座標値がありました。座標値の修正、またはboundedByによる緯度、経度及び標高の下限値、上限値を見直してください。<br>\n";
        } else if (preg_match('/L20|T05|T06|T07/i' ,$ck_item)){
          $rslt_err_mes_tmp .= "次の確認事項が見つかりました。<br>\n";
        }

        
        foreach ($targets as $target){
          ksk3d_console_log("target:".$target);
          $target = substr($target ,mb_strlen($row->{'file_path'})+1);
          if ($ck_target_unit=='ck_logic2_file'){
            $rslt_err_mes_tmp2 = "・".preg_replace('{^(.+?/).+$}',"",$target)."<br>\n";
          } else {
            $rslt_err_mes_tmp2 = "";
          }

          $ck_result = [];
          $ck_result = ksk3d_logic_check(
            $ck_item,
            $row->{'file_path'}."/".$target,
            $ck_target_unit,
            $takeover
          );
          ksk3d_console_log("ck_result");
          ksk3d_console_log($ck_result);
          
          if ($ck_result[0]>0){
            $rslt_err += $ck_result[0];
            $rslt_err_file++;
          }
          
          if (isset($ck_result[1])){
            if (!empty($ck_result[1])){
              $rslt_err_mes .= $rslt_err_mes_tmp.$rslt_err_mes_tmp2.$ck_result[1]."<br>\n";
              $rslt_err_mes_tmp = "";
              $rslt_err_mes_tmp2 = "";
            }
            if (isset($ck_result[2])){
              if (!empty($ck_result[2])){
                if (is_array($ck_result[2])){
                  for ($i=0; $i<count($ck_result[2]); $i++){
                    if (isset($rslt_mes_ct[$i])){
                      $rslt_mes_ct[$i] += $ck_result[2][$i];
                    } else {
                      $rslt_mes_ct[$i] = $ck_result[2][$i];
                    }
                  }
                } else {
                  if (isset($rslt_mes_ct[0])){
                    $rslt_mes_ct[0] += $ck_result[2];
                  } else {
                    $rslt_mes_ct[0] = $ck_result[2];
                  }
                }
              }
              if (isset($ck_result[3])){$takeover = $ck_result[3];}
            }
          }
        }
        
        if (!empty($rslt_mes_ct)){
          $rslt_mes .= "検査対象のうち、次を対象外にしました。<br>\n";
          if (preg_match('/C01/i' ,$ck_item)){
            $rslt_mes .= $rslt_mes_ct[0]."ファイルについて、gml::idは見つかりませんでした。<br>\n";
          } else if (preg_match('/L20/i' ,$ck_item)){
            if (!empty($rslt_mes_ct[0])){$rslt_mes .= $rslt_mes_ct[0]."ファイルについて、bldg:BuildingInstallationは見つかりませんでした。<br>\n";}
            if (!empty($rslt_mes_ct[1])){$rslt_mes .= $rslt_mes_ct[1]."ファイルについて、拡張属性は見つかりませんでした。<br>\n";}
            if (!empty($rslt_mes_ct[2])){$rslt_mes .= $rslt_mes_ct[2]."ファイルについて、bldg:Building及びbldg:BuildingPartのインスタンスは見つかりませんでした。<br>\n";}
          } else if (preg_match('/T05/i' ,$ck_item)){
            if (!empty($rslt_mes_ct[0])){$rslt_mes .= $rslt_mes_ct[0]."ファイルについて、ID参照は見つかりませんでした。<br>\n";}
          } else if (preg_match('/T06/i' ,$ck_item)){
            if (!empty($rslt_mes_ct[0])){$rslt_mes .= $rslt_mes_ct[0]."ファイルについて、bldg:BuildingInstallationは見つかりませんでした。<br>\n";}
            if (!empty($rslt_mes_ct[1])){$rslt_mes .= $rslt_mes_ct[1]."ファイルについて、bldg:BuildingInstallationのインスタンスは見つかりませんでした。<br>\n";}
          } else if (preg_match('/T07/i' ,$ck_item)){
            if (!empty($rslt_mes_ct[0])){$rslt_mes .= $rslt_mes_ct[0]."ファイルについて、汎用都市オブジェクト（gen:GenericCityObject）は見つかりませんでした。<br>\n";}
            if (!empty($rslt_mes_ct[1])){$rslt_mes .= $rslt_mes_ct[1]."ファイルについて、汎用都市オブジェクト（gen:GenericCityObject）のインスタンスは見つかりませんでした。<br>\n";}
          }
        }
      }
      
      ksk3d_console_log("ksk3d_functions_logic::save:$userID ,$pjt_id ,$ck_item ,$rslt_err ,$rslt_mes ,$rslt_err_mes ,$rslt_err_file ,$rslt_err_fileall");
      ksk3d_functions_logic::save($userID ,$pjt_id ,$ck_item ,$rslt_err ,$rslt_mes ,$rslt_err_mes ,$rslt_err_file ,$rslt_err_fileall);
    }
  }

  static function bg_exec_group($userID ,$parrent_id){
    global $wpdb;
    
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_PJT;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_row($prepared, ARRAY_A);
    $tbl_id_name = ksk3d_pjt::$setting['id'];
    $pjt_id = $rows[$tbl_id_name];

    $result = ksk3d_pjt::disp_prj_data($parrent_id);
    $datasets = $result[1];
    foreach ($datasets as $dataset){
      ksk3d_functions_zip::fileid_extractTo($dataset['file_id']);
    }

    $tbl_ck = $wpdb->prefix .KSK3D_TABLE_CHK_MENU;
    $sql = "SELECT * FROM {$tbl_ck} WHERE type='logic' ORDER BY check_item;";    
    ksk3d_console_log("sql:".$prepared);
    $rows = $wpdb->get_results($sql, ARRAY_A);

    foreach($rows as $row){
      $ck_item = $row['check_item'];
      $ck_target_unit = $row['target_unit'];
      $ck_target_filter = $row['target_filter'];
      $ck_mes = $row['method'];
      static::bg_exec($userID ,$pjt_id, $ck_item, $ck_target_unit, $ck_target_filter, $ck_mes);
    }

    foreach ($datasets as $dataset){
      ksk3d_fileid_zip_Compress_unlink($dataset['file_id']);
    }
  }

  static function save($userID ,$pjt_id ,$ck_item ,$rslt_err ,$rslt_mes ,$rslt_err_mes ,$rslt_err_file ,$rslt_err_fileall){
    $tbl_id_name = ksk3d_pjt::$setting['id'];

    if (strlen($rslt_err_mes)>110000){
      $rslt_err_mes = substr($rslt_err_mes ,0 ,100000)."<br>\n文字数が多すぎるため、100000字を超えた文字を削除しました。";
    }

    global $wpdb;

    $tbl_rslt = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;
    $sql = "SELECT times FROM {$tbl_rslt} WHERE user_id={$userID} and {$tbl_id_name}={$pjt_id} and check_item='{$ck_item}';";
    $rows = $wpdb->get_results($sql);
    if (count($rows)>0){
      $rslt_ct = $rows[0]->{'times'}+1;
      $wpdb->update( 
        $tbl_rslt,
        array( 
          'times' =>  $rslt_ct,
          'errfile_ct' =>  $rslt_err_file,
          'allfile_ct' =>  $rslt_err_fileall,
          'registration_date' =>  current_time('mysql'),
          'check_result' =>  $rslt_err,
          'check_description' =>  $rslt_mes,
          'err_description' =>  $rslt_err_mes
        ), 
        array(
          'user_id' =>  $userID,
          'pjt_id' =>  $pjt_id,
          'check_item' =>  $ck_item
        ),
        array(
          '%d',
          '%d',
          '%d',
          '%s',
          '%d',
          '%s',
          '%s'
        ), 
        array(
          '%d',
          '%d',
          '%s'
        )
      );
    } else {
      $rslt_ct = 1;
      $result = $wpdb->insert(
        $tbl_rslt,
        array(
          'user_id' =>  $userID,
          'pjt_id' =>  $pjt_id,
          'check_item' =>  $ck_item,
          'times' =>  $rslt_ct,
          'errfile_ct' =>  $rslt_err_file,
          'allfile_ct' =>  $rslt_err_fileall,
          'registration_date' =>  current_time('mysql'),
          'check_result' =>  $rslt_err,
          'check_description' =>  $rslt_mes,
          'err_description' =>  $rslt_err_mes
        ),
        array(
          '%d',
          '%d',
          '%s',
          '%d',
          '%d',
          '%d',
          '%s',
          '%d',
          '%s',
          '%s',
        )
      );
    }

    if (strlen($rslt_err_mes)>600){
      $rslt_err_mes = substr($rslt_err_mes ,0 ,500)."<br>\n文字数が多すぎるため、500字を超えた文字を削除しました。";
    }

    $tbl_log = $wpdb->prefix .KSK3D_TABLE_CHK_LOG;
    $result = $wpdb->insert(
      $tbl_log,
      array(
        'id' =>  'id',
        'user_id' =>  $userID,
        'pjt_id' =>  $pjt_id,
        'check_item' =>  $ck_item,
        'times' =>  $rslt_ct,
        'errfile_ct' =>  $rslt_err_file,
        'allfile_ct' =>  $rslt_err_fileall,
        'registration_date' =>  current_time('mysql'),
        'check_result' =>  $rslt_err,
        'check_description' =>  $rslt_mes,
        'err_description' =>  $rslt_err_mes
      ),
        array(
          '%d',
          '%d',
          '%d',
          '%s',
          '%d',
          '%d',
          '%d',
          '%s',
          '%d',
          '%s',
          '%s',
        )
      );

  }

}
