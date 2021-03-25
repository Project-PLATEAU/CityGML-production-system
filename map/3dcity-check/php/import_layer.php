<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$mapID = $_POST['mapID'];

// データベース接続
require_once('./DBConnect.php');
try {
  $dbh = connect();
} catch (PDOException $e) {
  var_dump($e->getMessage());
  exit;
}

$tbl1 = "wp_ksk3d_map_layer";//TBL_LAYER;
$tbl2 = "wp_ksk3d_data";// TBL_DATA;

//データセットを削除されているレイヤーは削除
$sql = "DELETE A
  FROM `{$tbl1}` A
  LEFT JOIN {$tbl2} B
  ON A.`user_id`=B.`user_id` AND A.`file_id`=B.`file_id`
  WHERE A.`user_id`={$userID} AND A.`map_id`={$mapID} AND B.`file_id` is NULL
;";
//ksk3d_log($sql);
$stmt = ($dbh->prepare($sql));
$stmt->execute();

//レイヤーを詰める
$sql = "UPDATE `{$tbl1}` A
INNER JOIN
(SELECT @i:=@i+1 as ROWNUM,id
  FROM (SELECT @i:=0) AS INDEX_NUM ,`{$tbl1}`
  WHERE `user_id`={$userID} AND `map_id`={$mapID}
  order by `layer_id`) B
ON A.id=B.id
SET `layer_id`=ROWNUM
  WHERE `user_id`={$userID} AND `map_id`={$mapID}
;";
$stmt = ($dbh->prepare($sql));
$stmt->execute();

// データ取得
$sql = "SELECT A.id ,A.layer_id ,A.display_name ,A.file_id ,A.color_exp ,A.meshsize,A.camera_position
  ,B.file_format ,B.file_path ,B.file_name
  FROM `{$tbl1}` A
  LEFT JOIN {$tbl2} B
  ON A.`user_id`=B.`user_id` AND A.`file_id`=B.`file_id`
  WHERE A.`user_id`={$userID} AND A.`map_id`={$mapID}
  order by A.`layer_id`
;";
//echo $sql;
$stmt = ($dbh->prepare($sql));
$stmt->execute();

//あらかじめ配列を生成しておき、while文で回します。
$bList = "[";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 $bList .= 
    '{"id":' .$row['id']
    .',"layer_id":' .$row['layer_id']
    .',"display_name":"' .$row['display_name']
    .'","file_id":' .$row['file_id']
    .',"file_format":"' .$row['file_format']
    .'","file_path":"' .$row['file_path']
    .'","file_name":"' .$row['file_name']
    .'","color_exp":"' .$row['color_exp']
    .'","meshsize":"' .$row['meshsize']
    .'","camera_position":"' .$row['camera_position']
    .'"},';
}

$bList = mb_substr($bList ,0 ,-1);
if ($bList<>"") {$bList .= "]";}

//jsonとして出力
header('Content-type: application/json');
echo json_encode($bList,JSON_UNESCAPED_UNICODE);

$dbh = null;