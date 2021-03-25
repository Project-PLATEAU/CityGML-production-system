<?php
function ksk3d_acc_update($userID){
  
  global $wpdb;
  $tbl_acc = $wpdb->prefix .KSK3D_TABLE_ACC;

  $wpdb->query("delete from {$tbl_acc} where access_date < SUBTIME(CURRENT_TIMESTAMP ,'".KSK3D_ACCESS_TIME."');");
  
  $id = $wpdb->get_var("select user_id from {$tbl_acc} where user_id={$userID};");
  
  if (is_null($id)){
    ksk3d_console_log("select count(user_id) from {$tbl_acc};");
    $ct = $wpdb->get_var("select count(user_id) from {$tbl_acc};");
    ksk3d_console_log($ct);
    if ($ct >= KSK3D_ACCESS_LIMIT){
      $flg = 1;
      echo "<script type='text/javascript'>alert(\"".KSK3D_ACCESS_ERR_MES."\");</script>";
    } else {
      $flg = 0;
    }
    $wpdb->insert(
      $tbl_acc, 
      array( 
        'user_id' => $userID,
        'access_date' => current_time('mysql'),
        'flg_mes' => $flg
      ), 
      array( 
        '%d',
        '%s',
        '%d'
      ) 
    );
    
  } else {
    $flg = $wpdb->get_var("select flg_mes from {$tbl_acc} where user_id={$userID};");
    if ($flg > 0){
      $ct = $wpdb->get_var("select count(user_id) from {$tbl_acc};");
      if ($ct > KSK3D_ACCESS_LIMIT){
        $flg = 1;
      } else {
        $flg = 0;
        $wpdb->query("update {$tbl_acc} set flg_mes={$flg} where user_id={$userID};");
      }
    }
    $wpdb->query("update {$tbl_acc} set access_date=CURRENT_TIMESTAMP where user_id={$userID};");
  }
  
  if ($flg > 0){
    ksk3d_console_log("エラー");
    $err = KSK3D_ACCESS_ERR_MES;
    $script = <<<EOL
<script type="text/javascript">
  if (document.getElementById("marquee") == null){
    var newElement = document.createElement("marquee");
    var newContent = document.createTextNode("{$err}");
    newElement.appendChild(newContent);
    newElement.setAttribute("behavior","scroll");
    newElement.setAttribute("id","marquee");
    var parentDiv = document.getElementById("headerTop");
    parentDiv.appendChild(newElement);
  }
</script>

EOL
;
    echo $script;

  }
  
}

function ksk3d_array_del_val($array ,$val){
  while( ($index = array_search( $val, $array, true )) !== false ) {
    unset( $array[$index] ) ;
  }
  return $array;
}

function ksk3d_array_push($array ,$val){
  if (is_array($array)){
    array_push($array ,$val);
  } else {
    if (empty($array)){
      $array = [$val];
    } else {
      $array = [$array ,$val];
    }
  }
  return $array;
}

function ksk3d_array_unique($array){
  if (is_array($array)){
    $array2 = array_unique($array);
  } else {
    if (empty($array)){
      $array2 = [];
    } else {
      $array2 = array($array);
    }
  }
  return $array2;
}

function ksk3d_check_usedsize(){
  $userID = ksk3d_get_current_user_id();
  global $wpdb;
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $sql = "SELECT sum(file_size) FROM {$tbl_name} WHERE user_id={$userID};";
  $size = $wpdb->get_var($sql);
  ksk3d_console_log("ksk3d_check_usedsize:".$size."<".KSK3D_FILESIZE_LIMIT);
  return($size < KSK3D_FILESIZE_LIMIT);
}

function ksk3d_chmodTree($dir) {
  $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("{$dir}/{$file}")) ? ksk3d_chmodTree("{$dir}/{$file}") : chmod("{$dir}/{$file}",0777);
    }
  return chmod($dir,0777);
}

function ksk3d_console_log($text){
  if (empty(get_option('ksk3d_option')['debug'])){return;}
  $text = json_encode($text);
  if (preg_match('{'.KSK3D_CONTENTS_PATH.'}i' ,$text)==1){
    $text = preg_replace('{'.KSK3D_CONTENTS_PATH.'}i' ,'' ,$text);
  }
    echo "<script>console.log(". $text .")</script>\n";
}

function ksk3d_delTree($dir) {
  if (!is_dir($dir)){return false;}
  ksk3d_console_log("ksk3d_delTree({$dir})");
  if (preg_match('{.+/wp-content/3d-city-management/users/.+?/.}',$dir)==0){
    ksk3d_log("Fatal error(Cancel):ksk3d_delTree({$dir})");
    return false;
  }
  
  $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("{$dir}/{$file}")) ? ksk3d_delTree("{$dir}/{$file}") : unlink("{$dir}/{$file}");
    }
  return rmdir($dir);
}

