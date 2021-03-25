<?php
class ksk3d_fn_proc{
  static $tbl = KSK3D_TABLE_PROC;

  static function bgexec_proc($id){
    ksk3d_log("ksk3d_fn_proc::bgexec_proc");
    ksk3d_log("id:".$id);

    $ksk3d_option = get_option('ksk3d_option');
    $bin = $ksk3d_option['ksk3d_bin_proc'];
    if (empty($bin)){
      $cmd = KSK3D_BIN_PROC;
    } else {
      $cmd = $bin;
    }
    $cmd = preg_replace("/( \S+\/)procs\.php /" ,"$1proc.php " ,$cmd);

    $cmd .= "-{$id} {$id}";


    $descriptorspec = array(
       0 => array("pipe", "r"),    
       1 => array("pipe", "w"),    
       2 => array("file", "/tmp/error-output.txt", "a")  
    );
    
    $process = proc_open($cmd, $descriptorspec, $pipes);
    if (is_resource($process)) {
      
      $etat = proc_get_status($process);
      $pid = $etat['pid'];
      if ($pid>0){
        global $wpdb;
        $tbl = $wpdb->prefix .static::$tbl;
        $sql = "UPDATE {$tbl} SET pid={$pid} where id={$id};";
        ksk3d_console_log($sql);
        $process = $wpdb->query($sql);
      }

      fclose($pipes[0]);

      fclose($pipes[1]);

      $return_value = proc_close($process);
      
    }
  }

  static function bgexec_procs(){
    ksk3d_console_log("ksk3d_fn_proc::bgexec_procs");
    $ksk3d_option = get_option('ksk3d_option');
    $bin = $ksk3d_option['ksk3d_bin_proc'];
    if (empty($bin)){
      $cmd = KSK3D_BIN_PROC;
    } else {
      $cmd = $bin;
    }
    $cmd .= " &";

    ksk3d_console_log("cmd:".$cmd);
    exec($cmd);
  }

  static function cancel($form_id ,$memo="キャンセルされました" ,$ret=true){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    $sql = "UPDATE {$tbl} SET status='キャンセル' WHERE id={$form_id} and status in ('登録','待機');";
    $st = $wpdb->query($sql,ARRAY_A);
    if ($st>0){
      $result = true;
    } else {
      $sql = "SELECT status,pid FROM {$tbl} WHERE id={$form_id}";
      $st = $wpdb->get_row($sql,ARRAY_A);

      $result = false;
      if (preg_match('/処理中|キャンセル中/' ,$st['status'])==1){
        if ($st['pid']>0){
          $pid = $st['pid'];
          ksk3d_console_log("pid:".$pid);

          $cmd = "ps " .$pid ."|grep ".$pid;
          exec($cmd, $output, $result);
          ksk3d_console_log("output");
          ksk3d_console_log($output);
          if (is_array($output)){
            $test = preg_grep('/^'.$pid.' /' ,$output);
          } else {
            preg_match('/^'.$pid.' /' ,$output ,$test);
          }
          if (!empty($test)){
            $cmd = "kill ".$pid;
            ksk3d_console_log("exec:".$cmd);
            if (exec($cmd)){
              $result = true;
            } else {
              $result = false;
              $sql = "UPDATE {$tbl} SET status='キャンセル中' WHERE id={$form_id};";
              $st = $wpdb->query($sql,ARRAY_A);
            }
          } else {
            $result = true;
          }

          $sql = "SELECT pid FROM {$tbl} WHERE id={$form_id} and status='キャンセル中' and pid>0;";
          $pid = $wpdb->get_var($sql);
        }
      }
    }
    
    if ($result){
      $result = $wpdb->update(
        $tbl,
        array(
          'status' =>  "キャンセル",
          'memo' =>  $memo,
          'priority' =>  null,
          'proc_end_data' =>  current_time('mysql')
        ),
        array(
          'id' =>  $form_id
        ),
        array(
          '%s',
          '%s',
          '%s'
        ),
        array(
          '%d'
        )
      );
    }

    if ($result != false){
      $text = $result ."件キャンセルしました。<br>\n";
      if($ret){static::bgexec_procs();}
    } else {
      $text = "キャンセルできませんでした";
    }

    return $text;
  }
  
