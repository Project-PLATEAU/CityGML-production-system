<?php
//db設定値
require_once('../../../wp-config.php');

// Connect to db
function connect() {
  $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USER,DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false));
  return $dbh;
}

//テーブル名
define( 'TBL_DATA' , 'wp_ksk3d_data' );
define( 'TBL_MAP' , 'wp_ksk3d_map' );
define( 'TBL_LAYER' , 'wp_ksk3d_map_layer' );

define( 'TBL_GEOM' , 'wp_ksk3d_geometry_' );
define( 'TBL_ATTRIB' , 'wp_ksk3d_attribute_' );

