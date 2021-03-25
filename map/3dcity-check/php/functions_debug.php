<?php
/*
function get_option($option){
  $tbl = TBL_OPTIONS;
  $sql = "SELECT option_value from {$tbl} where option_name='{$option}';";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result;
}
*/
function ksk3d_console_log($text){
  //ログアウトなどでエラーになるのでコメントアウト
  echo '<script>console.log('. json_encode( $text ) .')</script>';
}