function ksk3d_dir_format($dir) {
  ksk3d_console_log("ksk3d_dir_format1:".$dir);
  list($format_array, $ext_array) = ksk3d_dir_format_($dir);
  $format_array = ksk3d_array_del_val($format_array ,'');
  $ext_array = ksk3d_array_del_val($ext_array ,'');
  ksk3d_console_log($format_array);
  ksk3d_console_log($ext_array);

  if (is_array($format_array)){
    if (count($format_array)>1){
      if (
        in_array('zip', $format_array)
      ){
        $format_array = ksk3d_array_del_val($format_array ,'zip');
        $ext_array = ksk3d_array_del_val($ext_array ,'zip');
      }

      if (
        in_array('CityGML', $format_array)
        and in_array('CityGML(iur)', $format_array)
      ){
        $format_array = ksk3d_array_del_val($format_array ,'CityGML(iur)');
      }

      if (
        in_array('gml', $format_array)
        and in_array('gml(基盤地図情報)', $format_array)
      ){
        $format_array = ksk3d_array_del_val($format_array ,'gml(基盤地図情報)');
      }

      if (
        (
          in_array('CityGML', $format_array)
          or in_array('CityGML(iur)', $format_array)
        )
        and in_array('tif', $format_array)
      ){
        $format_array = ksk3d_array_del_val($format_array ,'tif');
        $ext_array = ksk3d_array_del_val($ext_array ,'tif');
      }
    }
    $format = implode(',' ,$format_array);
  }
  ksk3d_console_log($format_array);
  ksk3d_console_log($ext_array);

  if (is_array($ext_array)){
    if (count($ext_array)>1){
      $ext_array = ksk3d_array_del_val($ext_array ,'不明');
    }
    if (is_array($ext_array)){
      if (count($ext_array)>1){
        $ext = "*";
      } else {
        $ext = implode(',' ,$ext_array);
      }
    } else {
      $ext = implode(',' ,$ext_array);
    }
  }
  ksk3d_console_log($format.",".$ext);

  return array($format ,$ext);
}

function ksk3d_dir_format_($dir) {
  $format=['',''];
  $ext=['',''];
  if (is_dir($dir)){
    $handle = opendir($dir);
    while ($file = readdir($handle)) {
      if ($file != '..' && $file != '.'){
        if (is_dir($dir.'/'.$file)) {
          if (preg_match('/codelists/',$file)!=1){
            list($format2 ,$ext2) = ksk3d_dir_format_($dir.'/'.$file);
          }
        } else {
          $format_ = ksk3d_format($dir.'/'.$file);
          $format2 = array($format_['format']);
          $ext2 = array($format_['extension']); 
        }

        $format = array_merge($format ,$format2);
        $ext = array_merge($ext ,$ext2);

          if (count($format)>10){$format = array_unique($format);}
          if (count($ext)>10){$ext = array_unique($ext);}
      }
    }
  }
  
  if (is_array($format)){$format = array_unique($format);}
  if (is_array($ext)){$ext = array_unique($ext);}
  return array($format ,$ext);
}

function ksk3d_dir_size($dir) {
  $mas=0;
  if (is_dir($dir)){
    $handle = opendir($dir);
    while ($file = readdir($handle)) {
      if ($file != '..' && $file != '.'){
        if (is_dir($dir.'/'.$file)) {
          $mas += ksk3d_dir_size($dir.'/'.$file);
        } else {
          $mas += filesize($dir.'/'.$file);
        }
      }
    }
  }
  return $mas;
}

function ksk3d_download($url){
  echo "
  <a download href = \"$url\" id=\"downloadLink\" style=\"display:none;\"></a>
    <script>
      document.getElementById('downloadLink').click();
    </script>
";
}

function ksk3d_echo_debug($text){
  if (empty(get_option('ksk3d_option')['debug'])){return;}
  if(KSK3D_DEBUG){
    echo( $text."<br>" );
  }
}

function ksk3d_file1($filename){
  $filename_glob = glob($filename);
  foreach($filename_glob as $f){break;}
  ksk3d_console_log("ksk3d_file1:".$f);
  return $f;
}

