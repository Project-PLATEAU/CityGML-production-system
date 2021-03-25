<?php
function meshcode2box($code){
  ksk3d_log("fn:meshcode2box:({$code})");
  $Lat1 = substr($code ,0 ,2) /1.5;
  $Lon1 = substr($code ,2 ,2) +100;
  if (preg_match('/^[0-9]{6,}/' ,$code)) {
		$Lat1 += substr($code ,4 ,1) /12;
		$Lon1 += substr($code ,5 ,1) /8;
    if (preg_match('/^[0-9]{8,}/' ,$code)){
      $Lat1 += substr($code ,6 ,1) /120;
      $Lon1 += substr($code ,7 ,1) /80;
      if (preg_match('/^[0-9]{9,}/' ,$code)){
        $Lat1 += intval(substr($code ,8 ,1) /2.5) /240;
        $Lon1 += ((substr($code ,8 ,1) +1) %2) /160;
        if (preg_match('/^[0-9]{10,}/' ,$code)){
          $Lat1 += intval(substr($code ,9 ,1) /2.5) /480;
          $Lon1 += ((substr($code ,9 ,1) +1) %2) /320;
          $Lat2 = $Lat1 +1/480;
          $Lon2 = $Lon1 +1/320;
        } else {
          $Lat2 = $Lat1 +1/240;
          $Lon2 = $Lon1 +1/160;
        }
      } else {
        $Lat2 = $Lat1 +1/120;
        $Lon2 = $Lon1 +1/80;
      }
    } else {
      $Lat2 = $Lat1 +1/12;
      $Lon2 = $Lon1 +1/8;
    }
  } else {
    $Lat2 = $Lat1 +1/1.5;
    $Lon2 = $Lon1 +1;
  }
  return array ($Lat1,$Lon1,$Lat2,$Lon2);
}
