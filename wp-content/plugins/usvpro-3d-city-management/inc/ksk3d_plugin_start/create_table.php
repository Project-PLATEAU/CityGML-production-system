<?php
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  $table_name = $wpdb->prefix .KSK3D_TABLE_DATA;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    file_id mediumint(9) UNSIGNED NOT NULL,
    display_name varchar(250),
    file_format varchar(50),
    file_name varchar(100) NOT NULL,
    file_path varchar(250) NOT NULL,
    file_size int(11) default 0,
    registration_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    meta_name varchar(100),
    meta_path varchar(250),
    memo_city varchar(100),
    memo varchar(250),
    meshsize int(2) default 0 NOT NULL,
    camera_position varchar(40),
    zip_name varchar(100),
    zip_path varchar(250),
    release_flg boolean DEFAULT FALSE NOT NULL,
    release_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    release_url varchar(250),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_MAP;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    map_id mediumint(9) UNSIGNED NOT NULL,
    display_name varchar(250),
    registration_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    release_flg boolean DEFAULT FALSE NOT NULL,
    release_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    release_url varchar(250),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 
  
  $table_name = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    file_id mediumint(9) UNSIGNED NOT NULL,
    map_id mediumint(9) UNSIGNED NOT NULL,
    layer_id mediumint(9) UNSIGNED NOT NULL,
    display_name varchar(250),
    file_format varchar(50),
    height_exp varchar(250),
    color_exp varchar(4090),
    meshsize int(2) default 0 NOT NULL,
    camera_position varchar(40),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  $table_name = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    file_id mediumint(9) UNSIGNED NOT NULL,
    attrib_id mediumint(9) UNSIGNED NOT NULL,
    attrib_field varchar(30),
    attrib_type varchar(30),
    attrib_digit int(4),
    attrib_name varchar(80),
    attrib_unit varchar(10),
    tag_path varchar(250),
    tag_name varchar(80),
    codelist_id mediumint(9),
    codelist_name varchar(200),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE_TPL_LIST;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    template_id mediumint(9) UNSIGNED NOT NULL,
    group1 varchar(30),
    template_name varchar(80),
    release_flg boolean DEFAULT FALSE NOT NULL,
    release_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE_TPL_VALUE;
  
  // drop処理追加
  $sql = "DROP TABLE IF EXISTS $table_name;";
  ksk3d_log($sql);
  $wpdb->query($sql);

  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    template_id mediumint(9) UNSIGNED NOT NULL,
    attrib_id mediumint(9) UNSIGNED NOT NULL,
    tag_name varchar(80),
    attrib_type varchar(30),
    attrib_digit int(4),
    attrib_name varchar(30),
    attrib_unit varchar(10),
    codelist_id mediumint(9),
    rank int(4),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_CODE_LIST;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    codelist_id mediumint(9) UNSIGNED NOT NULL,
    group1 varchar(30),
    group2 varchar(30),
    codelist_name varchar(80),
    attrib_type varchar(30),
    release_flg boolean DEFAULT FALSE NOT NULL,
    release_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_CODE_VALUE;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    codelist_id mediumint(9) UNSIGNED NOT NULL,
    code_id mediumint(9) UNSIGNED NOT NULL,
    attrib_value varchar(20),
    attrib_name varchar(80),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_THEMATIC;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    thematic_id mediumint(9) UNSIGNED NOT NULL,
    group1 varchar(30),
    group2 varchar(30),
    thematic_name varchar(80),
    attrib_type varchar(30),
    color_exp varchar(250),
    codelist_id mediumint(9),
    release_flg boolean DEFAULT FALSE NOT NULL,
    release_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 

  $table_name = $wpdb->prefix .KSK3D_TABLE_GROUP;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    group_name varchar(100),
    appid varchar(40),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  $table_name = $wpdb->prefix .KSK3D_TABLE_GROUP_USER;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL,
    group_id bigint(20) NOT NULL,
    exuser_id bigint(20) NOT NULL,
    user_id bigint(20) UNSIGNED NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  $table_name = $wpdb->prefix .KSK3D_TABLE_USER;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) UNSIGNED NOT NULL,
    appid varchar(40),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  $table_name = $wpdb->prefix .KSK3D_TABLE_ACC;
  $sql = "CREATE TABLE $table_name (
    user_id bigint(20) UNSIGNED NOT NULL,
    access_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    flg_mes int(4),
    UNIQUE KEY unique_id (user_id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  $table_name = $wpdb->prefix .KSK3D_TABLE_PJT;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    pjt_id mediumint(9) UNSIGNED NOT NULL,
    display_name varchar(100),
    registration_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    map_id mediumint(9) UNSIGNED NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql ); 
  
  $table_name = $wpdb->prefix .KSK3D_TABLE_PJT_DATA;
  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    pjt_id mediumint(9) UNSIGNED NOT NULL,
    features_name varchar(250),
    dataset_id mediumint(9) UNSIGNED NOT NULL,
    dataset_id2 mediumint(9) UNSIGNED NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  $table_name = $wpdb->prefix .KSK3D_TABLE_CHK_MENU;
  $sql = "CREATE TABLE $table_name (
    id int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
    type varchar(20),
    check_item varchar(20),
    quality_factor varchar(50),
    method varchar(250),
    target_unit varchar(40),
    target_filter varchar(100),
    style varchar(100),
    UNIQUE KEY id (id)
  ) $charset_collate;";
  ksk3d_log( "dbDelta:" .$sql );
  dbDelta( $sql );

  foreach([$wpdb->prefix .KSK3D_TABLE_CHK_LOG
    ,$wpdb->prefix .KSK3D_TABLE_CHK_RESULT] as $table_name){
    $sql = "CREATE TABLE $table_name (
      id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      user_id bigint(20) UNSIGNED NOT NULL,
      pjt_id mediumint(9) UNSIGNED NOT NULL,
      check_item varchar(20) NOT NULL,
      times int(4),
      check_result mediumint(9),
      errfile_ct mediumint(9),
      allfile_ct mediumint(9),
      check_description BLOB(4096),
      err_description BLOB(4096),
      registration_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      map_id mediumint(9) UNSIGNED NOT NULL,
      tag_name varchar(80),
      attrib_field varchar(40),
      attrib_name varchar(100),
      dataset_id mediumint(9) UNSIGNED NOT NULL,
      dataset_id2 mediumint(9) UNSIGNED,
      display_name varchar(250),
      file_name varchar(100) NOT NULL,
      PRIMARY KEY id (id)
    ) $charset_collate;";
    ksk3d_log( "dbDelta:" .$sql );
    dbDelta( $sql );
    }
    
  foreach([$wpdb->prefix .KSK3D_TABLE_PROC
    ,$wpdb->prefix .KSK3D_TABLE_PROC_LOG] as $table_name){
    $sql = "CREATE TABLE $table_name (
      id int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
      user_id bigint(20) UNSIGNED NOT NULL,
      proc_id bigint(20) UNSIGNED NOT NULL,
      process_disp varchar(100),
      process_cmd varchar(100),
      process_var_ct int,
      process_var longtext,
      status varchar(20),
      memo varchar(100),
      priority int,
      estimated_time int,
      cpuload int,
      pid int,
      registration_date datetime DEFAULT '0000-00-00 00:00:00',
      proc_start_data datetime DEFAULT '0000-00-00 00:00:00',
      proc_end_data datetime DEFAULT '0000-00-00 00:00:00',
      proc_time int,
      UNIQUE KEY id (id)
    ) $charset_collate;";
    ksk3d_log( "dbDelta:" .$sql );
    dbDelta( $sql );
  }
