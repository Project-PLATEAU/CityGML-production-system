<?php
include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_view_list.php');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data.php');
add_shortcode('ksk3d_datalist','ksk3d_data::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_setting_height.php');
add_shortcode('ksk3d_data_setting_height','ksk3d_data_setting_height::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_citygml.php');
add_shortcode('ksk3d_data_citygml','ksk3d_data_citygml::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_csv.php');
add_shortcode('ksk3d_data_csv','ksk3d_data_csv::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_fgd.php');
add_shortcode('ksk3d_data_fgd','ksk3d_data_fgd::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_gml.php');
add_shortcode('ksk3d_data_gml','ksk3d_data_gml::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_internal_citygml.php');
add_shortcode('ksk3d_data_internal_citygml','ksk3d_data_internal_citygml::view');
include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_internal_joincsv.php');
add_shortcode('ksk3d_data_internal_joincsv','ksk3d_data_internal_joincsv::view');
include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_data_internal_ksk.php');
add_shortcode('ksk3d_data_internal_ksk','ksk3d_data_internal_ksk::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_map.php');
add_shortcode('ksk3d_maplist','ksk3d_map::view');
add_shortcode('ksk3d_mapview','ksk3d_map::map_view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_op_dataset.php');
add_shortcode('ksk3d_op_dataset','ksk3d_op_dataset::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_pjt.php');
add_shortcode('ksk3d_pjtlist','ksk3d_pjt::view');

include_once ( KSK3D_CLASS_PATH .'/class_ksk3d_proc.php');
add_shortcode('ksk3d_proc','ksk3d_proc::view');
