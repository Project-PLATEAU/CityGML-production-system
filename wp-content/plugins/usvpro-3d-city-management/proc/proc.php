<?php
if( php_sapi_name() !== 'cli' ) {
  die("Meant to be run from command line");
}

header("HTTP/1.0 200 OK");
$include_file = realpath(__DIR__ .'/../../../../wp-blog-header.php');
require_once($include_file);       
$id = $argv[1];
ksk3d_log("proc:{$id}");

if (!empty($id)){
  $result = ksk3d_fn_proc::execute($id);
}

return;