  static function Confi_cancellation(){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;
    $sql = "select id,pid from {$tbl} where status='キャンセル中' and pid>0;";
    ksk3d_console_log($sql);
    $rows = $wpdb->get_results($sql ,ARRAY_A);

    foreach($rows as $row){
      $pid = $row['pid'];

      $cmd = "ps " .$pid ."|grep ".$pid;
      exec($cmd, $output, $result);
      if (is_array($output)){
        $test = preg_grep('/^'.$pid.' /' ,$output);
      } else {
        preg_match('/^'.$pid.' /' ,$output ,$test);
      }
      if (!empty($test)){
        $cmd = "kill ".$pid;
        ksk3d_console_log("exec:".$cmd);
        if (exec($cmd)){
          $result = true;
        } else {
          $result = false;
          $sql = "UPDATE {$tbl} SET status='キャンセル中' WHERE id={$form_id};";
          $st = $wpdb->query($sql,ARRAY_A);
        }
      } else {
        $result = true;
      }

      if ($result){
        $result = $wpdb->update(
          $tbl,
          array(
            'status' =>  "キャンセル",
            'priority' =>  null,
            'proc_end_data' =>  current_time('mysql')
          ),
          array(
            'id' =>  $form_id
          ),
          array(
            '%s',
            '%s',
            '%s'
          ),
          array(
            '%d'
          )
        );
      }
    }
  }

  static function execute($id){
    ksk3d_console_log("ksk3d_fn_proc::execute");
    
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;
    $sql = "select * from {$tbl} where id={$id};";
    ksk3d_console_log($sql);
    $process = $wpdb->get_row($sql ,ARRAY_A);

    putenv("KSK3D_USER_ID=".$process['user_id']);
    $process_var = json_decode($process['process_var'] ,true);
    
    $proc_start_data = current_time('mysql');
    $result = $wpdb->update(
      $tbl,
      array(
        'proc_start_data' =>  $proc_start_data
      ),
      array(
        'id' =>  $id
      ),
      array(
        '%s'
      ),
      array(
        '%d'
      )
    );

    $func_name=$process['process_cmd'];
    $var1 = $process_var;
    if (count($var1)==1){$func_name($var1[0]);}
    else if (count($var1)==2){$func_name($var1[0],$var1[1]);}
    else if (count($var1)==3){$func_name($var1[0],$var1[1],$var1[2]);}
    else if (count($var1)==4){$func_name($var1[0],$var1[1],$var1[2],$var1[3]);}
    else if (count($var1)==5){$func_name($var1[0],$var1[1],$var1[2],$var1[3],$var1[4]);}
    else if (count($var1)==6){$func_name($var1[0],$var1[1],$var1[2],$var1[3],$var1[4],$var1[5]);}
    else if (count($var1)==7){$func_name($var1[0],$var1[1],$var1[2],$var1[3],$var1[4],$var1[5],$var1[6]);}
    else if (count($var1)==8){$func_name($var1[0],$var1[1],$var1[2],$var1[3],$var1[4],$var1[5],$var1[6],$var1[7]);}
    else if (count($var1)==9){$func_name($var1[0],$var1[1],$var1[2],$var1[3],$var1[4],$var1[5],$var1[6],$var1[7],$var1[8]);}
    else if (count($var1)==10){$func_name($var1[0],$var1[1],$var1[2],$var1[3],$var1[4],$var1[5],$var1[6],$var1[7],$var1[8],$var1[9]);}

    $proc_end_data = current_time('mysql');
    $proc_time = floor((strtotime($proc_end_data)-strtotime($proc_start_data)));
    
    $result = $wpdb->update(
      $tbl,
      array(
        'status' =>  "完了",
        'priority' =>  null,
        'proc_end_data' =>  $proc_end_data,
        'proc_time' =>  $proc_time
      ),
      array(
        'id' =>  $id
      ),
      array(
        '%s',
        '%s',
        '%s',
        '%d'
      ),
      array(
        '%d'
      )
    );

    static::bgexec_procs();
    
    return true;
  }

