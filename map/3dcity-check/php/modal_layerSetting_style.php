<?php
//require_once('functions_debug.php');
$userID = $_GET['userID'];
//if (!($userID>0)) {exit;}
$mapID = $_GET['mapID'];
$layerID = $_GET['layerID'];
$file_format = $_GET['file_format'];

/*
echo "userID:".$userID."<br>";
echo "mapID:".$mapID."<br>";
echo "layerID:".$layerID."<br>";
echo "file_format:".$file_format."<br>";
*/

function loadTilesetJson($userID ,$mapID ,$layerID) {
  require_once('DBConnect.php');
  try {
    $dbh = connect();
  } catch (PDOException $e) {
    var_dump($e->getMessage());
    exit;
  }
  $tbl1 = "wp_ksk3d_map_layer";//TBL_LAYER;
  $tbl2 = "wp_ksk3d_data";// TBL_DATA;

  // データ取得
  $sql = "SELECT A.id ,A.layer_id ,A.display_name ,A.file_id ,A.color_exp ,A.meshsize,A.camera_position
    ,B.file_format ,B.file_path ,B.file_name
    FROM `{$tbl1}` A
    LEFT JOIN {$tbl2} B
    ON A.`user_id`=B.`user_id` AND A.`file_id`=B.`file_id`
    WHERE A.`user_id`={$userID} AND A.`map_id`={$mapID} AND A.`layer_id`={$layerID}
  ;";
  //echo $sql;
  $stmt = ($dbh->prepare($sql));
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $path = $row['file_path']."/".$row['file_name'];
  $json = file_get_contents($path);
  $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $arr = json_decode($json,true);
  //ksk3d_console_log($arr);
  //ksk3d_console_log($arr['properties']);
  return $arr['properties'];
}

function saveStyle($userID ,$mapID ,$layerID ,$color){
  require_once('DBConnect.php');
  try {
    $dbh = connect();
  } catch (PDOException $e) {
    var_dump($e->getMessage());
    exit;
  }
  $tbl1 = TBL_LAYER;
  $color2 = preg_replace("/'/" ,"\'" ,$color);

  // データ挿入
  //try{
  $sql =<<< EOL
UPDATE {$tbl1}
SET color_exp='{$color2}'
WHERE user_id={$userID} and map_id={$mapID} and layer_id={$layerID}
;
EOL
;
  $stmt = $dbh->prepare($sql);
  $stmt->execute();  
}

//フォーマット設定
$ksk3d_foramt = [
  '/czml|内部データセット/i',
  '/3dtiles/i'
];

//スタイルの種類
$style_type = 1;
if(isset($_POST["style_type"])) {
  $style_type = $_POST["style_type"];
  if (preg_match('/^(2|3)$/' ,$style_type)){
    $attrib_array = loadTilesetJson($userID ,$mapID ,$layerID);

  }
}

//属性項目の選択
$style_attrib = 0;
if(isset($_POST["style_attrib"])) {
  $style_attrib = $_POST["style_attrib"];
}
/*      
foreach($attrib_array as $key =>$v){
  foreach($v as $key2 =>$v2){
    echo $key.",".$key2 ."," .$v2."<br>";
  }
}
*/
/*
echo $style_attrib."<br>";
echo $attrib_array[$style_attrib]['minimum']."<br>";
echo $attrib_array["{$style_attrib}"]['minimum']."<br>";
*/

