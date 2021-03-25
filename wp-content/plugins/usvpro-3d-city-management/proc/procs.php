<?php
if( php_sapi_name() !== 'cli' ) {
}

header("HTTP/1.0 200 OK");
$include_file = realpath(__DIR__ .'/../../../../wp-blog-header.php');
require_once($include_file);       
ksk3d_log("procs");


$id = ksk3d_fn_proc::get_execute_id();

while (!empty($id)){
  ksk3d_fn_proc::status_registration_to_wait();

  ksk3d_fn_proc::bgexec_proc($id);

  ksk3d_fn_proc::priority_update($id);

  $ct_cancel = ksk3d_fn_proc::forced_termination();

  ksk3d_fn_proc::Confi_cancellation();

  $id = ksk3d_fn_proc::get_execute_id();
}