function ksk3d_fileid_zip_Compress($file_id, $flg_del=false){
  ksk3d_log("ksk3d_fileid_zip_Compress:test1");
  if (empty($file_id)){
    ksk3d_log("ksk3d_fileid_zip_Compress:file_id is null");
    return false;
  }
  
  $zip_path = ksk3d_upload_dir()."/".$file_id;
  $zip_pathinfo = ksk3d_functions_zip::pathinfo($file_id);
  $zip_name = $zip_pathinfo['basename'];
  $zip_file = $zip_pathinfo['fullpath'];
  $zip = new ZipArchive();
  $res = $zip->open($zip_file, ZipArchive::CREATE);
  ksk3d_console_log("zip_file:".$zip_file);
  if($res){
    $ptn = '{^(tileset\.json|'.preg_replace('/\./','\.',$zip_name).')$}i';

    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator(
        $zip_path,
        FilesystemIterator::SKIP_DOTS
        |FilesystemIterator::KEY_AS_PATHNAME
        |FilesystemIterator::CURRENT_AS_FILEINFO
      ), RecursiveIteratorIterator::SELF_FIRST
    );
    foreach($iterator as $f => $info){
      if ($info->isFile()){
        $f2 = substr($f ,mb_strlen($zip_path)+1);
        if (preg_match($ptn ,$f2)!=1){
          if (preg_match(ksk3d_functions_pjt::$pattern['3dcitymodel_nozip'] ,$f2)==1){
            $f = ksk3d_functions_zip::file_compress($f ,true);
          }
          $zip->addFile($f, substr($f ,mb_strlen($zip_path)+1));
        }
      }
    }
    $zip->close();

    $dir = $zip_path;
    $files = array_diff(scandir($dir), array('.','..'));
    if ($flg_del){
      ksk3d_fileid_zip_Compress_unlink($file_id);
    }

    $user_id = ksk3d_get_current_user_id();
    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "UPDATE {$tbl_name} SET
    zip_name='{$zip_name}',
    zip_path='{$zip_path}'
    WHERE user_id = {$user_id} and file_id = %d;";
    $prepared = $wpdb->prepare($sql, $file_id);
    $wpdb->query($prepared);
      
    return $zip_file;
  } else {
    return false;
  }
}
function ksk3d_fileid_zip_Compress_($zip ,$dir ,$basedir ,$ptn ,$flg_del=false){
  ksk3d_console_log("ksk3d_fileid_zip_Compress_(zip ,$dir ,$basedir ,$ptn ,$flg_del)");
  $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    $f = $dir."/".$file;
    $f2 = substr($f ,mb_strlen($dir)+1);
      ksk3d_log("f:".$f);
      ksk3d_log("f2:".$f2);
    if (is_dir($f)) {
      ksk3d_fileid_zip_Compress_($zip, $f, $basedir, $ptn, $flg_del);
    } else {
      if (preg_match($ptn ,$f2)!=1){
        $zip->addFile($f, $root.substr($f, mb_strlen($basedir)));
        ksk3d_log("unlink:".$f);
        if ($flg_del){unlink($f);}
      }
    }
  }
}

function ksk3d_fileid_zip_Compress_unlink($file_id){
  ksk3d_log("ksk3d_fileid_zip_Compress_unlink:test1");
  if (empty($file_id)){
    ksk3d_log("ksk3d_fileid_zip_Compress_unlink:file_id is null");
    return false;
  }

  $zip_path = ksk3d_upload_dir()."/".$file_id;
  $zip_pathinfo = ksk3d_functions_zip::pathinfo($file_id);
  $zip_name = $zip_pathinfo['basename'];
  $zip_file = $zip_pathinfo['fullpath'];
  $ptn = '{^(tileset\.json|'.preg_replace('/\./','\.',$zip_name).')$}i';
  $dir = $zip_path;
  $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    $f = $dir."/".$file;
    $f2 = substr($f ,mb_strlen($zip_path)+1);
    if (is_dir($f)) {
      ksk3d_delTree($f);
    } else {
      if (preg_match($ptn ,$f2)!=1){
        unlink($f);
      }
    }
  }
}

function ksk3d_fileid_zip_Compress_unlink_($dir ,$ptn){
  $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    $f = $dir."/".$file;
    $f2 = substr($f ,mb_strlen($dir)+1);
    if (is_dir($f)) {
      ksk3d_fileid_zip_Compress_unlink_($zip, $f, $basedir, $flg_del);
    } else {
      if (preg_match($ptn ,$f2)!=1){
        unlink($f);
      }
    }
  }
}

function ksk3d_file_prepend($path, $data){
  if (!$fp = fopen($path, 'c+b')) { return false; }

  flock($fp, LOCK_EX);
  $data = $data . stream_get_contents($fp);
  rewind($fp);

  $result = fwrite($fp, $data);
  fflush($fp);
  flock($fp, LOCK_UN);
  fclose($fp);

  return $result;
}

function ksk3d_format($file){
  $filepath = pathinfo($file);
  if (!isset($filepath['extension'])){
    $e = "不明";
  } else {
    $e = mb_strtolower($filepath['extension']);
  }
  $ret = array(
    'format' => $e,
    'extension' => $e,
    'title' => $filepath['filename']
  );
  
  if (($e=='gml') || ($e=='xml')){
    $fgc = file_get_contents($file ,false ,null ,0 ,10000);
    ksk3d_console_log("xmlns:uro:".strpos($fgc, "xmlns:uro"));
    ksk3d_console_log("xmlns:urt:".strpos($fgc, "xmlns:urt"));

    if ((strpos($fgc, "xmlns:gml") !== false) or (strpos($fgc, "http://www.opengis.net/gml/") !== false)){
      $ret['format'] = "gml";
    }
    if (strpos($fgc, "http://fgd.gsi.go.jp/spec/2008/FGD_GMLSchema") !== false){
      $ret['format'] = "gml(基盤地図情報)";
    }
    if ((strpos($fgc, "xmlns:core") !== false) or (strpos($fgc, "http://www.opengis.net/citygml/") !== false)){
      $ret['format'] = "CityGML";
    }
    if ((strpos($fgc, "xmlns:uro") !== false) or (strpos($fgc, "xmlns:urf") !== false) or (strpos($fgc, "xmlns:urg") !== false) or (strpos($fgc, "xmlns:urt") !== false)){
      $ret['format'] = "CityGML(iur)";
    }
  } else if ($e=='kmz'){
    $ret['format'] = "kml";
  }
  return $ret;
}