//属性項目の数
$style_split = -1;
$ea_style_split = 'value="5"';
if(isset($_POST["style_split"])) {
  $style_split = $_POST["style_split"];
  if ($style_split<2){$style_split=2;}
  $ea_style_split = 'value="'.$style_split.'"';
  for($i=0; $i<$style_split; $i++){
    if(isset($_POST["fill_color2"][$i])) {
      $ea_fill_color2[$i] = 'value="'.$_POST["fill_color2"][$i].'"';
      $ea_fill_tra2[$i] = 'value="'.$_POST["fill_tra2"][$i].'"';
      $ea_condition2[$i] = 'value="'.$_POST["condition2"][$i].'"';
    } else {
      //echo 'value="'.substr("0".dechex(64+191/($style_split+1)*$i),-2).'FFFF"'."<br>";
      $ea_fill_color2[$i] = 'value="#'.substr("0".dechex(64+191/($style_split+1)*$i),-2).'0000"';//#FF0000
      $ea_fill_tra2[$i] = 'value="1"';
      /*
      echo $attrib_array[$style_attrib]['maximum']."<br>";
      echo $attrib_array[$style_attrib]['minimum']."<br>";
      echo $style_split."<br>";
      echo $style_split."<br>";
      */
      $ea_condition2[$i] = 'value="'.strval(($attrib_array[$style_attrib]['maximum']-$attrib_array[$style_attrib]['minimum'])/$style_split*($style_split-$i-1)+$attrib_array[$style_attrib]['minimum']).'"';
    }
  }
} else {
  //echo 'value="'.substr("0".dechex(64+191/($style_split+1)*$i),-2).'FFFF"'."<br>";
  $fill_color2[0] = '';//#FF0000
  $fill_tra2[0] = 'value="1"';
  $condition2[0] = 'value="0"';
}

//スタイルのDB保存
//$_POST["submit_style_save(1)"]=true;
if(isset($_POST["submit_style_save(1)"])) {
  $ls_fill_color = $_POST["fill_color"];
  $ls_fill_tra = $_POST["fill_tra"];
  if (isset($_POST["outline_color"])){
    $ls_outline_color = $_POST["outline_color"];
    $ls_outline_tra = $_POST["outline_tra"];
    $ls_outline_width = $_POST["outline_width"];
    //echo "test2<br>";
  } else {
    $ls_outline_width = 0;
  }
  
  //ls_fill
  $rgb = sscanf($ls_fill_color ,"#%02x%02x%02x");
  //foreach($rgb as $key =>$v){
  //  echo $key ."," .$v;
  //}
  $elem = $ls_fill_tra;

  //var ls_fill_color;
  if (preg_match($ksk3d_foramt[0], $file_format)){
    //echo "ls_fill_color:" .$rgb[0]/255 .",".$rgb[1]/255 .",".$rgb[2]/255 ."," .$ls_fill_tra."<br>";
    $db_fill_color = $rgb[0]/255 .",".$rgb[1]/255 .",".$rgb[2]/255 ."," .$ls_fill_tra;
    //$db_fill_color = new Cesium.Color($rgb[0]/255 ,$rgb[1]/255 ,$rgb[2]/255 , $ls_fill_tra);
  } else if (preg_match($ksk3d_foramt[1], $file_format)) {
    //echo "ls_fill_color:".'rgba('.$rgb[0].', '.$rgb[1].', '.$rgb[2].', ' .$ls_fill_tra.')'."<br>";
    $db_fill_color = 'rgba('.$rgb[0].', '.$rgb[1].', '.$rgb[2].', ' .$ls_fill_tra.')';
  }
  //ksk3d_console_log($db_fill_color);

  //ls_outline
  //var ls_outline;
  //var ls_outline_width;
  //var ls_outline_color;
  //echo "ls_outline_width:".$ls_outline_width."<br>";
  if ($ls_outline_width >0) {
    $ls_outline = true;
    $rgb = sscanf($ls_outline_color ,"#%02x%02x%02x");
    //$elem = $ls_outline_tra;
    if (preg_match($ksk3d_foramt[0], $file_format)){
      //echo "ls_outline_color:".$rgb[0]/255 .",".$rgb[1]/255 .",".$rgb[2]/255 ."," .$ls_outline_tra."<br>";
      $db_outline_color = $rgb[0]/255 .",".$rgb[1]/255 .",".$rgb[2]/255 ."," .$ls_outline_tra;
      //echo "test3<br>";
      //$db_outline_color = new Cesium.Color($rgb[0]/255 ,$rgb[1]/255 ,$rgb[2]/255 , $ls_outline_tra);
    } else if (preg_match($ksk3d_foramt[1], $file_format)) {
      //echo "ls_outline_color:".'rgba('.$rgb[0].', '.$rgb[1].', '.$rgb[2].', ' .$ls_outline_tra.')'."<br>";
      $db_outline_color = 'rgba('.$rgb[0].', '.$rgb[1].', '.$rgb[2].', ' .$ls_outline_tra.')';
    }
  } else {
    $ls_outline = false;
    $db_outline_color = "";
  }

  //var v;
  $v = "";
  if (preg_match($ksk3d_foramt[0], $file_format)==1){
    $v = "{'material':'" .$db_fill_color;
    if ($ls_outline){
      $v .= "','outline':'" .$ls_outline ."','outlineWidth':'" .$ls_outline_width ."','outlineColor':'" .$db_outline_color;
    }
    $v .= "'}";
    //echo "<br>".$v."<br>";
  } else if (preg_match($ksk3d_foramt[1], $file_format)==1) {
    $v = "{'color':'".$db_fill_color."'}";
  }
  saveStyle($userID ,$mapID ,$layerID ,$v);

} else if(isset($_POST["submit_style_save(2)"])) {

} else if(isset($_POST["submit_style_save(3)"])) {
  if ($style_split>-1){
    $v = "{'color':{'conditions':[";
    $v .= "['" .'${' .$style_attrib. "} === null','rgba(255,255,255,".$_POST["fill_tra2"][0].")'],";
    for($i=0; $i<$style_split; $i++){
      $rgb = sscanf($_POST["fill_color2"][$i] ,"#%02x%02x%02x");
      $fill_tra2 = $_POST["fill_tra2"][$i];
      $condition2 = $_POST["condition2"][$i];
      if ($i>0){$v .= ",";}
      $v .= "['" .'${' .$style_attrib. "} >= " .$condition2 ."','rgba(".$rgb[0].",".$rgb[1].",".$rgb[2].",".$fill_tra2.")']";
    }
    $v .= ']}}';
    //echo $v."<br>";
    saveStyle($userID ,$mapID ,$layerID ,$v);
  }
}