  static function forced_termination(){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    $sql = "select id from {$tbl} where status in ('処理中','検証','キャンセル中') and proc_start_data>0 and proc_start_data < SUBTIME(CURRENT_TIMESTAMP ,'".KSK3D_PROC_CANCEL_TIME."');";
    ksk3d_console_log($sql);

    $results = $wpdb->get_results($sql ,ARRAY_A);
    if (!empty($results)){
      foreach($results as $result){
        static::cancel($result['id'] ,$memo="指定時間超過と混雑により強制キャンセルされました." ,false);
      }
      return true;
    }
    return false;
  }

  static function get_ct_processing(){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    $sql = "select count(id) from {$tbl} where status = '処理中';";
    ksk3d_console_log($sql);

    $result = $wpdb->get_var($sql);
    ksk3d_console_log("ct_processing:".$result);
    return $result;
  }

  static function get_ct_untreated(){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    $sql = "select count(id) from {$tbl} where status in ('登録','待機');";
    ksk3d_console_log($sql);

    $result = $wpdb->get_var($sql);
    ksk3d_console_log("ct_untreated:".$result);
    return $result;
  }

  static function get_execute_id(){
    ksk3d_console_log("get_execute_id");
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;
    
    $limit_cpu = KSK3D_PROC_LIMIT_CPU;
    $limit_forall = KSK3D_PROC_LIMIT_SIM_FORALL;
    $limit_foruser = KSK3D_PROC_LIMIT_SIM_FORUSER;

    $condition = <<<EOL
status in ('待機','登録')
  and ifnull(cpuload,5)<({$limit_cpu}-(SELECT ifnull(B1.cpuload,0) FROM (SELECT SUM(ifnull(cpuload,0)) as cpuload FROM {$tbl} WHERE status in ('処理中','キャンセル中'))B1))
  and {$limit_forall}>=(SELECT B2.id FROM (SELECT COUNT(id) as id FROM {$tbl} WHERE status in ('処理中','キャンセル中'))B2)
  and not user_id in (SELECT B3.user_id FROM (SELECT user_id FROM {$tbl} GROUP BY user_id,status HAVING status in ('処理中','キャンセル中') and count(user_id)>={$limit_foruser})B3)
ORDER BY priority
LIMIT 1
EOL
;

    $sql = "SELECT id FROM {$tbl} WHERE {$condition};";
    ksk3d_console_log("sql:".$sql);
    $id = $wpdb->get_var($sql);

    if (empty($id)){
      $sql = <<<EOL
SELECT count(id) FROM {$tbl}
  WHERE
    status in ('待機','登録')
EOL
;
      $id = $wpdb->get_var($sql);
      ksk3d_console_log("待機,登録の候補：".$id);
      
      $sql = <<<EOL
SELECT count(id) FROM {$tbl}
  WHERE
    status in ('待機','登録')
    and ifnull(cpuload,5)<({$limit_cpu}-(SELECT ifnull(B1.cpuload,0) FROM (SELECT SUM(ifnull(cpuload,0)) as cpuload FROM {$tbl} WHERE status in ('処理中','キャンセル中'))B1))
EOL
;
      $id = $wpdb->get_var($sql);
      ksk3d_console_log("CPU負荷の制限範囲内の候補：".$id);

      $sql = <<<EOL
SELECT {$limit_forall}>=(SELECT B2.id FROM (SELECT COUNT(id) as id FROM {$tbl} WHERE status='処理中')B2)
EOL
;
      $id = $wpdb->get_var($sql);
      ksk3d_console_log("全体の同時処理の可否：".$id);

      $sql = <<<EOL
SELECT count(id) FROM {$tbl}
  WHERE
    not user_id in (SELECT B3.user_id FROM (SELECT user_id FROM {$tbl} GROUP BY user_id,status HAVING status in ('処理中','キャンセル中') and count(user_id)>={$limit_foruser})B3)
EOL
;
      $id = $wpdb->get_var($sql);
      ksk3d_console_log("ユーザの同時処理可能な候補：".$id);

      return 0;
    }

    ksk3d_console_log("id:".$id);

    $sql = "UPDATE {$tbl} SET status='処理中' WHERE id = {$id} and {$condition}";
    ksk3d_console_log("sql:".$sql);
    $result = $wpdb->query($sql);
    if ($result>0){
      ksk3d_console_log("id:".$id);
      return $id;
    } else {
      ksk3d_console_log("同時アクセスによる調整（0:ok）：1");
      return 0;
    }
  }