function ksk3d_get_current_user_id(){
  $userID = get_current_user_id();
  ksk3d_log("get_current_user_id-".$userID);
  if ($userID == 0) {
    $auth = BAuth::get_instance();
    if ($auth->is_logged_in()){
      $member_id  = $auth->get('member_id');
      ksk3d_log("Simple Membership member_id-".$member_id);
      $email_value  = $auth->get('email');
      global $wpdb;
      $userID = $wpdb->get_var( "SELECT ID FROM {$wpdb->users} WHERE user_email='{$email_value}'" );
      ksk3d_log("Simple Membership->wp_user-".$userID);
    } else {
      $proc_id = getenv("KSK3D_USER_ID");
      if ($proc_id > 0){
        $userID = $proc_id;
        ksk3d_log("proc_user_id-").$userID;
      } else {
        ksk3d_log("Simple Membership not login");
      }
    }
  }
  ksk3d_acc_update($userID);
  return $userID;
}

function ksk3d_get_current_fileinfo($formID) {
  global $wpdb;
  $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;

  $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
  $prepared = $wpdb->prepare($sql, $formID);
  $result = $wpdb->get_row($prepared ,ARRAY_A);
  $result['file_path_abs'] = $result['file_path']."/".$result['file_name'];

  return $result;
}

function ksk3d_get_file1($path ,$filter ,$file2 = false){
  if ($file2!=false){return $file2;}
  $dir = new DirectoryIterator($path);
  $dirs = array();
  foreach ($dir as $file) {
    if ($file->isDot()){ 
        continue;
    } else if ($file->isDir()){
      $dirs[] = $file->getPathname();

    } else if ($file->isFile()){
      ksk3d_console_log("getPathname:".$file->getPathname());
      
      if (preg_match('/'.$filter.'/',$file->getPathname())){
        $file2 = $file->getPathname();
        break;
      }
    }
  }
  if ($file2==false){
    foreach ($dirs as $dir) {
      $file2 = ksk3d_get_file1($dir ,$filter ,$file2);
      if ($file2!=false){break;}
    }
  }
  return $file2;
}

function ksk3d_get_max($table_name ,$field ,$wh=""){
  global $wpdb;
  $userID = ksk3d_get_current_user_id();
  if (!empty($wh)){$wh = " and ".$wh;}
  $sql = "select ifnull(max({$field}), 0)+1 from {$table_name} where user_id={$userID} {$wh}";
  $max_id = $wpdb->get_var($sql);
  ksk3d_log("$sql");
  ksk3d_log($max_id);
  return $max_id;
}

function ksk3d_get_max_file_id(){
  global $wpdb;
  $table_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $userID = ksk3d_get_current_user_id();
  $sql = "select ifnull(max(file_id), 0)+1 from {$table_name} where user_id={$userID}";
  $max_file_id = $wpdb->get_var($sql);
  ksk3d_log("$sql");
  ksk3d_log($max_file_id);
  return $max_file_id;
}

function ksk3d_log($text){
  $log = KSK3D_CONT_LOG_PATH ."/ksk3d-".date("Ymd").".log";
  file_put_contents ($log ,date("Y-m-d H:i:s") ." " .substr($text,0,200) ."\n" ,FILE_APPEND);
  chmod ($log ,0777);
}

function ksk3d_mkdir($dr){
  ksk3d_console_log("fn:ksk3d_mkdir({$dr})");
  if (!is_dir($dr)){
    $dr2 = preg_replace('/(.*)\/.*?$/' ,'$1' ,$dr);
    if (!is_dir($dr2)){
      ksk3d_mkdir($dr2);
    }
    if (!is_dir($dr)){
      mkdir($dr);
      chmod($dr ,0777);
    }
  }
  return;
}

function ksk3d_random_word($length = 8){
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

function ksk3d_realpath($dr){
  $dr2 = $dr;
  foreach(array('/\/\.\//' ,'/\/[^\/]*?\/\.\.\//') as $key){
    while (preg_match($key ,$dr2)==1){
      $dr2 = preg_replace($key ,'/' ,$dr2);
    }    
  }
  return $dr2;
}

function ksk3d_sprintf_bytes($bytes ,$flg=0){
  $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
  $base = 1024;
  $class = min((int)log($bytes, $base), count($si_prefix) - 1);
  $sprintf_bytes = sprintf('%1.2f', $bytes / pow($base,$class)).$si_prefix[$class];
  if ($flg!=0){
    $color_prefix = array('black', 'blue', 'fuchsia', 'red', 'red', 'red', 'red', 'red');
    $sprintf_bytes = "<font color=\"{$color_prefix[$class]}\">".$sprintf_bytes."</font>";
  }
  return $sprintf_bytes;
}

function ksk3d_stripslashes_deep($value)
{
  $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);
  return $value;
}

