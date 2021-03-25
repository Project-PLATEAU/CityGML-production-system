<?php
class ksk3d_functions_zip{

  static function fileid_extractTo($file_id){
    $zip_pathinfo = static::pathinfo($file_id);
    //ZIP展開
    if (is_file($zip_pathinfo['fullpath'])){
      $zip_extractTo = ksk3d_zip_extractTo($zip_pathinfo['fullpath']);
      $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
          $zip_pathinfo['dirname'],
          FilesystemIterator::SKIP_DOTS
          |FilesystemIterator::KEY_AS_PATHNAME
          |FilesystemIterator::CURRENT_AS_FILEINFO
        ), RecursiveIteratorIterator::SELF_FIRST
      );
      //201129確認
      foreach($iterator as $f => $info){
        if ($info->isFile()){
          if (preg_match(ksk3d_functions_pjt::$pattern['3dcitymodel_zip'],$f)==1){
            ksk3d_zip_extractTo($f);
            unlink($f);
          }
        } else {
          ksk3d_console_log("file not found:".$f);
        }
      }
    } else {
      ksk3d_fileid_zip_Compress($file_id);
    }
  }

  //zip内のファイルをzipに移動
  static function filemoveall($zip_src ,$zip_move) {
    $zip_src_pathinfo = pathinfo($zip_src);
    $dir = $zip_src_pathinfo['dirname'];
    
    $zip1 = new ZipArchive;
    $zip2 = new ZipArchive;
    if ($zip1->open($zip_src) === TRUE) {
    if ($zip2->open($zip_move) === TRUE) {
      $count = $zip1->numFiles;
      for ($i = 0; $i < $count; $i++) {
        $f = $zip1->getNameIndex($i);
        if (substr($f, -1, 1) != '/'){//dirではない
          ksk3d_console_log("file:".$dir."/".$f);
          $zip1->extractTo($dir, $f);
          $zip2->addFile($dir."/".$f ,$f);
          //unlink($dir."/".$f);
        }
      }
    }}
    $zip2->close();
    
    for ($i = 0; $i < $count; $i++) {
      $f = $zip1->getNameIndex($i);
      ksk3d_console_log("f:".$f);
      if (substr($f, -1, 1) != '/'){//dirではない
        unlink($dir."/".$f);
      }
    }
    $zip1->close();
  }

  static function file_compress($file ,$flg_del=false){
    $pathinfo = pathinfo($file);
    $zipfile = $pathinfo['dirname']."/".$pathinfo['filename'].".zip";
    $zip = new ZipArchive();
    $res = $zip->open($zipfile, ZipArchive::CREATE);
    $zip->addFile($file, $pathinfo['basename']);
    $zip->close();
    if ($flg_del){unlink($file);}
    return $zipfile;
  }


  //zip内の対象ファイルの情報を返す
  static function pathinfo($file_id){
    $userID = ksk3d_get_current_user_id();
    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "SELECT zip_name,zip_path,file_format,file_name,file_path FROM {$tbl_name} WHERE user_id={$userID} and file_id={$file_id};";
    $result = $wpdb->get_row($sql ,ARRAY_A);

//要修正
    //ファイルにzipが登録されている場合のクリーニング
    if ($result['file_format']=='zip'){
      $zip_fileinfo = ksk3d_zip_fileinfo($result['file_path'].'/'.$result['file_name']);
      //DB更新
      $result = $wpdb->update(
        $tbl_name,
        array(
          'file_format' =>  $zip_fileinfo['format'],
          'file_name' =>  $zip_fileinfo['basename'],
          'file_path' =>  $zip_fileinfo['extract_filepath'],
          'zip_name' =>  $result['file_name'],
          'zip_path' =>  $result['file_path']
        ),
        array(
          'user_id' =>  $userID,
          'file_id' =>  $file_id
        ),
        array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s'
        ),
        array(
          '%d',
          '%d'
        )
      );
    }
    
    if (empty($result['zip_path'])){
      $result['zip_path'] = ksk3d_upload_dir()."/".$file_id;
    }
    if (empty($result['zip_name'])){
      $result['zip_name'] = "dataset_".$file_id.".zip";
    }

    return array(
      'fullpath'=>$result['zip_path']."/".$result['zip_name'],
      'dirname'=>$result['zip_path'],
      'basename'=>$result['zip_name']
    );
  }
}