//echo "test33";

//要素初期値
if(isset($_POST["fill_color"])) {
  $ea_fill_color = 'value="'.$_POST["fill_color"].'"';
  $ea_fill_tra = 'value="'.$_POST["fill_tra"].'"';
} else {
  $ea_fill_color = '';
  $ea_fill_tra = 'value="1"';
}
if(isset($_POST["outline_color"])) {
  $ea_outline_color = 'value="'.$_POST["outline_color"].'"';
  $ea_outline_tra = 'value="'.$_POST["outline_tra"].'"';
  $ea_outline_width = 'value="'.$_POST["outline_width"].'"';
} else {
  $ea_outline_color = '';
  $ea_outline_tra = 'value="1"';
  $ea_outline_width = 'value="0"';
}
$ea_submit_style = "";

/*
echo "ls_fill_color:".$ls_fill_color."<br>";
echo "ls_fill_tra:".$ls_fill_tra."<br>";
echo "ls_outline_color:".$ls_outline_color."<br>";
echo "ls_outline_tra:".$ls_outline_tra."<br>";
echo "ls_outline_width:".$ls_outline_width."<br>";
*/

//フォーマットによる要素非表示の設定
if (preg_match($ksk3d_foramt[0], $file_format)==1){
  $ea_style_type = " disabled";
} else if (preg_match($ksk3d_foramt[1], $file_format)==1){
  $ea_style_type = "";
  $ea_outline_color = " value=\"#CCCCCC\" disabled";
  $ea_outline_tra = " disabled";
  $ea_outline_width = " disabled";
} else {
  $ea_style_type = " disabled";
  $ea_fill_color = " value=\"#CCCCCC\" disabled";
  $ea_fill_tra = " disabled";
  $ea_outline_color = " value=\"#CCCCCC\" disabled";
  $ea_outline_tra = " disabled";
  $ea_outline_width = " disabled";
}

//スタイルの種類による設定
if ($style_type==1){
  $title = "単一のスタイル";
} else if ($style_type==2){
  $title = "プロパティ値によるスタイル";
  //属性一覧がない場合、取得
  
} else if ($style_type==3){
  $title = "プロパティ範囲によるスタイル";
}