function ksk3d_table_2rows($array) {
  $html = "    <table class=\"ksk3d_style_table_report ksk3d_style_table_name\">\n";
  $array2 = array_chunk($array ,2);
  foreach ($array2 as $row){
    $html .= "      <tr><td>{$row[0]}</td><td>{$row[1]}</td></tr>\n";
  }
  $html .= "    </table><br>\n";
  return $html;
}

function ksk3d_upload_dir(){
  $userID = ksk3d_get_current_user_id();
  if ($userID > 0){
    $dr = KSK3D_CONT_USERS_PATH."/".get_user_meta( $userID, "ksk3d_user_folder", true );
  } else {
    $dr = KSK3D_CONT_GUEST_PATH;
  }
  ksk3d_log("ksk3d_upload_dir-" .$dr);
  return $dr;
}

function ksk3d_upload_progress(){
  $inc_path = KSK3D_URL."/inc";
  return <<<EOL

<br>
<span id="progress">　</span>
<div id="progress_bar"><div class="percent">　</div></div>

<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
$(function() {
  $("form").submit(function() {
    var progress = $("#progress");
    var progress_bar = document.getElementById('progress_bar');
    var progress_bar_per = document.querySelector('.percent');

    var mes = "";

    var f = function() {
      $.getJSON("{$inc_path}/progress.php", function(data) {
        console.dir(data);

        if (data != null) {
          progress_bar.className = 'loading';
          var percentLoaded = Math.round(100 * (data["bytes_processed"] / data["content_length"]));
          progress.text(
            "ファイルをアップロードしています・・・" + percentLoaded + "%"
          );
          progress_bar_per.style.width = percentLoaded + '%';

          if (!data["done"]) {
            setTimeout(f, 200);
          }
        } else {
          /*
          if (mes==''){mes='・';}
          else if (mes=='・'){mes='・・';}
          else if (mes=='・・'){mes='・・・';}
          else　if (mes=='・・・'){mes='';}
          */
          progress.text(
            "ファイルをアップロードしています。画面を閉じないでください。" //+ mes
          );
          /*
            setTimeout(f, 1000);
          */
        }
      });
    };
    setTimeout(f, 300);
});
});
</script>

EOL
;
}

function ksk3d_user_log($text){
  $userID = ksk3d_get_current_user_id();
  global $wpdb;
  $tbl = $wpdb->prefix .KSK3D_TABLE_GROUP_USER;
  $result = ksk3d_fn_db::sel("select exuser_id from {$tbl} where user_id={$userID} limit 1;");
  if (!empty($result)){
    $guser_id = $result[0]['exuser_id'];
    ksk3d_console_log("guser_id:".$guser_id);

    $log = KSK3D_CONT_LOG_PATH ."/ksk3d-user-".date("Ymd").".log";
    file_put_contents ($log ,$guser_id.",".$_SERVER['REMOTE_ADDR'].",".date("Y-m-d H:i:s").",".$text ."\n" ,FILE_APPEND);
    chmod ($log ,0777);
  }
}

function ksk3d_user_name($title, $item, $args, $depth){
  $auth = SwpmAuth::get_instance();
  if ($auth->is_logged_in()){
    if (preg_match('/メンバーログイン/',$title)==1){
      $title = 'こんちには '.$auth->get('user_name').' さん';
    }
  }
  return $title;
}

function ksk3d_userrootdir($file){
  $dir = substr($file ,mb_strlen(ksk3d_upload_dir())+1);
  $filter = '{^(.+?/.+?)/.+$}';
  if (preg_match($filter ,$dir)==1){
    $dir = preg_replace($filter,"$1",$dir);
    return ksk3d_upload_dir()."/".$dir;
  } else {
    return false;
  }
}

function ksk3d_dl_schema_all($file ,$schema_path){
  $file = ksk3d_file1($file);

  $xml = simplexml_load_file($file);
  if ($xml !== FALSE){
    $ns_used = $xml->getNamespaces(true);

    $schema_lc = [];
    if (!empty($xml->xpath('@xsi:schemaLocation'))){
      $schema_lc = array_chunk(preg_split("/\s+/" ,$xml->xpath('@xsi:schemaLocation')[0]) ,2);
    }
    if(!empty($xml->xpath('@xsi:noNamespaceSchemaLocation'))){
      array_push($schema_lc ,array_chunk(preg_split("/\s+/" ,$xml->xpath('@xsi:noNamespaceSchemaLocation')[0]) ,2));
    }
    $schema_lc_full = [];
    foreach ($schema_lc as $list){
      if (strpos($list[1], '://') == false) {
        $schema_lc_full[$list[0]] = $list[0] ."/" .$list[1];
      } else {
        $schema_lc_full[$list[0]] = $list[1];
      }
    }
    $schema_all = "";
    $schema_all =<<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

EOL
;
    foreach ($ns_used as $key=>$value){
      if (isset($schema_lc_full[$value])){
        $response = @file_get_contents($schema_lc_full[$value], NULL, NULL ,0 ,1);
        $count = 1;
        if ($response !== false) {  
            $import = "<xs:import namespace=\"{$value}\" schemaLocation=\"{$schema_lc_full[$value]}\"/>";
            $schema_all .= "  {$import}\n";
        }
      }
    }
    $schema_all .=<<<EOL
</xs:schema>
EOL
;
    file_put_contents($schema_path ,$schema_all);
    chmod($schema_path ,0777);

    return true;
  } else {
    return false;
  }
}

