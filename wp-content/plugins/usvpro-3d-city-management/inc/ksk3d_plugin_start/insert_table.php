<?php
  global $wpdb;
  $dr = dirname( __FILE__ ) .'/insert_table/';

  $files = preg_grep('/\.csv$/' ,scandir($dr));
  foreach($files as $f){
    $path = pathinfo($f);
    $table = $wpdb->prefix .$path{'filename'};
    
    ksk3d_log("insert_table:".$table);
    
    $file = fopen("{$dr}{$f}", "r");
    $line = fgets($file);

    $sql = "SELECT count(id) AS CT FROM {$table}";
    $rows = $wpdb->get_results($sql);
    $rec_ct = $rows[0]->CT;

    if ($rec_ct == 0){
      $sql = "INSERT INTO {$table} ({$line}) VALUES \n";
      while ($line = fgets($file)) {
        if (!empty($line)){
          $sql .= "({$line}),";
        }

      }
      ksk3d_log( "sql:" .substr($sql ,0 ,-1) .";");
      $dlt = $wpdb->query(substr($sql ,0 ,-1) .";");
    }
  }
