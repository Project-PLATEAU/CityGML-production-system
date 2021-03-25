<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$mapID = $_POST['mapID'];
$index = $_POST['index'];

// データベース接続
require_once('./DBConnect.php');
try {
  $dbh = connect();
} catch (PDOException $e) {
  var_dump($e->getMessage());
  exit;
}

$tbl1 = TBL_LAYER;

//レイヤ削除
$sql =<<< EOL
DELETE FROM {$tbl1}
WHERE user_id={$userID} AND map_id={$mapID} AND layer_id={$index}
;
EOL
;
$stmt = $dbh->prepare($sql);
$stmt->execute();

//順番詰める
$sql =<<< EOL
UPDATE {$tbl1}
SET layer_id=layer_id-1
WHERE user_id={$userID} AND map_id={$mapID} AND layer_id>{$index}
;
EOL
;
//echo $sql;
$stmt = $dbh->prepare($sql);
$stmt->execute();

//jsonとして出力
header('Content-type: application/json');
echo json_encode("",JSON_UNESCAPED_UNICODE);

$dbh = null;