function ksk3d_dl_schema_all2($file ,$schema_path){
  $file = ksk3d_file1($file);

  $xml = simplexml_load_file($file);
  if ($xml !== FALSE){
    $schema_lc = [];
    if (!empty($xml->xpath('@xsi:schemaLocation'))){
      $schema_lc = array_chunk(preg_split("/\s+/" ,$xml->xpath('@xsi:schemaLocation')[0]) ,2);
    }
    if(!empty($xml->xpath('@xsi:noNamespaceSchemaLocation'))){
      array_push($schema_lc ,array_chunk(preg_split("/\s+/" ,$xml->xpath('@xsi:noNamespaceSchemaLocation')[0]) ,2));
    }
    $schema_lc_full = [];
    foreach ($schema_lc as $list){
      if (strpos($list[1], '://') == false) {
        $schema_lc_full[$list[0]] = $list[0] ."/" .$list[1];
      } else {
        $schema_lc_full[$list[0]] = $list[1];
      }
    }
    $schema_all = "";
    foreach ($schema_lc_full as $schema){
      $response = @file_get_contents($schema, NULL, NULL);
      if ($response !== false) {  
        if (! empty($schema_all)){
          $schema_all .= preg_replace("/<\?xml .*>\n/" ,'' ,$response);
        }
      }
    }
    
    file_put_contents($schema_path ,$schema_all);
    chmod($schema_path ,0777);
    
    return true;
  } else {
    return false;
  }
}

function ksk3d_xml_namespace($file){
  $file = ksk3d_file1($file);
  
  $xml = simplexml_load_file($file);
  if ($xml !== FALSE){
    $ns_defined = $xml->getDocNamespaces();
    $ns_used = $xml->getNamespaces(true);

    $schema_lc = [];
    if (!empty($xml->xpath('@xsi:schemaLocation'))){
      $schema_lc = array_chunk(preg_split("/\s+/" ,$xml->xpath('@xsi:schemaLocation')[0]) ,2);
    }
    if(!empty($xml->xpath('@xsi:noNamespaceSchemaLocation'))){
      array_push($schema_lc ,array_chunk(preg_split("/\s+/" ,$xml->xpath('@xsi:noNamespaceSchemaLocation')[0]) ,2));
    }
    $schema_lc_full = [];
    foreach ($schema_lc as $list){
      $schema_lc[$list[0]] = $list[1];
      if (strpos($list[1], '://') == false) {
        $schema_lc_full[$list[0]] = $list[0] ."/" .$list[1];
      } else {
        $schema_lc_full[$list[0]] = $list[1];
      }
    }
    $ns = [];
    $i = 0;
    foreach ($ns_defined as $key=>$value){
      $ns[$i]['prefix'] = $key; 
      $ns[$i]['namespace'] = $value;  
      $ns[$i]['namespace_used'] = isset($ns_used[$key]);  
      if (isset($schema_lc_full[$value])){
        $ns[$i]['schema'] = $schema_lc[$value];  
        $ns[$i]['schema_abs'] = $schema_lc_full[$value];  
        $response = @file_get_contents($schema_lc_full[$value], NULL, NULL, 0, 1);
        if ($response !== false) {  
          $ns[$i]['schema_response'] = true;
        } else {
          $ns[$i]['schema_response'] = false;
        }
      } else {
        $ns[$i]['schema'] = "";
        $ns[$i]['schema_abs'] = "";
        $ns[$i]['schema_response'] = "";
      }
      $i++;
    }
    return $ns;
  } else {
    return false;
  }
}

function ksk3d_zip_Compress($dir, $file, $root="", $flg_del=false){
  $zip = new ZipArchive();
  $res = $zip->open($file, ZipArchive::CREATE);

  if($res){
    if($root != "") {
      $zip->addEmptyDir($root);
    }

    $baseLen = mb_strlen($dir);
     
    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator(
        $dir,
        FilesystemIterator::SKIP_DOTS
        |FilesystemIterator::KEY_AS_PATHNAME
        |FilesystemIterator::CURRENT_AS_FILEINFO
      ), RecursiveIteratorIterator::SELF_FIRST
    );

    $list = array();
    foreach($iterator as $pathname => $info){
      $localpath = $root . mb_substr($pathname, $baseLen);
   
      if( $info->isFile() ){
        $zip->addFile($pathname, $localpath);
      } else {
        $res = $zip->addEmptyDir($localpath);
      }
    }
    $zip->close();
    
    if ($flg_del){ksk3d_delTree($dir);}

    return true;
  } else {
    return false;
  }
}

