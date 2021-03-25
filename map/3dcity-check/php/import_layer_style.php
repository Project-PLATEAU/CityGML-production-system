<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$mapID = $_POST['mapID'];
$layerID = $_POST['layerID'];

// データベース接続
require_once('./DBConnect.php');
try {
  $dbh = connect();
} catch (PDOException $e) {
  var_dump($e->getMessage());
  exit;
}

$tbl1 = "wp_ksk3d_map_layer";//TBL_LAYER;

// データ取得
$sql = "SELECT A.id,A.color_exp
  FROM `{$tbl1}` A
  WHERE A.`user_id`={$userID} AND A.`map_id`={$mapID} AND A.`layer_id`={$layerID}
;";
//echo $sql;
$stmt = ($dbh->prepare($sql));
$stmt->execute();

//あらかじめ配列を生成しておき、while文で回します。
//$bList = "[";
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bList =
  '[{"id":' .$row['id']
  .',"color_exp":"' .$row['color_exp']
  .'"}]';

//$bList = mb_substr($bList ,0 ,-1);
//if ($bList<>"") {$bList .= "]";}

//jsonとして出力
header('Content-type: application/json');
//echo json_encode($sql,JSON_UNESCAPED_UNICODE);
echo json_encode($bList,JSON_UNESCAPED_UNICODE);

$dbh = null;