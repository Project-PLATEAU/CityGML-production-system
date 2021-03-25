<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$fileID = $_POST['fileID'];
$meshcode = $_POST['meshcode'];

// データベース接続
require_once('./DBConnect.php');
try {
 $dbh = connect();
} catch (PDOException $e) {
 var_dump($e->getMessage());
 exit;
}

$tbl1 = TBL_GEOM ."{$userID}_{$fileID}";

// データ取得
$sql = "SELECT id,ST_AsText(the_geom) as the_geom,ST_AsText(hole) as hole,z,m FROM {$tbl1} WHERE meshcode like '{$meshcode}%'";
//$sql = "SELECT id,ST_AsText(the_geom) as the_geom FROM {$tbl1} WHERE meshcode = '{$meshcode}'";
$stmt = ($dbh->prepare($sql));
$stmt->execute();

//あらかじめ配列を生成しておき、while文で回します。
$bList = "[";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  $bList .= 
    '{"id":' .$row['id']
    .',"the_geom":"' .$row['the_geom']
    .'","hole":"' .$row['hole']
    .'","z":"' .$row['z']
    .'","m":"' .$row['m']
    .'"},';
}

$bList = mb_substr($bList ,0 ,-1);
$bList .= "]";

//jsonとして出力
header('Content-type: application/json');
echo json_encode($bList,JSON_UNESCAPED_UNICODE);

$dbh = null;