function ksk3d_zip_extractTo($zipfile ,$extractDir = ""){
  $zip = new ZipArchive;
  if ($extractDir==""){
    $extractDir = dirname($zipfile);
  }
  
  if ($zip->open($zipfile) === TRUE) {
    $count = $zip->numFiles;
    for ($i = 0; $i < $count; $i++) {
       $filename1 = $zip->getNameIndex($i);
        if (substr($filename1, -1, 1) != '/'){
          break;
        }
    }
    $zip->extractTo($extractDir);

    $file_info1 = pathinfo($filename1);
    $filename = $filename1; 
    $dirname = $file_info1['dirname'];  
    ksk3d_console_log("dirname:".$dirname);
    $basename = $filename1; 
    $basename1 = $file_info1['dirname']."/".$file_info1['filename']; 
    $extension = "";
    if (isset($file_info1['extension'])){
      $extension = $file_info1['extension'];
    }
    $filename1 = $extractDir ."/" .$filename1;
    ksk3d_console_log("basename1:".$basename1);


    ksk3d_console_log("count:".$count);
    if ($count > 1){
      for ($i = 0; $i < $count; $i++) {
        ksk3d_console_log("name:".$zip->getNameIndex($i));
        ksk3d_console_log("path:".$extractDir."/".$zip->getNameIndex($i));
        if (is_file($extractDir."/".$zip->getNameIndex($i))){
          ksk3d_console_log("isfile:true");
          $file_info = pathinfo($zip->getNameIndex($i));

          ksk3d_console_log("dirname:".$file_info['dirname']);
          if ($dirname != $file_info['dirname']) {
            $dirname = "*";
            $basename = "*";
            $i = $count;
          }
        }
      }
      
      for ($i = 0; $i < $count; $i++) {
        if (is_file($extractDir."/".$zip->getNameIndex($i))){
          $file_info = pathinfo($zip->getNameIndex($i));

          if (isset($file_info['extension'])){
            if ($extension != $file_info['extension']) {
              $extension = "*";
              $i = $count;
            }
          }
        }
      }
      $basename = '*.'.$extension;
      $basename1 = '*';
      if ($dirname != "."){
        $basename = $dirname ."/" .$basename;
        $basename1 = $dirname ."/" .$basename1;
      }
      $filename = $extractDir."/".$basename;
    }
    
    $zip->close();
    return array(
      true,
      'filename1'=>$filename1,
      'filename'=>$filename,
      'dirname'=>$dirname,
      'basename'=>$basename,
      'basename1'=>$basename1,  
      'extension'=>$extension,
      'extractDir'=>$extractDir,  
      'numFiles'=>$count
    );
  } else {
    return false;
  }
}

function ksk3d_zip_extractTo1($zipfile ,$extractDir="" ,$filter="."){
  if (preg_match('{\..+$}',$filter)==1){
    $filter = preg_replace('{\+}',".+",
      preg_replace('{\*}',".*",
        preg_replace('{(.*)\.([^.]+)$}',"$1\.($2|zip)",
          $filter
        )
      )
    );
  }
  ksk3d_console_log("ksk3d_zip_extractTo1($zipfile ,$extractDir ,$filter)");
  
  if (empty($extractDir)){
    $zipfile_pathinfo = pathinfo($zipfile);
    $extractDir = $zipfile_pathinfo['dirname'];
  }
  $basename1 = false;
  $zip = new ZipArchive;
  if ($zip->open($zipfile)===true){
    $count = $zip->numFiles;
    for ($i = 0; $i < $count; $i++) {
      $basename1 = $zip->getNameIndex($i);
      ksk3d_console_log("basename1($i):".$basename1);
      if (substr($basename1, -1, 1) != '/'){
      if (preg_match('{/codelists/}',$basename1)!=1){
      if (preg_match('{'.$filter.'}i',$basename1)==1){
        ksk3d_console_log("zip->extractTo($extractDir ,$basename1)");
        $zip->extractTo($extractDir ,$basename1);
        break;
      }}}
    }
    $zip->close();
  } else {
    ksk3d_console_log("zip->open:false");
  }
  
  $file = $extractDir."/".$basename1;
  ksk3d_console_log("file1:".$file);
  if (is_file($file)){
    $userrootdir = ksk3d_userrootdir($file);
    if ($userrootdir != false) {
      ksk3d_console_log("userrootdir:".$userrootdir);
      chmod($userrootdir ,0777);
    } else {
      chmod($file ,0777);
    }
    
    if (preg_match('/\.zip$/',$file)==1){
      $zip->open($file);
      $file_path = pathinfo($file);
      $extractDir = $file_path['dirname'];
      $basename1 = $zip->getNameIndex(0);
      $zip->extractTo($extractDir);
      $file = $extractDir."/".$basename1;
      $zip->close();
      ksk3d_console_log("file2:".$file);
    }
    
    return $file;
  } else {
    return false;
  }
}


