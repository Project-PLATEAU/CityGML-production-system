<?php
$userID = $_POST['userID'];
if (!($userID>0)) {header( "location: ../../../");}
$format = $_POST['format'];

// �f�[�^�x�[�X�ڑ�
require_once('./DBConnect.php');
try {
  $dbh = connect();
} catch (PDOException $e) {
  var_dump($e->getMessage());
  exit;
}

$tbl = TBL_DATA;

// �f�[�^�擾
$sql = "SELECT id,file_id,display_name,file_format,file_path,file_name,meshsize,camera_position
 from {$tbl}
 where user_id={$userID} and file_format in ({$format})
 order by -file_id
 ;";
ksk3d_log($sql);
$stmt = ($dbh->prepare($sql));
$stmt->execute();

//���炩���ߔz��𐶐����Ă����Awhile���ŉ񂵂܂��B
$bList = "[";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 $bList .= 
    '{"id":' .$row['id']
    .',"file_id":' .$row['file_id']
    .',"display_name":"' .$row['display_name']
    .'","file_format":"' .$row['file_format']
    .'","file_path":"' .$row['file_path']
    .'","file_name":"' .$row['file_name']
    .'","meshsize":"' .$row['meshsize']
    .'","camera_position":"' .$row['camera_position']
    .'"},';
}

$bList = mb_substr($bList ,0 ,-1);
if ($bList<>"") {$bList .= "]";}

//json�Ƃ��ďo��
header('Content-type: application/json');
echo json_encode($bList,JSON_UNESCAPED_UNICODE);

$dbh = null;