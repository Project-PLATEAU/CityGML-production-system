<?php
//システム設定
date_default_timezone_set('Asia/Tokyo');

//DEBUG
define( 'KSK3D_DEBUG', true );

//icon position
define( 'KSK3D_POSITION', '66.01' );

//アカウントロック、失敗回数
define( 'KSK3D_ACCOUNT_LOCK_LOGIN_FAILURE_TIMES', 5 );//5回失敗でアカウントロック

//利用者数制限
define( 'KSK3D_ACCESS_LIMIT', 5 );//5人

//利用者アクセス時間
define( 'KSK3D_ACCESS_TIME', '00:15:00' );//15分

//利用者超過時メッセージ
define( 'KSK3D_ACCESS_ERR_MES', 'ただいま混雑しているため、時間を空けてアクセスをお願いします。' );//15分

//ファイルサイズ容量（無料利用）
define( 'KSK3D_FILESIZE_LIMIT', 5*1024**3 );//5GB、50GB（kashika7）

//ファイルアップロードサイズ
define( 'KSK3D_MAX_FILE_SIZE', 1*1024**3 );//1GB

//ファイルサイズ容量超過時のメッセージ
define( 'KSK3D_USEDSIZE_ERR_MES', '<font color="red">使用容量が制限値を超えています。不要なデータの削除をお願いします。</font><br>');

//バックアップにアクセスするキー
define( 'KSK3D_BACKUP_KEY', 'KEY=xxxxxxxxxxxxxxxxxx' );

//LOGを格納するフォルダのパス、URL
define( 'KSK3D_CONT_LOG_PATH', KSK3D_CONTENTS_PATH .'/log' );
define( 'KSK3D_CONT_LOG_URL', KSK3D_CONTENTS_URL .'/log' );

//ユーザデータを格納するフォルダのパス、URL
define( 'KSK3D_CONT_USERS_PATH', KSK3D_CONTENTS_PATH .'/users' );
define( 'KSK3D_CONT_USERS_URL', KSK3D_CONTENTS_URL .'/users' );

//ログインしていないときのフォルダのパス、URL
define( 'KSK3D_CONT_GUEST_PATH', KSK3D_CONT_USERS_PATH .'/0-guest' );
define( 'KSK3D_CONT_GUEST_URL', KSK3D_CONT_USERS_URL .'/0-guest' );

//バックグラウンド処理条件
//実行制限、同時処理数（ユーザあたり）
define('KSK3D_PROC_LIMIT_SIM_FORUSER', 1);//1件
//実行制限、同時処理数（全体）
define('KSK3D_PROC_LIMIT_SIM_FORALL', 10);//5件
//実行制限、CPU負荷想定
define('KSK3D_PROC_LIMIT_CPU', 180);//180%
//強制キャンセル時間
define( 'KSK3D_PROC_CANCEL_TIME', '24:00:00' );//24時間

//Cesium設定
define( 'KSK3D_MAP_URL', esc_url( home_url( './map/3dcity-check/' )) );

//DBテーブル
define( 'KSK3D_TABLE_DATA', 'ksk3d_data' );
define( 'KSK3D_TABLE_MAP', 'ksk3d_map' );
define( 'KSK3D_TABLE_MAP_LAYER', 'ksk3d_map_layer' );
define( 'KSK3D_TABLE_ATTRIBUTE', 'ksk3d_attribute' );
define( 'KSK3D_TABLE_ATTRIBUTE_TPL_LIST', 'ksk3d_attribute_tpl_list' );
define( 'KSK3D_TABLE_ATTRIBUTE_TPL_VALUE', 'ksk3d_attribute_tpl_value' );
define( 'KSK3D_TABLE_CODE_LIST', 'ksk3d_code_list' );
define( 'KSK3D_TABLE_CODE_VALUE', 'ksk3d_code_value' );
define( 'KSK3D_TABLE_THEMATIC', 'ksk3d_thematic' );
define( 'KSK3D_TABLE_GROUP', 'ksk3d_group' );
define( 'KSK3D_TABLE_GROUP_USER', 'ksk3d_group_user' );
define( 'KSK3D_TABLE_USER', 'ksk3d_user' );
define( 'KSK3D_TABLE_ACC', 'ksk3d_acc' );
define( 'KSK3D_TABLE_PJT', 'ksk3d_pjt' );
define( 'KSK3D_TABLE_PJT_DATA', 'ksk3d_pjt_data' );
define( 'KSK3D_TABLE_CHK_MENU', 'ksk3d_check_menu' );
define( 'KSK3D_TABLE_CHK_RESULT', 'ksk3d_check_result' );
define( 'KSK3D_TABLE_CHK_LOG', 'ksk3d_check_log' );
define( 'KSK3D_TABLE_PROC', 'ksk3d_proc' );
define( 'KSK3D_TABLE_PROC_LOG', 'ksk3d_proc_log' );

//図形テーブル、属性テーブル
define( 'KSK3D_TABLE_GEOM', 'wp_ksk3d_geometry_' );
define( 'KSK3D_TABLE_ATTRIB', 'wp_ksk3d_attribute_' );

//実行ファイルのパス
//citygml-to-3dtiles
define( 'KSK3D_BIN_CITYGML_TO_3DTILES', '/usr/local/bin/citygml-to-3dtiles');

//proc
define( 'KSK3D_BIN_PROC', '/usr/bin/php /var/www/wordpress/wp-content/plugins/usvpro-3d-city-management/proc/procs.php');