function ksk3d_zip_fileinfo($zip_file, $flg_del=true) {
  ksk3d_console_log("ksk3d_zip_fileinfo:".$zip_file);
  $zip_pathinfo = pathinfo($zip_file);
  
  $zip = new ZipArchive;
  $format=['',''];
  $ext=['',''];
  $path=['',''];
  $name=['',''];
  $dirname=""; 
  $filename;  
  $basename;  
  if ($zip->open($zip_file) === TRUE) {
    $count = $zip->numFiles;
    
    $zip_file1 = ksk3d_zip_extractTo1($zip_file);
    while(preg_match('/\.zip$/' ,$zip_file1)==1){
      $zip_file1 = ksk3d_zip_extractTo1($zip_file1);
    }
    
    if (is_file($zip_file1)){
      $format_ = ksk3d_format($zip_file1);
      $format = array_merge($format ,array($format_['format']));
      unlink ($zip_file1);
    }

    for ($i = 0; $i < $count; $i++) {
      $basename1 = $zip->getNameIndex($i);
      if (substr($basename1, -1, 1) != '/'){
        ksk3d_console_log("is_file:true");
        if (preg_match('{/codelists/}',$basename1)!=1){
          ksk3d_console_log("basename1:".$basename1);
          $zip->extractTo($zip_pathinfo['dirname'], $basename1);
          $file1 = $zip_pathinfo['dirname']."/".$basename1;
          ksk3d_console_log("file1($i):".$file1);
          if (preg_match('/\.zip$/i',$basename1)==1){
            $file1 = ksk3d_zip_extractTo1($file1);
            $file_info = pathinfo(substr($file1, strlen($zip_pathinfo['dirname'])+1));
          } else {
            $file_info = pathinfo($basename1);
          }
          
          $format_ = ksk3d_format($file1);
          $format = array_merge($format ,array($format_['format']));
          $ext = array_merge($ext ,array($file_info['extension']));
          if (count($ext)>10){$ext = array_unique($ext);}
          if (empty($dirname)){
            $dirname = $file_info['dirname'];
          } else {
            $dirname2 = preg_replace('/\*/' ,'.*' ,$dirname);
            if (preg_match('{^'.$dirname2.'$}' ,$file_info['dirname'])==0) {
              $dirname = "*";
            }
          }
          
          if (!isset($filename)){
            $filename = $file_info['filename'];
            $filename_array = explode('_' ,$filename);
          } else {
            $filename2 = preg_replace('/\*/' ,'.*' ,$filename);
            if (preg_match('{'.$filename2.'}' ,$file_info['filename'])==0){
              $filename = "";
              $filename_array_ = explode('_' ,$file_info['filename']);
              for ($i=0; $i<count($filename_array); $i++){
                if (isset($filename_array_[$i])){
                  if ($filename_array[$i] != $filename_array_[$i]){$filename_array[$i]="*";}
                  if ($i>0){$filename .= "_";}
                  $filename .= $filename_array[$i];
                } else {
                  $filename .= "*";
                  break;
                }
              }
            }
          }
          ksk3d_console_log("filename:".$filename);
        }
      } else {
        ksk3d_console_log("is_file:false");
      }
    }
    ksk3d_console_log("filename:".$filename);
    if (is_array($format)){$format = array_unique($format);}
    if (is_array($ext)){$ext = array_unique($ext);}
    $format = ksk3d_array_del_val($format ,'');
    $ext = ksk3d_array_del_val($ext ,'');
  
    if (is_array($format)){
      if (count($format)>1){
        if (
          in_array('zip', $format)
        ){
          $format = ksk3d_array_del_val($format ,'zip');
          $ext = ksk3d_array_del_val($ext ,'zip');
        }

        if (
          in_array('CityGML', $format)
          and in_array('CityGML(iur)', $format)
        ){
          $format = ksk3d_array_del_val($format ,'CityGML(iur)');
        }

        if (
          in_array('gml', $format)
          and in_array('gml(基盤地図情報)', $format)
        ){
          $format = ksk3d_array_del_val($format ,'gml(基盤地図情報)');
        }

        if (
          (
            in_array('CityGML', $format)
            or in_array('CityGML(iur)', $format)
          )
          and in_array('tif', $format)
        ){
          $format = ksk3d_array_del_val($format ,'tif');
          $ext = ksk3d_array_del_val($ext ,'tif');
        }
      }
      $format = implode(',' ,$format);
    }

    if (is_array($ext)){
      if (count($ext)>1){
        $ext = ksk3d_array_del_val($ext ,'不明');
      }
      if (is_array($ext)){
        if (count($ext)>1){
          $ext = "*";
        } else {
          $ext = implode(',' ,$ext);
        }
      } else {
        $ext = implode(',' ,$ext);
      }
    }

    $basename = $filename;
    if (!empty($ext)){$basename .= ".".$ext;}

    $zipfile_info = pathinfo($zip_file);
    if ($dirname == '.'){
      $dirname = "";
      $extract_filepath = $zipfile_info['dirname'];
    } else {
      $extract_filepath = $zipfile_info['dirname']."/".$dirname;
    }

    $zip->close();
    if ($flg_del){
      ksk3d_delTree($zip_pathinfo['dirname']."/".$zip_pathinfo['filename']);
    }
    
    return array(
      true,
      'dirname'=>$dirname,  
      'filename'=>$filename,  
      'basename'=>$basename,  
      'extension'=>$ext,  
      'format'=>$format,  
      'extract_filepath'=>$extract_filepath,  
      'numFiles'=>$count  
    );
  } else {
    return false;
  }
}

