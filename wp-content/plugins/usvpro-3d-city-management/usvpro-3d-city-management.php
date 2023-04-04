<?php
/*
Plugin Name: Usvpo 3D-City Management
Plugin URI: https://kashika.or.jp/
Description: 株式会社Tコンサルタント作成
Author: Yuichi Tanaka
Version: 1.0
Author URI: https://kashika.or.jp/
*/


// Plugin Basename
define( 'KSK3D_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

// Plugin Path
define( 'KSK3D_PATH', dirname( __FILE__ ) );

// Plugin URL
define( 'KSK3D_URL', plugins_url( '', KSK3D_PLUGIN_BASENAME ) );

// Plugin Storage URL
//define( 'KSK3D_STORAGE_URL', plugins_url('storage') );

// Plugin Contents Path
define( 'KSK3D_CONTENTS_PATH', WP_CONTENT_DIR.'/3d-city-management');

// Plugin Contents URL
define( 'KSK3D_CONTENTS_URL', content_url('3d-city-management'));

// Include Path
define( 'KSK3D_INCLUDE_PATH', KSK3D_PATH .'/inc');

// Class Path
define( 'KSK3D_CLASS_PATH', KSK3D_PATH .'/class');

// CSS URL
define( 'KSK3D_CSS_URL', KSK3D_URL .'/style');

//定数
include_once ('inc/constants.php');
include_once ('inc/geometryPrimitives.php');
include_once ('inc/sample_style.php');

//関数
include_once ('inc/functions.php');
include_once ('inc/functions_db.php');
include_once ('inc/functions_mesh.php');

include_once ('inc/dataset_citygml.php');
include_once ('inc/dataset_file.php');
include_once ('inc/dataset_gml.php');
include_once ('inc/dataset_internal.php');

include_once ('inc/functions_citygml.php');
include_once ('inc/functions_csv.php');
include_once ('inc/functions_fgd.php');
include_once ('inc/functions_gml.php');
include_once ('inc/functions_conv.php');
include_once ('inc/functions_internal.php');
include_once ('inc/functions_logic.php');
include_once ('inc/functions_logic_ck.php');
include_once ('inc/functions_pjt.php');
include_once ('inc/functions_visually.php');
include_once ('inc/functions_zip.php');

include_once ('inc/proc.php');

//ショートコード
include_once ('inc/shortcode.php');


if(function_exists('register_activation_hook')) {
  register_activation_hook (__FILE__, 'ksk3d_plugin_start');
}
if(function_exists('register_deactivation_hook')) {
  register_deactivation_hook (__FILE__, 'ksk3d_plugin_stop');
}
if(function_exists('register_uninstall_hook')) {
  register_uninstall_hook (__FILE__, 'ksk3d_plugin_end');
}
 
function ksk3d_plugin_start(){
  ksk3d_log ("ksk3d_plugin_start");

  $dr = array(
    KSK3D_CONTENTS_PATH,
    KSK3D_CONT_LOG_PATH,
    KSK3D_CONT_BACKUP_PATH,
    KSK3D_CONT_USERS_PATH,
    KSK3D_CONT_GUEST_PATH
  );

  $log = [];
  foreach ($dr as $d){
    if(! is_dir($d)){
      mkdir($d);
      chmod ($d ,0777);
      array_push($log ,$d);
    }
  }
  if (count($log)>0){ksk3d_log ("mkdir:".implode("\n mkdir:",$log));}

  include_once ('inc/ksk3d_plugin_start/create_table.php');
  include_once ('inc/ksk3d_plugin_start/create_fn.php');
  include_once ('inc/ksk3d_plugin_start/insert_table.php');

}
function ksk3d_plugin_stop(){
}
function ksk3d_plugin_end(){
}

function ksk3d_user_register( $user_id ){
  ksk3d_log ("user_register:".$user_id);
  add_user_meta ($user_id ,"ksk3d_user_folder" ,$user_id."-".ksk3d_random_word());
  $dr = KSK3D_CONT_USERS_PATH."/".get_user_meta( $user_id, "ksk3d_user_folder", true );
  mkdir($dr);
  chmod($dr, 0777);
}

add_action('user_register', 'ksk3d_user_register');
add_filter('nav_menu_item_title','ksk3d_user_name',10,4);