//スタイルの種類
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?php echo $title?></title>
</head>
<body>
  <form method="post" action="">
    <p>
      スタイルの種類
      <select name="style_type" <?php echo $ea_style_type?>>
        <option value="1" <?php echo $style_type==1 ? "selected" :""; ?>>単一のスタイル</option>
        <!--<option value="2" <?php echo $style_type==2 ? "selected" :""; ?>>プロパティ値によるスタイル</option>-->
        <option value="3" <?php echo $style_type==3 ? "selected" :""; ?>>プロパティ範囲によるスタイル</option>
      </select>
      <input type="submit" name="submit_style_type" value="設定" <?php echo $ea_style_type?>/>
    </p>
  <hr>
<?php

//属性選択
if (preg_match('/^(2|3)$/' ,$style_type)){
?>
  属性項目の選択
  <select name="style_attrib">
<?php
  $i = 0;
  foreach ($attrib_array as $key => $attrib) {
    $selected = $style_attrib==$key ? "selected" :"";
    echo <<<EOL
      <option value="{$key}" {$selected}>{$key}</option>
EOL
;
    $i++;
  }
  if (count($attrib_array)==0){
    $ea_style_split = " disabled";
    $ea_submit_style_attrib = " disabled";
    $ea_submit_style = " disabled";
  } else {
    $ea_submit_style_attrib = "";
  }
  
  echo "    </select>";
  if (preg_match('/^3$/' ,$style_type)==1){
    echo <<<EOL
    <br>
    区分の数<input type="number" name="style_split" min="2" max="20" {$ea_style_split}/><br>
EOL
;
  }

?>
  <input type="submit" name="submit_style_attrib" value="設定" <?php echo $ea_submit_style_attrib?>/>
  <hr>
  <input type="hidden" name="fill_color" <?php echo $ea_fill_color?>>
  <input type="hidden" name="fill_tra" min="0" max="1" step="0.01" <?php echo $ea_fill_tra?>>
  <input type="hidden" name="outline_color" <?php echo $ea_outline_color?>>
  <input type="hidden" name="outline_tra" min="0" max="1" step="0.01" <?php echo $ea_outline_tra?>>
  <input type="hidden" name="outline_width" min="0" max="1" step="1" <?php echo $ea_outline_width?>>
<?php
}

//プロパティ値によるスタイル
if ($style_type==2){
?>
    <p>
      エリア&emsp;|
      &emsp;色<input name="fill_color" type="color" <?php echo $ea_fill_color2[$i]?>>
      &emsp;透過度<input name="fill_tra" type="number" min="0" max="1" step="0.01" <?php echo $ea_fill_tra2[$i]?>>
    </p>

<?php

//プロパティ範囲によるスタイル
} else if ($style_type==3){
  //echo "    <table>";
  if ($style_split>-1){
    for($i=0; $i<$style_split; $i++){
?>
    <p>
      &emsp;<input name="fill_color2[<?php echo $i?>]" type="color" <?php echo $ea_fill_color2[$i]?>>
      &emsp;透過度<input name="fill_tra2[<?php echo $i?>]" type="number" min="0" max="1" step="0.01" <?php echo $ea_fill_tra2[$i]?>>
      &emsp;属性値>=<input name="condition2[<?php echo $i?>]" type="number" step="0.01" <?php echo $ea_condition2[$i]?>>
    </p>
<?php
    }
  }
  //echo "    </table>";
  
//単一のスタイル
} else {
?>
    <p>
      エリア&emsp;|
      &emsp;色<input name="fill_color" type="color" <?php echo $ea_fill_color?>>
      &emsp;透過度<input name="fill_tra" type="number" min="0" max="1" step="0.01" <?php echo $ea_fill_tra?>>
    </p>
    <p>
      ライン&emsp;|
      &emsp;色<input name="outline_color" type="color" <?php echo $ea_outline_color?>>
      &emsp;透過度<input name="outline_tra" type="number" min="0" max="1" step="0.01" <?php echo $ea_outline_tra?>>
      &emsp;幅<input name="outline_width" type="number" min="0" max="1" step="1" <?php echo $ea_outline_width?>>
    </p>
<?php
}
?>
    <p align="left" style="margin-right:20px">
      <input type="submit" style="font-size:14px;" name="submit_style_save(<?php echo $style_type?>)" value="スタイルを適用" <?php echo $ea_submit_style?>>
    </p>
  </form>
</body>
</html>