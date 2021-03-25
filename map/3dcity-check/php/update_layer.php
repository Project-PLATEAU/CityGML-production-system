<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$mapID = $_POST['mapID'];
$layer_id = $_POST['layer_id']+1;
$color = $_POST['color'];

// データベース接続
require_once('./DBConnect.php');
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
WHERE user_id={$userID} and map_id={$mapID} and layer_id={$layer_id}
;
EOL
;
$stmt = $dbh->prepare($sql);
$stmt->execute();
//jsonとして出力
header('Content-type: application/json');
echo json_encode("",JSON_UNESCAPED_UNICODE);

$dbh = null;
