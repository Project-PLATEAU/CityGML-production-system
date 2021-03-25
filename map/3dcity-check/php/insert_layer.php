<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$mapID = $_POST['mapID'];
$index = $_POST['index'];
$dataset = $_POST['dataset'];

// データベース接続
require_once('./DBConnect.php');
try {
  $dbh = connect();
} catch (PDOException $e) {
  var_dump($e->getMessage());
  exit;
}

$tbl1 = TBL_LAYER;
$tbl2 = TBL_DATA;


// データ挿入
//try{
  $sql =<<< EOL
INSERT INTO {$tbl1}
(user_id ,map_id ,layer_id ,file_id ,display_name ,meshsize ,camera_position)
VALUES ({$userID},{$mapID},{$index},{$dataset['file_id']},'{$dataset['display_name']}',{$dataset['meshsize']},'{$dataset['camera_position']}');
EOL
;
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

//jsonとして出力
header('Content-type: application/json');
echo json_encode("",JSON_UNESCAPED_UNICODE);

$dbh = null;