  static function get_max_proc_id($user_id){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    $sql = "select max(proc_id) as proc_id from {$tbl} where user_id={$user_id};";
    ksk3d_console_log($sql);
    $result = $wpdb->get_var($sql);
    ksk3d_log("max_proc_id:".$result);
    return $result;
  }

  static function get_proc($user_id ,$proc_id){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    $sql = "select * from {$tbl} where user_id={$user_id} and proc_id={$proc_id};";
    ksk3d_console_log($sql);
    return $wpdb->get_results($sql ,ARRAY_A);
  }

  static function priority_update($id=""){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;

    if (empty($id)){
      $user_id = getenv("KSK3D_USER_ID");
    } else {
      $user_id = $wpdb->get_var("SELECT user_id FROM {$tbl} WHERE id={$id}");
    }
    if ($user_id > 0){
      $sql = "update {$tbl} set priority = priority-1 where user_id <> {$user_id} and status in ('登録','待機');";
      ksk3d_console_log($sql);

      $result = $wpdb->query($sql);
      return $result;
    }
  }

  static function registration($process_disp ,$process_cmd ,$process_var_ct ,$v_array ,$cpuload ,$estimated_time){
    $process_var = json_encode($v_array ,JSON_UNESCAPED_UNICODE);

    $user_id = ksk3d_get_current_user_id();
    $proc_id = static::get_max_proc_id($user_id);
    if (empty($proc_id)){
      $proc_id = 1;
      $priority = 100;
    } else {
      $result = static::get_proc($user_id ,$proc_id);
      $proc_id = $proc_id +1;
      if ($result[0]['priority']<100+10){
        $priority = 110;
      } else {
        $priority = $result[0]['priority'] +10;
      }
    }

    $v = array(
      'user_id' =>  $user_id,
      'proc_id' =>  $proc_id,
      'process_disp' =>  $process_disp,
      'process_cmd' =>  $process_cmd,
      'process_var_ct' =>  $process_var_ct,
      'process_var' =>  $process_var,
      'status' =>  "登録",
      'priority' =>  $priority,
      'cpuload' =>  $cpuload,
      'estimated_time' =>  $estimated_time,
      'registration_date' =>  current_time('mysql')
    );
    ksk3d_console_log($v);

    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;
    $result = $wpdb->insert(
      $tbl,
      array(
        'user_id' =>  $user_id,
        'proc_id' =>  $proc_id,
        'process_disp' =>  $process_disp,
        'process_cmd' =>  $process_cmd,
        'process_var_ct' =>  $process_var_ct,
        'process_var' =>  $process_var,
        'status' =>  "登録",
        'priority' =>  $priority,
        'cpuload' =>  $cpuload,
        'estimated_time' =>  $estimated_time,
        'registration_date' =>  current_time('mysql')
      ),
      array(
        '%d',
        '%d',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%s'
      )
    );
    if ($result != false){
      $text = $result ."件登録しました。<br>\n";
    } else {
      $text = "失敗しました<br>\n";
    }

    static::bgexec_procs();

    return $text;
  }

  static function reprocess($form_id){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;
    $result = ksk3d_fn_db::sel("SELECT * FROM {$tbl} WHERE id={$form_id};")[0];

    static::registration(
      $result['process_disp'],
      $result['process_cmd'],
      $result['process_var_ct'],
      json_decode($result['process_var'] ,JSON_UNESCAPED_UNICODE),
      $result['cpuload'],
      $result['estimated_time']
    );

    return;
  }

  static function status_registration_to_wait(){
    global $wpdb;
    $tbl = $wpdb->prefix .static::$tbl;
    $result = $wpdb->update(
      $tbl,
      array(
        'status' =>  "待機"
      ),
      array(
        'status' =>  "登録"
      ),
      array(
        '%s'
      ),
      array(
        '%s'
      )
    );
  }

}
