<?php
class ksk3d_pjt extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_PJT;
  static $tbl_ref = [
    array(
      'table' => KSK3D_TABLE_PJT_DATA,
      'ref' => KSK3D_TABLE_DATA,
      'on' => 'a.dataset_id=b.file_id and a.user_id=b.user_id'
    ),
    'gml2db_set_attrib'=>array(
      'table' => KSK3D_TABLE_ATTRIBUTE_TPL_VALUE,
      'wh' => ''
    ),
    'ck_logic'=>array(
      'table' => KSK3D_TABLE_CHK_MENU,
      'ref' => KSK3D_TABLE_CHK_RESULT,
      'on' => 'a.check_item=b.check_item',
      'wh' => 'a.type="logic"'
    ),
    'ck_visually'=>array(
      'table' => KSK3D_TABLE_CHK_RESULT,
      'ref' => KSK3D_TABLE_CHK_MENU,
      'on' => 'a.check_item=b.check_item',
      'wh' => 'type="visually"'
    ),
    'pre_setting_style'=>array(
      'table' => KSK3D_TABLE_CHK_RESULT,
      'ref' => KSK3D_TABLE_CHK_MENU,
      'on' => 'a.check_item=b.check_item',
      'wh' => 'type="visually"'
    ),
  ];
  
  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_pjt.css',
      'ksk3d_map.css'
    ],
    'id' => 'pjt_id'
  ];

  static function view2(){
    if (isset($_POST["submit"]["detail_ref2"])) {
      return static::detail_ref2();
    } else if (isset($_POST["submit"]["gml2db_set_attrib"])) {
      return static::gml2db_set_attrib();
    } else if (isset($_POST["submit"]["gml2db_set_attrib_fromonefile"])) {
      return static::gml2db_set_attrib(1);
    } else if (isset($_POST["submit"]["gml2db_set_attrib_fromallfile"])) {
      return static::gml2db_set_attrib(2);
    } else if (isset($_POST["submit"]["gml2DB_check2"])) {
      return static::gml2DB_check2();
    } else if (isset($_POST["submit"]["set_attrib_exec"])) {
      return static::set_attrib_exec();
    } else if (isset($_POST["submit"]["set_attrib_bgexec"])) {
      return static::set_attrib_bgexec();
    } else if (isset($_POST["submit"]["ck_logic"])) {
      return static::ck_logic();
    } else if (isset($_POST["submit"]["ck_logic_check"])) {
      return static::ck_logic_check();
    } else if (isset($_POST["submit"]["ck_logic_exec"])) {
      return static::ck_logic_exec();
    } else if (isset($_POST["submit"]["ck_logic_bgexec"])) {
      return static::ck_logic_bgexec();
    } else if (isset($_POST["submit"]["ck_visually"])) {
      return static::ck_visually();
    } else if (isset($_POST["submit"]["pre_create_map"])) {
      return static::pre_create_map();
    } else if (isset($_POST["submit"]["pre_create_map_check"])) {
      return static::pre_create_map_check();
    } else if (isset($_POST["submit"]["pre_create_map_exec"])) {
      return static::pre_create_map_exec();
    } else if (isset($_POST["submit"]["pre_convert_data_bgexec"])) {
      return static::pre_convert_data_bgexec();
    } else if (isset($_POST["submit"]["pre_setting_style"])) {
      return static::pre_setting_style();
    } else if (isset($_POST["submit"]["pre_setting_style_exec"])) {
      return static::pre_setting_style_exec();
    } else if (isset($_POST["submit"]["pre_create_mesh"])) {
      return static::pre_create_mesh();
    } else if (isset($_POST["submit"]["pre_create_mesh_exec"])) {
      return static::pre_create_mesh_exec();
    } else if (isset($_POST["submit"]["map_view"])) {
      return static::map_view();
    } else {
      return static::disp();
    }
  }

  static $tab = [ 
      array(
      'tab' => '1',
      'displayName' => '1??????'
    )
  ];
  
  static $field = [
    array(
      'tab' => '0',
      'displayName' => '??????????????????ID',
      'dbField' => 'pjt_id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => '?????????????????????',
      'dbField' => 'display_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    )
  ];

  static $button_value = [
    '' => '?????????????????????????????????',
    'detail' => '??????',
    'edit' => '??????',
    'edit_check' => '?????????????????????',
    'edit_exec' => '???????????????',
    'delete_check' => '??????',
    'delete_exec' => '???????????????',
    'detail_ref' => '??????????????????',
    'edit_ref_list' => '???????????????????????????',
    'edit_ref' => '??????????????????',
    'edit_ref_check' => '?????????????????????',
    'edit_ref_exec' => '???????????????',
    'edit_ref_del' => '???????????????',
    'detail_ref2' => '?????????????????????',
    'gml2db_set_attrib' => '?????????????????????',
    'gml2db_set_attrib_fromallfile' => '??????????????????????????????????????????',
    'gml2db_set_attrib_fromonefile' => '1?????????????????????????????????',
    'gml2DB_check2' => '????????????????????????????????????',
    'set_attrib_exec' => '?????????????????????',
    'set_attrib_bgexec' => '?????????????????????????????????',
    'ck_logic' => '????????????',
    'ck_logic_check' => '??????',
    'ck_logic_exec' => '????????????',
    'ck_logic_bgexec' => '???????????????????????????????????????????????????',
    'ck_visually' => '????????????',
    'pre_create_map' => '???????????????',
    'pre_create_map_check' => '???????????????????????????????????????',
    'pre_create_map_exec' => '?????????????????????',
    'pre_convert_data' => '????????????????????????',
    'pre_convert_data_bgexec' => '???????????????????????????????????????????????????????????????',
    'pre_setting_style' => '???????????????',
    'pre_setting_style_exec' => '?????????????????????',
    'pre_create_mesh' => '??????????????????',
    'pre_create_mesh_exec' => '????????????????????????',
    'map_view' => '?????????',
    'regist' => '????????????',
    'regist_check' => '?????????????????????',
    'regist_exec' => '???????????????',
    'regist_file' => '??????(??????????????????????????????)',
    'regist_file_up' => '??????????????????',
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'detail',
      'displayName' => '??????',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => '1',
      'submit' => 'detail_ref',
      'displayName' => '??????????????????',
      'th-style' => 'width:140px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => '1',
      'submit' => 'ck_logic',
      'displayName' => '????????????', 
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => '1',
      'submit' => 'ck_visually',
      'displayName' => '????????????', 
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => '0',
      'submit' => 'delete_check',
      'displayName' => '??????', 
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'status' => ''
    )
  ];
  
  static $post = [
    'disp' => [
      'header-title' => '????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'detail' => [
      'header-title' => '??????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit' => [
      'header-title' => '??????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_check' => [
      'header-title' => '????????????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_exec' => [
      'header-title' => '????????????????????????????????????',
      'header-text' => '',
      'main-text' => '???????????????????????????',
      'footer-text' => ''
    ],
    'delete_check' => [
      'header-title' => '??????????????????????????????',
      'header-text' => '?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'delete_exec' => [
      'header-title' => '??????????????????????????????',
      'header-text' => '',
      'main-text' => '???????????????????????????',
      'footer-text' => ''
    ],
    'detail_ref' => [
      'header-title' => '?????????????????????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_ref_list' => [
      'header-title' => '????????????????????????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_ref' => [
      'header-title' => '??????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_ref_check' => [
      'header-title' => '????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_ref_exec' => [
      'header-title' => '????????????????????????',
      'header-text' => '',
      'main-text' => '???????????????????????????',
      'footer-text' => ''
    ],
    'edit_ref_del' => [
      'header-title' => '???????????????????????????',
      'header-text' => '',
      'main-text' => '??????????????????????????????',
      'footer-text' => ''
    ],
    'ck_logic' => [
      'header-title' => '????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'ck_logic_check' => [
      'header-title' => '?????????????????????',
      'header-text' => '???????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'ck_logic_exec' => [
      'header-title' => '?????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'ck_logic_bgexec' => [
      'header-title' => '?????????????????????????????????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'ck_visually' => [
      'header-title' => '????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'detail_ref2' => [
      'header-title' => '???????????????????????????',
      'header-text' => '????????????????????????????????????????????????????????????????????????<br>
???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????<br>
<br>
?????????????????????????????????????????????????????????????????????????????????????????????<br>
????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????<br>
?????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2db_set_attrib_fromonefile' => [
      'header-title' => '????????????????????????1???????????????????????????',
      'header-text' => '??????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2db_set_attrib_fromallfile' => [
      'header-title' => '????????????????????????????????????????????????????????????',
      'header-text' => '??????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2db_set_attrib' => [
      'header-title' => '?????????????????????',
      'header-text' => '??????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2DB_check2' => [
      'header-title' => '?????????????????????????????????????????????',
      'header-text' => '????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'set_attrib_exec' => [
      'header-title' => '?????????????????????',
      'header-text' => '?????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => '?????????????????????'
    ],
    'set_attrib_bgexec' => [
      'header-title' => '?????????????????????????????????????????????????????????',
      'header-text' => '??????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_create_map' => [
      'header-title' => '????????????????????????????????????????????????',
      'header-text' => '???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_create_map_check' => [
      'header-title' => '???????????????????????????????????????',
      'header-text' => '??????????????????????????????????????????????????????????????????????????????????????????????????????????????????<br>
??????????????????????????????????????????????????????????????????????????????????????????<br>
????????????????????????????????????????????????????????????????????????????????????????????????<br>
',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_create_map_exec' => [
      'header-title' => '?????????????????????',
      'header-text' => '????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => '?????????????????????'
    ],
    'pre_convert_data' => [
      'header-title' => '????????????????????????',
      'header-text' => '??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_convert_data_bgexec' => [
      'header-title' => '??????????????????????????????????????????????????????????????????',
      'header-text' => '?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_setting_style' => [
      'header-title' => '???????????????',
      'header-text' => '???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_setting_style_exec' => [
      'header-title' => '?????????????????????',
      'header-text' => '??????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => '?????????????????????'
    ],
    'pre_create_mesh' => [
      'header-title' => '??????????????????',
      'header-text' => '????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => ''
    ],
    'pre_create_mesh_exec' => [
      'header-title' => '????????????????????????',
      'header-text' => '?????????????????????????????????????????????????????????',
      'main-text' => '',
      'footer-text' => '?????????????????????'
    ],
    'map_view' => [
      'header-title' => '',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist' => [
      'header-title' => '????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist_check' => [
      'header-title' => '?????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist_exec' => [
      'header-title' => '????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist_file' => [
      'header-title' => '??????(??????????????????????????????)',
      'header-text' => '?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????zip?????????????????????????????????????????????',
      'main-text' => '????????????????????????????????????????????????????????????????????????????????????',
      'footer-text' => ''
     ],
    'regist_file_up' => [
      'header-title' => '????????????????????????????????????',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
   ]
];

  static $field_ref = [
    array(
      'tab' => '0',
      'displayName' => '??????',
      'dbField' => 'a.features_name',
      'dbField_ref' => 'features_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => '??????????????????ID',
      'dbField' => 'a.dataset_id',
      'dbField_ref' => 'dataset_id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => '?????????????????????',
      'dbField' => 'b.display_name',
      'dbField_ref' => 'display_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'disp_prj_data',
      'displayName' => '??????',
      'dbField' => "(CASE WHEN b.file_name REGEXP '^[0-9]{6}_dem_' THEN '???????????????????????????' ELSE null END)",
      'dbField_ref' => 'memo',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '????????????',
      'gmlValue' => 'tag_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'tag_path',
      'gmlValue' => 'tag_path',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'tag_attrib',
      'gmlValue' => 'tag_attrib',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'tag_attrib_name',
      'gmlValue' => 'tag_attrib_name',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '??????????????????',
      'gmlValue' => 'field_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '???',
      'dbField' => 'attrib_type',
      'gmlValue' => 'attrib_type',
      'default' => 'VARCHAR',
      'editer' => '',
      'select' => 'VARCHAR,INT,DOUBLE',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '???',
      'dbField' => 'attrib_digit',
      'default' => '100',
      'editer' => '',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '????????????',
      'dbField' => 'attrib_name',
      'gmlValue' => 'attrib_name',
      'default' => '??????[_n%]',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '??????',
      'dbField' => 'attrib_unit',
      'gmlValue' => 'attrib_unit',
      'default' => '',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '???????????????????????????',
      'gmlValue' => 'attrib_value',
      'shortened' => true,
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '?????????',
      'gmlValue' => 'codelist',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '??????No.',
      'dbField' => 'a.check_item',
      'dbField_ref' => 'check_item',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '????????????',
      'dbField' => 'a.quality_factor',
      'dbField_ref' => 'quality_factor',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '??????????????????',
      'dbField' => 'a.method',
      'dbField_ref' => 'method',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:160px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '????????????',
      'dbField' => 'b.times',
      'dbField_ref' => 'times',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '????????????',
      'dbField' => 'b.registration_date',
      'dbField_ref' => 'registration_date',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '????????????',
      'dbField' => 'b.check_result',
      'dbField_ref' => 'check_result',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '??????????????????',
      'ck_result' => '1',
      'dbField' => 'b.err_description',
      'dbField_ref' => 'err_description',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:300px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic',
      'displayName' => '????????????',
      'ck_result' => '1',
      'dbField' => 'b.check_description',
      'dbField_ref' => 'check_description',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:300px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'displayName' => '??????No.',
      'dbField' => 'b.check_item',
      'dbField_ref' => 'check_item',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'displayName' => '????????????',
      'dbField' => 'b.quality_factor',
      'dbField_ref' => 'quality_factor',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'displayName' => '??????????????????',
      'dbField' => 'b.method',
      'dbField_ref' => 'method',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:300px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'displayName' => '??????',
      'dbField' => 'a.attrib_name',
      'dbField_ref' => 'attrib_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'displayName' => '????????????',
      'dbField' => 'a.times',
      'dbField_ref' => 'times',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'displayName' => '????????????',
      'dbField' => 'a.registration_date',
      'dbField_ref' => 'registration_date',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2_file',
      'displayName' => '??????',
      'dbField' => 'a.features_name',
      'dbField_ref' => 'features_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2_file',
      'displayName' => '????????????????????????',
      'dbField' => 'a.dataset_id',
      'dbField_ref' => 'dataset_id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2_file',
      'displayName' => '???????????????',
      'dbField' => 'b.file_name',
      'dbField_ref' => 'file_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2_dataset',
      'displayName' => '??????',
      'dbField' => 'a.features_name',
      'dbField_ref' => 'features_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2_dataset',
      'displayName' => '????????????????????????',
      'dbField' => 'a.dataset_id',
      'dbField_ref' => 'dataset_id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2_dataset',
      'displayName' => '?????????????????????',
      'dbField' => 'b.display_name',
      'dbField_ref' => 'display_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'disp_prj_data_cklist',
      'displayName' => '??????No.',
      'variable1' => 'ck_menu',
      'variable2' => 'check_item',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'disp_prj_data_cklist',
      'displayName' => '????????????',
      'variable1' => 'ck_menu',
      'variable2' => 'quality_factor',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'disp_prj_data_cklist',
      'displayName' => '??????????????????',
      'variable1' => 'ck_menu',
      'variable2' => 'method',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:400px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'disp_prj_data_cklist',
      'displayName' => '??????',
      'variable1' => 'attrib',
      'variable2' => 'attrib_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'pre_setting_style',
      'displayName' => '??????No.',
      'dbField' => 'b.check_item',
      'dbField_ref' => 'check_item',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'pre_setting_style',
      'displayName' => '????????????',
      'dbField' => 'b.quality_factor',
      'dbField_ref' => 'quality_factor',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'pre_setting_style',
      'displayName' => '??????????????????',
      'dbField' => 'b.method',
      'dbField_ref' => 'method',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:450px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'pre_setting_style',
      'displayName' => '??????',
      'dbField' => 'a.attrib_name',
      'dbField_ref' => 'attrib_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'pre_setting_style',
      'displayName' => '????????????????????????',
      'dbField' => 'b.style',
      'dbField_ref' => 'style',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => 'C02',
      'displayName' => '?????????????????????',
      'ck_result' => '2',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'L05',
      'displayName' => '?????????????????????',
      'ck_result' => '2',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2',
      'displayName' => '????????????',
      'ck_result' => '0',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => 'ck_logic2',
      'displayName' => '??????????????????',
      'ck_result' => '1',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:300px;',
      'td-style' => '',
      'status' => ''
    )
  ];

  static $button_ref_ = [
    array(
      'tab' => '0',
      'submit' => 'edit_ref',
      'displayName' => '??????????????????',
      'th-style' => 'width:140px;',
      'td-style' => 'text-align:center',
      'status' => ''
   ),array(
      'tab' => '0',
      'submit' => 'detail_ref2',
      'displayName' => '?????????????????????',
      'th-style' => 'width:160px;',
      'td-style' => 'text-align:center',
      'status' => ''
   ),array(
      'tab' => '0',
      'submit' => 'edit_ref_del',
      'displayName' => '???????????????', 
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => 'ck_visually',
      'submit' => 'map_view',
      'displayName' => '?????????', 
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'status' => ''
    )
  ];

  static $header_button = [
    'disp' => [
      array(
        'submit' => 'regist',
        'class' => 'button-primary',
      )
    ],
    'detail' => [
    ],
    'edit' => [
    ],
    'edit_check' => [
    ],
    'edit_exec' => [
    ],
    'delete_check' => [
    ],
    'delete_exec' => [
    ],
    'detail_ref' => [
    ],
    'edit_ref_list' => [
    ],
    'edit_ref' => [
    ],
    'edit_ref_check' => [
    ],
    'edit_ref_exec' => [
    ],
    'detail_ref2' => [
    ],
    'gml2db_set_attrib' => [
    ],
    'gml2DB_check2' => [
    ],
    'set_attrib_exec' => [
    ],
    'set_attrib_bgexec' => [
    ],
    'ck_logic' => [
    ],
    'ck_logic_check' => [
    ],
    'ck_logic_exec' => [
    ],
    'ck_logic_bgexec' => [
    ],
    'ck_visually' => [
    ],
    'pre_create_map' => [
    ],
    'pre_create_map_check' => [
    ],
    'pre_create_map_exec' => [
    ],
    'pre_convert_data' => [
    ],
    'pre_convert_data' => [
    ],
    'pre_convert_data_bgexec' => [
    ],
    'pre_setting_style' => [
    ],
    'pre_setting_style_exec' => [
    ],
    'pre_create_mesh' => [
    ],
    'pre_create_mesh_exec' => [
    ],
    'map_view' => [
    ],
    'regist' => [
    ],
    'regist_check' => [
    ],
    'regist_exec' => [
    ],
    'regist_file' => [
    ],
    'regist_file_up' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'detail' => [
      array(
        'submit' => 'edit[{$form_id}]',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit' => [
      array(
        'submit' => 'edit_check',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit_check' => [
      array(
        'submit' => 'edit_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'edit',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'delete_check' => [
      array(
        'submit' => 'delete_exec',
        'class' => 'btn-danger',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'delete_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'detail_ref2' => [
      array(
        'submit' => 'gml2db_set_attrib_fromallfile',
        'class' => 'btn-primary',
        'onclick' => 'myform.ksk3d_key.value="open_set_attrib"'
      ),
      array(
        'submit' => 'gml2db_set_attrib_fromonefile',
        'class' => 'btn-primary',
        'onclick' => 'myform.ksk3d_key.value=""'
      ),
      array(
        'submit' => 'edit_ref_list[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '????????????????????????????????????',
        'onclick' => 'myform.ksk3d_key.value=""'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
        'onclick' => 'myform.ksk3d_key.value=""'
      )
    ],
    'gml2db_set_attrib' => [
      array(
        'submit' => 'gml2DB_check2',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'edit_ref_list[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '????????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'gml2DB_check2' => [
      array(
        'submit' => 'set_attrib_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'gml2db_set_attrib',
        'class' => 'btn-secondary',
        'display' => '????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'set_attrib_exec' => [
      array(
        'submit' => 'edit_ref_list[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '????????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'set_attrib_bgexec' => [
      array(
        'submit' => 'edit_ref_list[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '????????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'ck_logic' => [
      array(
        'submit' => 'ck_logic_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'ck_logic_check' => [
      array(
        'submit' => 'ck_logic_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'ck_logic[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'ck_logic_exec' => [
      array(
        'submit' => 'ck_logic[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'ck_logic_bgexec' => [
      array(
        'submit' => 'ck_logic[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'ck_visually' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_create_map' => [
      array(
        'submit' => 'pre_create_map_check',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_create_map_check' => [
      array(
        'submit' => 'pre_create_map_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_create_map_exec' => [
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_convert_data' => [
      array(
        'submit' => 'pre_convert_data_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_convert_data_bgexec' => [
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_setting_style' => [
      array(
        'submit' => 'pre_setting_style_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_setting_style_exec' => [
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_create_mesh' => [
      array(
        'submit' => 'pre_create_mesh_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'pre_create_mesh_exec' => [
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'map_view' => [
      array(
        'submit' => 'ck_visually[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'regist' => [
      array(
        'submit' => 'regist_check',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'regist_check' => [
      array(
        'submit' => 'regist_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'regist',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'regist_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'regist_file' => [
      array(
        'submit' => 'detail_ref[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'regist_file_up' => [
      array(
        'submit' => 'detail_ref[{$form_id}]',
        'class' => 'btn-primary',
        'display' => '?????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'detail_ref' => [
      array(
        'submit' => 'regist_file[{$form_id}]',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'edit_ref_list[{$form_id}]',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit_ref_list' => [
      array(
        'submit' => 'detail_ref[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit_ref' => [
      array(
        'submit' => 'edit_ref_check',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'edit_ref_list[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit_ref_check' => [
      array(
        'submit' => 'edit_ref_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'edit_ref',
        'class' => 'btn-secondary',
        'display' => '?????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'edit_ref_exec' => [
      array(
        'submit' => 'detail_ref[{$form_id}]',
        'class' => 'btn-secondary',
        'display' => '?????????????????????????????????'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];  

  static function regist_file_up(){
    $page = 'regist_file_up';
    $form_id = $_POST["form_id"];
    $message = "";

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);


    $user_id = ksk3d_get_current_user_id();
    if (get_user_meta($user_id, "ksk3d_token" ,true) == "1"){

      if ($_FILES["upfilename"]["error"]==0){
        if (is_uploaded_file($_FILES["upfilename"]["tmp_name"])) {
          $file_id = ksk3d_get_max_file_id();
          $upload_dir = ksk3d_upload_dir() ."/" .$file_id;
          if (! file_exists($upload_dir)){
            mkdir($upload_dir);
            chmod($upload_dir, 0777);
          }
          
          $upload_file_name = $upload_dir ."/" .$_FILES["upfilename"]["name"];
          ksk3d_console_log("upload_file_name:".$upload_file_name);
          if (move_uploaded_file($_FILES["upfilename"]["tmp_name"], $upload_file_name)) {
            chmod($upload_file_name, 0777);
          }
          $text .= "?????????????????????????????????????????????<br>";
          $file = ksk3d_format($upload_file_name);
          
          update_user_meta($user_id ,"ksk3d_token" ,"2");

          global $wpdb;
          $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
          $result = $wpdb->insert(
            $tbl_name,
            array(
              'user_id' =>  ksk3d_get_current_user_id(),
              'display_name' =>  $file['title'],
              'file_id' =>  $file_id,
              'file_format' =>  $file['format'],
              'file_name' =>  $_FILES["upfilename"]["name"],
              'file_path' =>  $upload_dir,
              'file_size' =>  $_FILES["upfilename"]["size"],
              'registration_date' =>  current_time('mysql'),
            )
          );
 
          if ($file['format']=='zip'){
            global $wpdb;
            $tbl_name = $wpdb->prefix .static::$tbl;
            $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
            $prepared = $wpdb->prepare($sql, $form_id);
            $rows = $wpdb->get_results($prepared, ARRAY_A);
            $pjt_id = $rows[0][static::$setting['id']];

            $text .= "????????????????????????????????????????????????????????????????????????????????????????????????<br>";

            $text .= ksk3d_fn_proc::registration(
              "??????????????????????????????????????????",
              "ksk3d_functions_pjt::zip_sortingTo",
              4,
              array(
                $upload_file_name,
                $user_id,
                $pjt_id,
                $file_id
              ),
              5,
              1
            );

          } else {
            $message = "???????????????zip????????????????????????????????????????????????";
          }
        } else {
          $message = "??????????????????????????????????????????????????????";
        }
      } else {
        $message = "??????????????????????????????????????????????????????";
      }
    } else {
      $message = "?????????????????????????????????????????????????????????";
    }

    $text .= $message;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$form_id);

    return $text;
  }

  static function detail_disp_maplay($maplay_id){
    global $wpdb;
    $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref[0]['ref'];
    $tbl_ref_on = static::$tbl_ref[0]['on'];
    $wh = "";
    $text =<<< EOL
      <table class="ksk3d_style_table_report">
EOL
;
    $tab = "0";
    $sql = "a.id as id,b.file_name as file_name,b.file_path as file_path,b.file_format as file_format";
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
      }
    }
    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.id={$maplay_id} {$wh};";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql, ARRAY_A);

    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "
      <tr>
        <td>{$list['displayName']}</td>
        <td>{$rows[0][$list['dbField_ref']]}</td>
      </tr>
";
      }
    }
    $text .="
      </table><br>
";
    return array(
      $text,
      $rows[0]
    );
  }


  static function detail_ref2($tab=1){
    $page = 'detail_ref2';
    ksk3d_console_log($page);

    $parrent_id = $_POST["parrent_id"]; 
    $form_id = static::ksk3d_get_form_id($page);  
    ksk3d_console_log("parrent_id:".$parrent_id);
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header(
      $page,
      '',
      array(
        'alert' => 'open_set_attrib',
        'alert_message' => '????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????'
      )
    );

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1($parrent_id);

    $result = static::detail_disp_maplay($form_id);
    $text .= $result[0];
    ksk3d_console_log($result[0]);
    ksk3d_console_log($result[1]);
    
    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">
      <input type="hidden" name="form_id" value="{$form_id}">

EOL
;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function gml2db_set_attrib($flg_file=0){
    $page = 'gml2db_set_attrib';
    ksk3d_console_log("page:".$page);
    $tab = 'gml2db_set_attrib';
    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];
    ksk3d_console_log("parrent_id:".$parrent_id);
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $result = static::detail_disp_maplay($form_id);
    $text .= $result[0];
    ksk3d_console_log($result[0]);
    ksk3d_console_log($result[1]);
    
    global $wpdb;
    $file1 = $result[1]['file_path']."/".$result[1]['file_name'];
    ksk3d_console_log("file:".$file1);

    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(static::$field_ref as $list){
      if ($list['tab']==$tab){
        if ($list['editer']!='hidden'){
          $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }
      }
    }
    $text .= "        </tr>";

    if (isset($_POST["set_attrib"])) {
      $set_attrib = $_POST["set_attrib"];
      ksk3d_console_log($set_attrib);
    } else {
      $set_attrib = [];

      $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;
      $user_id = ksk3d_get_current_user_id();
      $sql = "select id from {$tbl_data} where user_id={$user_id} and file_id={$result[1]['dataset_id']};";
      ksk3d_console_log("sql:".$sql);
      $src_id = ksk3d_fn_db::sel($sql)[0]["id"];
      if ($flg_file==1) {
        $result = ksk3d_citygml_test_onefile($src_id);
      } else {
        $result = ksk3d_citygml_test_all($src_id);
      }
      $sql = "";
      foreach(static::$field_ref as $list){
        if ($list['tab']==$tab){
          if (!empty($list{'dbField'})){
            $sql .= ",".$list{'dbField'};
          }
        }
      }
      $sql_select = substr($sql ,1);
      $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref["{$tab}"]['table'];
      $wh = "";

      $i = 0;
      if (!empty($result)){
        foreach($result as $gml){
          $sql = "SELECT {$sql_select} FROM {$tbl_ref_tbl} WHERE tag_name='{$gml['tag_name']}' {$wh};";
          $rows = $wpdb->get_results($sql, ARRAY_A);
          foreach(static::$field_ref as $list){
            if ($list['tab']==$tab){
              if (empty($list{'gmlValue'}) or ((!empty($list['dbField'])) and (empty($gml[$list['gmlValue']])))){
                $m = $list['dbField'];
                if (count($rows)>0){
                  $v = $rows[0]["{$m}"];
                } else {
                  $v = preg_replace('/\[_n%\]/' ,($i+1) ,$list['default']);
                }
                if ($m == 'attrib_digit'){
                  if ($v < strlen($gml['attrib_value'])*2){
                    $v = round(strlen($gml['attrib_value'])*2 ,-1 ,PHP_ROUND_HALF_UP);
                  }
                }
              } else {
                $m = $list['gmlValue'];
                if (isset($gml["{$m}"])){
                  $v = $gml[$m];
                } else {
                  $v = "";
                }
              }
              $set_attrib[$i]["{$m}"] = $v;
            }
          }
          $i++;
        }
      }
    }
    
    $i = 0;
    $text2 = "";
    foreach($set_attrib as $attrib){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if ($list['tab']==$tab){
          if (empty($list{'gmlValue'}) or ((!empty($list['dbField'])) and (empty($gml[$list['gmlValue']])))){
            $m = $list['dbField'];
            $v = $attrib["{$m}"];
          } else {
            $m = $list['gmlValue'];
            $v = $attrib["{$m}"];
          }
          $v = preg_replace('/\\\\+\'/' ,'\'' ,$v);
          if (!empty($list['select'])){
            $text .= "          <td><select name=\"set_attrib[$i][$m]\">\n";
            $select_option = explode(',' ,$list['select']);
            if (preg_match('/'.$attrib[$m].'/i' ,$list['select'])==1){
              foreach($select_option as $s){
                $test=preg_match('/'.$v.'/i' ,$s);
                if (preg_match('/'.$v.'/i' ,$s)==1){$tmp=" selected";} else {$tmp="";}
                $text .= "            <option value=\"{$s}\"{$tmp}>{$s}</option>\n";
              }
            } else {
              $text .= "            <option value=\"{$attrib[$m]}\">{$attrib[$m]}</option>\n";
            }
            $text .= "          </select></td>\n";
          } else {
            if (isset($list['shortened']) and $list['shortened']==true){
              if (mb_strlen($v)>53){
                $v = mb_substr($v ,0 ,50) ."?????????";
              }
            }

            $v_ = $v;
            if (mb_strlen($v_)>50){$v_ = mb_substr($v_ ,0 ,50) ."?????????";}

            if (preg_match('/disabled="disabled"/i' ,$list['editer'])){
              $text .= "          <td>{$v_}</td>\n";
              $text2 .= "      <input type=\"hidden\" name=\"set_attrib[$i][$m]\" value=\"{$v}\">\n";
            } else if (!empty($list['editer'])){
              $text2 .= "      <input type=\"hidden\" name=\"set_attrib[$i][$m]\" value=\"{$v}\">\n";
            } else {
              $text .= "          <td><input type=\"text\" name=\"set_attrib[$i][$m]\" value=\"{$v}\" {$list['editer']}></td>\n";
            }
          }
        }
      }
      $text .= "        </tr>\n";
      $i++;
    }
    $text .="
        </table>
";

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="set_attrib_ct" value="{$i}">
{$text2}
EOL
;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function gml2DB_check2(){
    $page = 'gml2DB_check2';
    $tab = 'gml2db_set_attrib';
    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];
    ksk3d_console_log("parrent_id:".$parrent_id);
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
    } else {
      $set_attrib = [];
    }
    ksk3d_console_log($set_attrib);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $result = static::detail_disp_maplay($form_id);
    $text .= $result[0];
    ksk3d_console_log($result[0]);
    ksk3d_console_log($result[1]);
    
    global $wpdb;
    $file1 = $result[1]['file_path']."/".$result[1]['file_name'];
    ksk3d_console_log("file:".$file1);

    $text .=<<< EOL
      <p>?????????????????????</p>
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(static::$field_ref as $list){
      if ($list['tab']==$tab){
        if ($list['editer']!='hidden'){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }
      }
    }
    $text .= "        </tr>";

    foreach($set_attrib as $attrib){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if ($list['tab']==$tab){
          if ($list['editer']!='hidden'){
            if (!empty($list['dbField'])){
              $m = $list['dbField'];
              $v = $attrib[$list['dbField']];
            } else {
              $m = $list['gmlValue'];
              $v = $attrib[$list['gmlValue']];
            }
            $v = preg_replace('/\\\\+\'/' ,'\'' ,$v);
            if (preg_match('/disabled="disabled"/i' ,$list['editer'])){
              if (mb_strlen($v)>53){$v = mb_substr($v ,0 ,50) ."?????????";}
            }
            $text .= "          <td>{$v}</td>\n";
          }
        }
      }
      $text .= "        </tr>\n";
    }

    $text .= <<<EOL
        </table>
EOL
;

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">
      <input type="hidden" name="form_id" value="{$form_id}">

EOL
;
    $i = 0;
    foreach($set_attrib as $s){
      foreach(static::$field_ref as $list){
        if ($list['tab']==$tab){
          if (!empty($list['dbField'])){
            $m = $list['dbField'];
          } else {
            $m = $list['gmlValue'];
          }
          $s[$m] = preg_replace('/\\\\+\'/' ,"'" ,$s[$m]);
            $text .= <<< EOL
      <input type="hidden" name="set_attrib[{$i}][{$m}]" value="{$s[$m]}">

EOL
;
        }
      }
      $i++;
    }

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function set_attrib_exec(){
    $page = 'set_attrib_exec';
    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];
    ksk3d_console_log("parrent_id:".$parrent_id);
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
    } else {
      $set_attrib = [];
    }
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);
    $result = static::detail_disp_maplay($form_id);
    ksk3d_console_log($result[0]);
    ksk3d_console_log($result[1]);
    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_citygml::internal" ,array("????????????????????????","????????????????????????") ,$set_attrib);
    $text .= $result[1];

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function set_attrib_bgexec(){
    $page = 'set_attrib_bgexec';
    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];
    ksk3d_console_log("parrent_id:".$parrent_id);
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
    } else {
      $set_attrib = [];
    }
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);
    
    $text .= ksk3d_fn_proc::registration(
      "????????????????????????????????????????????????",
      "ksk3d_functions_visually::set_attrib_bgexec",
      2,
      array(
        $form_id,
        $set_attrib
      ),
      5,
      1
    );

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function ck_logic($form_id=0){
    $page = 'ck_logic';
    $tab = 'ck_logic';

    if ($form_id==0){
      $form_id = static::ksk3d_get_form_id($page);
    }
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);

    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if (($list['tab']==0) or ($list['tab']==$tab)){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";

    $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref[$tab]['table'];
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref[$tab]['ref'];
    $tbl_ref_on = static::$tbl_ref[$tab]['on'];
    $userID = ksk3d_get_current_user_id();
    $tbl_id_name = static::$setting['id'];
    $tbl_id = $rows[0][$tbl_id_name];
    $wh = static::$tbl_ref[$tab]['wh'];
    ksk3d_console_log("tbl_id_name:".$tbl_id_name);
    ksk3d_console_log("tbl_id:".$tbl_id);
    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(array_merge(static::$field_ref ,static::button(static::$button_ref_)) as $list){
      if ($list['tab']==$tab){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>";

    $sql = "a.id as id";
    foreach(static::$field_ref as $list){
      if ($list['tab']==$tab){
        $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
      }
    }

    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN (SELECT * FROM {$tbl_ref_ref} WHERE user_id={$userID} and {$tbl_id_name}={$tbl_id}) b ON {$tbl_ref_on} WHERE {$wh} ORDER BY a.id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql);

    ksk3d_console_log($rows);
    if (count($rows)>0){
      foreach($rows as $row) {
        $text .= "<tr>";
        ksk3d_console_log($row);
        foreach(static::$field_ref as $list){
          if ($list['tab']==$tab){
            $v = $row->{$list['dbField_ref']};
            if ($list['dbField_ref']=='file_size'){$v = ksk3d_sprintf_bytes($v);}
            $text .= "<td style=\"{$list['td-style']}\">{$v}</td>";
          }
        }
        foreach(static::button(static::$button_ref_) as $list){
          if ($list['tab']==$tab){
            $v = static::$button_value[$list['submit']];
            if (empty($list['format'])){
              $disable = "";
              $cls_disable = "";
            } else if (empty($row->file_format)) {
              $disable = "disabled";
              $cls_disable = " btn-secondary";
            } else if (strpos($list['format'], $row->file_format) === false){
              $disable = "disabled";
              $cls_disable = " btn-secondary";
            } else {
              $disable = "";
              $cls_disable = "";
            }
            $text .= "<td style=\"{$list['td-style']}\">
            <input type='submit' name='submit[{$list['submit']}][{$row->id}]'
             class='button-primary{$cls_disable}' value='{$v}' {$disable}/></td>";
          }
        }
        $text .= "</tr>";
      }
    }
    $text .= "</table>";

    $text .= "  <input type=\"hidden\" name=\"parrent_id\" value=\"{$form_id}\">\n";

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$form_id);

    return $text;
  }

  static function ck_logic_check(){
    $page = 'ck_logic_check';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    ksk3d_console_log("form_id:".$form_id);

    global $wpdb;
    $tbl_ck = $wpdb->prefix .static::$tbl_ref['ck_logic']['table'];
    $sql = "SELECT * FROM {$tbl_ck} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $ck_item = $rows[0]['check_item'];
    $ck_target_unit = $rows[0]['target_unit'];
    $ck_target_filter = $rows[0]['target_filter'];
    $ck_mes = $rows[0]['method'];

    $text = static::ksk3d_box_header($page ,"??????????????????".$ck_mes);

    $text .= static::ksk3d_box_main($page);

    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);

    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";

    $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref[0]['ref'];
    $tbl_ref_on = static::$tbl_ref[0]['on'];
    $userID = ksk3d_get_current_user_id();
    $tbl_id_name = static::$setting['id'];
    $tbl_id = $rows[0][$tbl_id_name];
    $wh = "";
    ksk3d_console_log("tbl_id_name:".$tbl_id_name);
    ksk3d_console_log("tbl_id:".$tbl_id);

    $tab = 'ck_logic2|'.$ck_target_unit.'|'.$ck_item;
    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;

    foreach(array_merge(static::$field_ref) as $list){
      if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>";

    $sql = "a.id as id ,b.file_name as file_name ,b.file_path as file_path";
    foreach(static::$field_ref as $list){
      if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
        if (isset($list['dbField'])){
          $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
        }
      }
    }

    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.user_id={$userID} and a.{$tbl_id_name}={$tbl_id} {$wh} ORDER BY a.id;";
ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql);

    ksk3d_console_log($rows);
    if (count($rows)>0){
      foreach($rows as $row) {
        if ($ck_target_unit=='ck_logic2_file'){
          $file1 = $row->{'file_path'}."/".$row->{'file_name'};
          $targets = glob($file1);
          
        } else {
          $targets = [$row->{'file_path'}."/".$row->{'file_name'}];
        }
        foreach ($targets as $target){
          ksk3d_console_log("target:".$target);
          $target = substr($target ,mb_strlen($row->{'file_path'})+1);
        $text .= "<tr>";
        ksk3d_console_log($row);
        foreach(static::$field_ref as $list){
          if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
            if (isset($list['dbField'])){
              $v = $row->{$list['dbField_ref']};
              if ($list['dbField_ref']=='file_size'){
                $v = ksk3d_sprintf_bytes($v);
              } else if ($list['dbField_ref']=='file_name'){
                $v = $target;
              }
            } else {
              $v = "";
            }
            
            $text .= "<td style=\"{$list['td-style']}\">{$v}</td>";
          }
        }
        }
        $text .= "</tr>";
      }
    }
    $text .= "</table>";

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="parrent_id" value="{$parrent_id}">
      <input type="hidden" name="ck_item" value="{$ck_item}">
      <input type="hidden" name="ck_target_unit" value="{$ck_target_unit}">
      <input type="hidden" name="ck_target_filter" value="{$ck_target_filter}">

EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function ck_logic_exec(){
    $page = 'ck_logic_exec';
    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];
    $ck_item = $_POST["ck_item"];
    $ck_target_unit = $_POST["ck_target_unit"];
    $ck_target_filter = $_POST["ck_target_filter"];
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);

    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";

    $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref[0]['ref'];
    $tbl_ref_on = static::$tbl_ref[0]['on'];
    $userID = ksk3d_get_current_user_id();
    $tbl_id_name = static::$setting['id'];
    $tbl_id = $rows[0][$tbl_id_name];
    $wh = "";
    ksk3d_console_log("tbl_id_name:".$tbl_id_name);
    ksk3d_console_log("tbl_id:".$tbl_id);

    $upload_dir = ksk3d_upload_dir();
    $rslt_err = 0;
    $rslt_mes = "";
    $rslt_err_mes = "";
    $takeover = "";

    $tab = 'ck_logic2|'.$ck_target_unit.'|'.$ck_item;
    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;

    foreach(array_merge(static::$field_ref) as $list){
      if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>";

    $sql = "a.id as id ,b.file_name as file_name ,b.file_path as file_path";
    foreach(static::$field_ref as $list){
      if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
        if (isset($list['dbField'])){
          $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
        }
      }
    }

    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.user_id={$userID} and a.{$tbl_id_name}={$tbl_id} {$wh} ORDER BY a.id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql);

    ksk3d_console_log($rows);
    if (count($rows)>0){
      foreach($rows as $row) {
        $rslt_mes .= $row->{'features_name'}."???";
        if ($ck_target_unit=='ck_logic2_file'){
          $file1 = $row->{'file_path'}."/".$row->{'file_name'};
          $targets = glob($file1);
          
        } else {
          $targets = [$row->{'file_path'}."/".$row->{'file_name'}];
        }
        foreach ($targets as $target){
          ksk3d_console_log("target:".$target);
          $target = substr($target ,mb_strlen($row->{'file_path'})+1);
          
          $ck_result = [];
          $ck_result = ksk3d_logic_check(
            $ck_item,
            $row->{'file_path'}."/".$target,
            $ck_target_unit,
            $takeover
          );
          ksk3d_console_log("ck_result");
          ksk3d_console_log($ck_result);
          
          if (preg_match('/^[0-9]+$/' ,$ck_result[0])){
            $rslt_err += $ck_result[0];
            $rslt_err_mes .= substr($row->{'file_path'}."/".$target ,strlen($upload_dir)+1) ."\n" .$ck_result[1];
          }
          if (isset($ck_result[3])){
            $takeover = $ck_result[3];
          }

          $text .= "<tr>";
          ksk3d_console_log($row);
          foreach(static::$field_ref as $list){
            if (preg_match('/^('.$tab.')$/' ,$list['tab'])==1){
              if (isset($list['dbField'])){
                $v = $row->{$list['dbField_ref']};
                if ($list['dbField_ref']=='file_size'){
                  $v = ksk3d_sprintf_bytes($v);
                } else if ($list['dbField_ref']=='file_name'){
                  $v = $target;
                }
              } else {
                $v = $ck_result["{$list['ck_result']}"];
              }
              
              $text .= "<td style=\"{$list['td-style']}\">{$v}</td>";
            }
          }
        }
        $text .= "</tr>";
      }
    }
    $text .= "</table>";

    ksk3d_functions_logic::save($userID ,$tbl_id ,$ck_item ,$rslt_err ,$rslt_mes ,$rslt_err_mes);

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="parrent_id" value="{$parrent_id}">

EOL
;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }


  static function ck_logic_bgexec(){
    $page = 'ck_logic_bgexec';
    $parrent_id = $_POST["parrent_id"];
    global $wpdb;
    $userID = ksk3d_get_current_user_id();

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $tbl_id_name = static::$setting['id'];
    $pjt_id = $rows[0][$tbl_id_name];

    $text .= ksk3d_fn_proc::registration(
      "????????????",
      "ksk3d_functions_logic::bg_exec_group",
      2,
      array(
        $userID,
        $parrent_id
      ),
      5,
      1
    );
    
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function disp_ck_visually_menu(){
    $page = 'disp_ck_visually_menu';
    $tab = 'ck_visually';

    global $wpdb;
    
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref[$tab]['ref'];
    $wh = static::$tbl_ref[$tab]['wh'];

    $text =<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
EOL
;
    $sql = "SELECT * FROM {$tbl_ref_ref} b WHERE {$wh} ORDER BY id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql, ARRAY_A);

    $text .= "        <tr>\n";
    foreach(static::$field_ref as $list){
      if ($list['tab']==$tab){
        if (preg_match('/^[^a]\./' ,$list['dbField'])){
          if (preg_match('/^??????????????????$/' ,$list['displayName'])){
            $list['th-style'] = 'width:450px;';
          }
          $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }
      }
    }
    $text .= "        </tr>\n";

    foreach($rows as $row){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if ($list['tab']==$tab){
        if (preg_match('/^[^a]\./' ,$list['dbField'])){
          $text .= "          <td style=\"{$list['td-style']}\">{$row[$list['dbField_ref']]}</td>\n";
        }}
      }
      $text .= "        </tr>\n";
    }

    $text .="
      </table><br>
";
    return array(
      $text,
      $rows
    );
  }

  static function disp_prj_ckresult($pjt_id ,$tab=""){
    $page = 'disp_ckresult_ckmenu_list';
    if ($tab==""){
      $tab = 'ck_visually';
    }
    $userID = ksk3d_get_current_user_id();
    
    $text = "";
    $ck_result = [];
    $ck_result_ct = 0;

    global $wpdb;
    $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref["{$tab}"]['table'];
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref["{$tab}"]['ref'];
    $tbl_ref_on = static::$tbl_ref["{$tab}"]['on'];
    $wh = static::$tbl_ref["{$tab}"]['wh'];
    if (!empty($wh)){$wh = "and ".$wh;}
    $tbl_id_name = static::$setting['id'];

    $dataset_id = "";
    $dataset_id_bk = "";

    $sql = "a.id as id,a.dataset_id as dataset_id,a.display_name as display_name,a.map_id as map_id,a.attrib_field as attrib_field,a.tag_name as tag_name,a.dataset_id2 as dataset_id2";
    foreach(static::$field_ref as $list){
      if ($list['tab']==$tab){
        $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
      }
    }
    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN (SELECT * FROM {$tbl_ref_ref} WHERE 1 {$wh}) b ON {$tbl_ref_on} WHERE user_id={$userID} and {$tbl_id_name}={$pjt_id} {$wh} ORDER BY a.dataset_id,id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql, ARRAY_A);

    if (count($rows)>0){
      foreach($rows as $row) {
        $dataset_id = $row['dataset_id'];
        if ($dataset_id != $dataset_id_bk){
          if ($dataset_id_bk != ""){
            $text .= "</table>";
          }
          $dataset_id_bk = $dataset_id;
          $text .=<<< EOL
      <h6>{$row['display_name']}</h6>
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
          foreach(array_merge(static::$field_ref ,static::button(static::$button_ref_)) as $list){
            if ($list['tab']==$tab){
              $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
            }
          }
          $text .= "        </tr>";
        }
        
        $text .= "<tr>";
        ksk3d_console_log($row);
        foreach(static::$field_ref as $list){
          if ($list['tab']==$tab){
            $v = $row["{$list['dbField_ref']}"];
            if ($list['dbField_ref']=='file_size'){$v = ksk3d_sprintf_bytes($v);}
            $text .= "<td style=\"{$list['td-style']}\">{$v}</td>";
          }
        }
        foreach(static::button(static::$button_ref_) as $list){
          if ($list['tab']==$tab){
            $v = static::$button_value[$list['submit']];
            if (empty($list['format'])){
              $disable = "";
              $cls_disable = "";
            } else if (empty($row["file_format"])) {
              $disable = "disabled";
              $cls_disable = " btn-secondary";
            } else if (strpos($list['format'], $row["file_format"]) === false){
              $disable = "disabled";
              $cls_disable = " btn-secondary";
            } else {
              $disable = "";
              $cls_disable = "";
            }
            $text .= "<td style=\"{$list['td-style']}\">
            <input type='submit' name='submit[{$list['submit']}][{$row['id']}]'
             class='button-primary{$cls_disable}' value='{$v}' {$disable}/></td>";
          }
        }
        $text .= "</tr>";
      }
      $text .= "</table>";
    }

    return array(
      $text,
      $rows
    );
  }

  static function ck_visually(){
    $page = 'ck_visually';
    $tab = 'ck_visually';
    $userID = ksk3d_get_current_user_id();

    $form_id = static::ksk3d_get_form_id($page);
    $text = static::ksk3d_box_header($page);

    $ck_menu = static::disp_ck_visually_menu();
    $text .= "      <h5>????????????</h5>\n".$ck_menu[0];

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $result = static::disp_prj_ckresult($pjt_id);
    $rows = $result[1];
    ksk3d_console_log($rows);

    if (count($rows)>0){
      $disabled2 = "";
      $disabled3 = "";
      $disabled4 = "";
    } else {
      $disabled2 = " disabled";
      $disabled3 = " disabled";
      $disabled4 = " disabled";
    }

    $text .= <<<EOL
      <h5>????????????</h5>
    <div class="ksk3d_pjt_ck_visually_pre">
      <table class="ksk3d_style_table_list ksk3d_pjt_ck_visually_table">
        <tr>
          <th>??????</th>
          <th>??????</th>
          <th>??????</th>
        </tr>
        <tr>
          <td>???????????????</td>
          <td>????????????????????????????????????????????????</td>
          <td>
            <input type="submit" name="submit[pre_create_map]" class="button-primary" value="???????????????"/>
          </td>
        </tr>
        <tr>
          <td>????????????????????????</td>
          <td>???????????????????????????????????????????????????????????????????????????????????????????????????</td>
          <td>
            <input type="submit" name="submit[pre_convert_data]" class="button-primary" value="????????????????????????" {$disabled2}/>
          </td>
        </tr>
        <tr>
          <td>???????????????</td>
          <td>????????????????????????????????????????????????????????????</td>
          <td>
            <input type="submit" name="submit[pre_setting_style]" class="button-primary" value="???????????????" {$disabled3}/>
          </td>
        </tr>
        <tr>
          <td>??????????????????</td>
          <td>???????????????????????????????????????????????????????????????????????????</td>
          <td>
            <input type="submit" name="submit[pre_create_mesh]" class="button-primary" value="??????????????????" {$disabled4}/>
          </td>
        </tr>
      </table>
    </div>
EOL
;

    $text .= static::ksk3d_box_main($page);
    
    $text .= "<h5>????????????</h5>\n";
    if (count($rows)>0){
      $text .= $result[0];
    } else {
      $text .= "<p>??????????????????????????????????????????</p><br>";
    }


    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$form_id}">

EOL
;

    $text .= SELF::ksk3d_box_footer($page);

    return $text;
  }

  static function disp_prj_data($prj){
    $page = 'disp_prj_data';
    $tab = 'disp_prj_data';
    $parrent_id = $prj;
    $userID = ksk3d_get_current_user_id();

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $tbl_ref_tbl = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $tbl_ref_ref = $wpdb->prefix .static::$tbl_ref[0]['ref'];
    $tbl_ref_on = static::$tbl_ref[0]['on'];
    $wh = "";     
    $text =<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
EOL
;
    $sql = "a.id as id,a.dataset_id2 as dataset_id2,b.file_id as file_id,b.file_name as file_name,b.file_path as file_path,b.file_format as file_format";
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
      }
    }
    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.user_id={$userID} and a." .static::$setting['id'] ."={$pjt_id} {$wh} ORDER BY b.file_id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql, ARRAY_A);

    $text .= "        <tr>\n";
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>\n";

    foreach($rows as $row){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if (($list['tab']=='0') or ($list['tab']==$tab)){
          $text .= "          <td style=\"{$list['td-style']}\">{$row[$list['dbField_ref']]}</td>\n";
        }
      }
      $text .= "        </tr>\n";
    }

    $text .="
      </table><br>
";
    return array(
      $text,
      $rows
    );
  }

  static function datasets_filter($v){return (($v["memo"]=='') and (!empty($v["file_id"])));}
  static function disp_prj_data_cklist($prj ,$tab=""){
    $page = 'disp_prj_data_cklist';
    if ($tab==""){
      $tab = 'disp_prj_data_cklist';
    }
    $userID = ksk3d_get_current_user_id();
    
    $ck_result = [];
    $ck_result_ct = 0;
    
    $result = static::disp_prj_data($prj);
    $datasets = array_filter($result[1], "static::datasets_filter");
    ksk3d_console_log("datasets");
    ksk3d_console_log($datasets);


    $result = static::disp_ck_visually_menu();
    $ck_menus = $result[1];
    ksk3d_console_log("ck_menus");
    ksk3d_console_log($ck_menus);

    global $wpdb;
    $tbl_attrib = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;

    $text = "";
    foreach($datasets as $dataset){
      $text .= <<<EOL
      <h6>{$dataset['features_name']}</h6>
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
      foreach(static::$field_ref as $list){
        if ($list['tab']==$tab){
          $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }
      }
      $text .= "        </tr>";
    
      foreach($ck_menus as $ck_menu){
        if ($ck_menu['check_item']=='C04'){
          $attribs = [
            array(
              'attrib_field' => '1',
              'attrib_name' => '????????????????????????',
              'tag_name' => 'gen:value'
            )
          ];
        } else {
          $sql =<<< EOL
SELECT *,file_id as dataset_id2
FROM {$tbl_attrib}
WHERE user_id={$userID} and file_id={$dataset['dataset_id2']} and {$ck_menu['target_filter']}
ORDER BY attrib_id
EOL
;
          ksk3d_console_log("sql:".$sql);
          $attribs = $wpdb->get_results($sql, ARRAY_A);
          ksk3d_console_log("attribs");
          ksk3d_console_log($attribs);
        }

        if (!empty($attribs)){
          foreach($attribs as $attrib){
            $text .= "        <tr>";
            foreach(static::$field_ref as $list){
              if ($list['tab']==$tab){
                if ($list['variable1']=='ck_menu'){
                  $v = $ck_menu["{$list['variable2']}"];
                } else if ($list['variable1']=='attrib'){
                  $v = $attrib["{$list['variable2']}"];
                }
                $text .= "          <td style=\"{$list['td-style']}\">{$v}</td>\n";
              }
            }
            $text .= "        </tr>";
            $attrib['file_id'] = $dataset['file_id'];
            $ck_result[$ck_result_ct] = array_merge($dataset ,$ck_menu ,$attrib);
            $ck_result_ct++;
          }
        } else {
          $text .= "        <tr>";
          foreach(static::$field_ref as $list){
            if ($list['tab']==$tab){
              if ($list['variable1']=='ck_menu'){
                $v = $ck_menu["{$list['variable2']}"];
              } else if ($list['variable1']=='attrib'){
                $v = "??????????????????????????????";
              }
              $text .= "          <td style=\"{$list['td-style']}\">{$v}</td>\n";
            }
          }
          $text .= "        </tr>";
        }
      }
      $text .= "      </table><br>\n";
    }

    return array(
      $text,
      $ck_result
    );
  }

  static function pre_create_map(){
    $page = 'pre_create_map';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $text = static::ksk3d_box_header($page);

    $result = static::disp_prj_data($parrent_id);

    if (!count($result[1])>0){
      $ck_disabled = " disabled";
      $text .= "??????????????????????????????????????????????????????<br>
?????????????????????????????????????????????????????????????????????????????????????????????????????????<br>";
    } else {
      $ck_disabled = "";
      $text .= $result[0];
    }

    $text .= static::ksk3d_box_main($page);

    $userID = ksk3d_get_current_user_id();

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">

EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function pre_create_map_check(){
    $page = 'pre_create_map_check';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $user_id = ksk3d_get_current_user_id();
    
    $text = static::ksk3d_box_header($page);

    $result = static::disp_prj_data_cklist($parrent_id);
    $text .= $result[0];

    $text .= static::ksk3d_box_main($page);

    $ck_disabled="";
    
    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">

EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id ,$ck_disabled);

    return $text;
  }

  static function pre_create_map_exec(){
    $page = 'pre_create_map_exec';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $user_id = ksk3d_get_current_user_id();
    
    $text = static::ksk3d_box_header($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    ksk3d_console_log("pjt_id:".$pjt_id);

    $table_map =  $wpdb->prefix .KSK3D_TABLE_MAP;
    $map_id = ksk3d_get_max($table_map ,"map_id");

    $tbl = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;
    $sql = "DELETE FROM {$table_map} WHERE user_id={$user_id} and map_id in (SELECT map_id FROM {$tbl} WHERE user_id={$user_id} and pjt_id={$pjt_id} and map_id>0);";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);
    
    $tbl_maplay = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;
    $sql = "DELETE FROM {$tbl_maplay} WHERE user_id={$user_id} and map_id in (SELECT map_id FROM {$tbl} WHERE user_id={$user_id} and pjt_id={$pjt_id} and map_id>0);";
    ksk3d_log("sql:" .$sql);
    $wpdb->query($sql);
    
    $tbl_menu = $wpdb->prefix .KSK3D_TABLE_CHK_MENU;
    $sql = "DELETE FROM {$tbl} WHERE user_id={$user_id} and pjt_id={$pjt_id} and check_item in (SELECT check_item FROM {$tbl_menu} WHERE `type`='visually');";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);

    $result = static::disp_prj_data_cklist($parrent_id);
    $maps = $result[1];

    $sql = "INSERT INTO {$tbl} (
      user_id,
      pjt_id,
      check_item,
      dataset_id,
      display_name,
      tag_name,
      attrib_field,
      attrib_name,
      map_id
    )
    VALUES";

    $sql_map = "INSERT INTO {$table_map} (
      user_id,
      map_id,
      display_name,
      registration_date
    )
    VALUES";
    
    foreach($maps as $map){
      $sql .= "\n(
        {$user_id},
        {$pjt_id},
        '{$map['check_item']}',
        '{$map['file_id']}',
        '{$map['features_name']}',
        '{$map['tag_name']}',
        '{$map['attrib_field']}',
        '{$map['attrib_name']}',
        {$map_id}
      ),";

      $sql_map .= "\n(
        {$user_id},
        {$map_id},
        '{$map['check_item']}/{$map['features_name']}/{$map['attrib_name']}',
        CURRENT_TIMESTAMP
      ),";

      $map_id++;
    }
    $sql = substr($sql ,0 ,-1).";";
    ksk3d_log("sql:".$sql);
    $wpdb->query($sql);

    $sql_map = substr($sql_map ,0 ,-1).";";
    ksk3d_log("sql:".$sql_map);
    $wpdb->query($sql_map);


    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function pre_convert_data(){
    $page = 'pre_convert_data';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $text = static::ksk3d_box_header($page);

    $result = static::disp_prj_data($parrent_id);
    $text .= $result[0];

    $text .= static::ksk3d_box_main($page);

    $userID = ksk3d_get_current_user_id();

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">

EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function pre_convert_data_bgexec(){
    $page = 'pre_convert_data_bgexec';
    $parrent_id = $_POST["parrent_id"];
    $userID = ksk3d_get_current_user_id();
    
    $text = static::ksk3d_box_header($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $result = static::disp_prj_data($parrent_id);
    $datasets = array_filter($result[1], "static::datasets_filter");
    ksk3d_console_log($datasets);
    foreach($datasets as $dataset){
      $text .= ksk3d_fn_proc::registration(
        "????????????????????????",
        "ksk3d_functions_visually::pre_convert_data_bgexec",
        3,
        array(
          $userID,
          $pjt_id,
          $dataset
        ),
        150,
        60
      );
    }


    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function pre_setting_style(){
    $page = 'pre_setting_style';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $text = static::ksk3d_box_header($page);


    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $result = static::disp_prj_ckresult($pjt_id ,"pre_setting_style");

    $maps = $result[1];
    $disable = "";
    foreach($maps as $map){
      if (!$map['dataset_id2']>0){
        $text .= "????????????????????????????????????????????????????????????????????????????????????????????????????????????<br>\n";
        $disable = "disabled";
        break;
      }
    }
    if ($disable == ""){
      $text .= $result[0];
    }
      
    $text .= static::ksk3d_box_main($page);

    $userID = ksk3d_get_current_user_id();

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">

EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id ,$disable);

    return $text;
  }

  static function pre_setting_style_exec(){
    $page = 'pre_setting_style_exec';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $userID = ksk3d_get_current_user_id();

    $text = static::ksk3d_box_header($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $tbl_maplay = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;
    $tbl_attribute = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;

    $result = static::disp_prj_ckresult($pjt_id ,"pre_setting_style");
    $maps = $result[1];

    foreach($maps as $map){
      $tbl_pjtdata = $wpdb->prefix .KSK3D_TABLE_PJT_DATA;
      $sql = "SELECT dataset_id2 FROM {$tbl_pjtdata} WHERE user_id={$userID} and pjt_id={$pjt_id} and dataset_id={$map['dataset_id']}";
      ksk3d_console_log("sql:".$sql);
      $id2 = $wpdb->get_var($sql);
      $tbl_attrib = KSK3D_TABLE_ATTRIB .$userID ."_" .$id2;
        if (preg_match('/^??????????????????????????????/' ,$map['style'])){
        $sql = "SELECT DISTINCT({$map['attrib_field']}) as v FROM {$tbl_attrib} ORDER BY v";
        $rows = $wpdb->get_results($sql, ARRAY_A);
        if (count($rows)>30){
          $text .= $map['attrib_name']." ???????????????30????????????????????????????????????????????????<br>\n";
        } else {
          $color = "{'color':";
          $fill_tra = 1;
          if (count($rows)>0){
            $color .= "{'conditions':[";
            
            for($i=0; $i<count($rows); $i++){
              if ($i<5){
                $r = ksk3d_sampleStyles::$color_range[$i]['r'];
                $g = ksk3d_sampleStyles::$color_range[$i]['g'];
                $b = ksk3d_sampleStyles::$color_range[$i]['b'];
              } else {
                $r = rand(0,255);
                $g = rand(0,255);
                $b = rand(0,255);
              }

              $fill_tra2 = $fill_tra;
              if (empty($rows[$i]['v'])){
                $rows[$i]['v'] = "null";
              } else {
                $rows[$i]['v'] = "[&squot]".$rows[$i]['v']."[&squot]";
              }
              $color .= '['
                ."'".'${'.$map['attrib_name']. "} === ".$rows[$i]['v']."'"
                .",'rgba({$r},{$g},{$b},{$fill_tra2})'"
                ."],";
            }
            $color .= "[true,'rgba(128,128,128,0.5)']]}";
            
          } else {
            $color .= "'rgba(128,128,128,0.5)'";
          }
          $color .= "}";
          ksk3d_console_log("color:".$color);
        }
      } else if (preg_match('/^????????????????????????????????????/' ,$map['style'])){
        $sql = "SELECT attrib_type FROM {$tbl_attribute} WHERE user_id={$userID} and file_id={$id2} and attrib_field='{$map['attrib_field']}'";
        ksk3d_console_log("sql:".$sql);

        $rows = $wpdb->get_results($sql, ARRAY_A);
        $attrib_type = $rows[0]['attrib_type'];

        $sql = "SELECT MIN({$map['attrib_field']}) as min,MAX({$map['attrib_field']}) as max FROM {$tbl_attrib}";
        $rows = $wpdb->get_results($sql, ARRAY_A);
        ksk3d_console_log($rows);
        $min = $rows[0]['min'];
        $max = $rows[0]['max'];
        
        if ($max==$min){
          $style_split = 1;
        } else if (preg_match('/int/i' ,$attrib_type) and ($max-$min<5)){
          $style_split = $max-$min;
        } else {
          $style_split = 5;
        }
        $fill_tra2 = 0.8;
        $color = "{'color':{'conditions':[";
        $color .= "['" .'${'.$map['attrib_name']. "} === null','rgba(128,128,128,".$fill_tra2.")']";
        $fill_tra = 1;

        for($i=0; $i<$style_split; $i++){
          $fill_tra2 = $fill_tra;
          $condition2 = strval(($max-$min)/$style_split*($style_split-$i-1)+$min);
          if (preg_match('/int/i' ,$attrib_type)){$condition2 = floor($condition2);}
          $color .= ",['" .'${' 
            .$map['attrib_name']
            . "} >= " 
            .$condition2 
            ."','rgba("
              .ksk3d_sampleStyles::$color_range[$i]['r'].","
              .ksk3d_sampleStyles::$color_range[$i]['g'].","
              .ksk3d_sampleStyles::$color_range[$i]['b'].","
              .$fill_tra2
            .")']";
        }
        $color .= ']}}';
      } else {
        $color = "";
      }

      if (!empty($color)){
        $color2 = preg_replace("/'/" ,"\'" ,$color);
        $sql = "UPDATE {$tbl_maplay} SET color_exp='{$color2}' WHERE user_id={$userID} and map_id={$map['map_id']} and layer_id=1;";
        ksk3d_log( "sql:" .$sql );
        $wpdb->query($sql);
      }
    }

    $text .= static::ksk3d_box_main($page);

    $userID = ksk3d_get_current_user_id();

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function pre_create_mesh(){
    $page = 'pre_create_mesh';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $text = static::ksk3d_box_header($page);

    $result1 = static::disp_prj_data($parrent_id);

    $result2 = static::disp_prj_ckresult($pjt_id ,"pre_setting_style");
    $maps = $result2[1];

    $disable = "";
    foreach($maps as $map){
      if (empty($map['map_id'])){
        $text .= "?????????????????????????????????????????????????????????????????????<br>\n";
        $disable = "disabled";
        break;
      }
      if (empty($map['dataset_id2'])){
        $text .= "?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????<br>\n";
        $disable = "disabled";
        break;
      }
    }
    if (empty($disable)){
      $text .= $result1[0];
      $text .= $result2[0];
    }

    $text .= static::ksk3d_box_main($page);

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">

EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id, $disable);

    return $text;
  }

  static function pre_create_mesh_exec(){
    $page = 'pre_create_mesh_exec';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $userID = ksk3d_get_current_user_id();
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();  
    $user_id = ksk3d_get_current_user_id();  
    $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;

    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $pjt_id = $rows[0][static::$setting['id']];
    $result = static::disp_prj_ckresult($pjt_id);
    $rows = $result[1];
    $file_id_array = [];
    $map_id_array = [];
    foreach($rows as $row){
      ksk3d_console_log("row:".$row['dataset_id'].",".$row['map_id']);
      
      $file_id = $row['dataset_id'];
      $file_id_array = ksk3d_array_push($file_id_array ,$file_id);
      if (!isset($map_id_array[$file_id])){$map_id_array[$file_id]=[];}
      $map_id_array[$file_id] = ksk3d_array_push($map_id_array[$file_id] ,$row['map_id']);
    }
    
    $file_id_array = ksk3d_array_unique($file_id_array);
    ksk3d_console_log($file_id_array);
    ksk3d_console_log($map_id_array);

    foreach ($file_id_array as $file_id){
      $src_id = $wpdb->get_var("SELECT id FROM {$tbl_data} WHERE user_id={$user_id} and file_id={$file_id};");

      ksk3d_console_log("dataset:".$userID.",". $src_id.",map_id_array[{$file_id}]");
      ksk3d_console_log($map_id_array[$file_id]);
      $text .= ksk3d_functions_visually::pre_create_mesh_exec($userID, $src_id, $map_id_array[$file_id]);
    }

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function map_view(){
    $page = 'map_view';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    $userID = ksk3d_get_current_user_id();

    global $wpdb;
    $tbl_ck_result = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;
    $sql = "SELECT map_id FROM {$tbl_ck_result} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $mapID = $wpdb->get_var($prepared);

    $tbl_map = $wpdb->prefix .KSK3D_TABLE_MAP;
    $sql = "SELECT display_name FROM {$tbl_map} WHERE user_id={$userID} and map_id=%d;";
    $prepared = $wpdb->prepare($sql, $mapID);
    $display_name = $wpdb->get_var($prepared);


    $text = static::ksk3d_box_header($page, $display_name);

    $text .= static::ksk3d_box_main($page);

    $dataID = "";
    $map_url = KSK3D_MAP_URL; 
    $text .=<<< EOL
    <input type="hidden" id="userID" value="{$userID}">
    <input type="hidden" id="mapID" value="{$mapID}">
    <input type="hidden" id="dataID" value="{$dataID}">
    <iframe id="ksk3d_map_mapview"
      title="ksk3d_map_mapview"
      src="{$map_url}" allow="fullscreen" >
    </iframe><br>
EOL
;

    $tbl_rslt = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;
    $sql = "SELECT times FROM {$tbl_rslt} WHERE id={$form_id};";
    $rows = $wpdb->get_results($sql);
    if (count($rows)>0){
      $rslt_ct = $rows[0]->{'times'}+1;
      $wpdb->update( 
        $tbl_rslt,
        array( 
          'times' =>  $rslt_ct,
          'registration_date' =>  current_time('mysql')
        ), 
        array(
          'id' => $form_id
        ),
        array(
          '%d',
          '%s',
          '%d',
          '%s'
        ), 
        array(
          '%d'
        )
      );
    }
    
    $tbl_log = $wpdb->prefix .KSK3D_TABLE_CHK_LOG;
    $sql = "INSERT INTO {$tbl_log}(
      user_id,
      pjt_id,
      check_item,
      times,
      registration_date,
      dataset_id,
      display_name,
      file_name,
      attrib_name,
      map_id
      )
      SELECT 
      user_id,
      pjt_id,
      check_item,
      times,
      registration_date,
      dataset_id,
      display_name,
      file_name,
      attrib_name,
      map_id
    FROM {$tbl_rslt}
    WHERE id={$form_id}";
    ksk3d_log("sql:".$sql);
    $wpdb->query($sql);

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }
  
  static function delete_exec(){
    $page = 'delete_exec';

    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $id = static::$setting['id'];
    $sql = "SELECT {$id} FROM {$tbl_name} WHERE id = %d;";
    $pjt_id = $wpdb->get_var($wpdb->prepare($sql, $form_id));

    $userID = ksk3d_get_current_user_id();

    $sql = $wpdb->prepare("DELETE FROM {$tbl_name} WHERE id = %d;", $form_id);
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    $tbl_name = $wpdb->prefix .KSK3D_TABLE_PJT_DATA;
    $sql = "DELETE FROM {$tbl_name} WHERE user_id={$userID} and {$id}={$pjt_id};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    $table_map = $wpdb->prefix .KSK3D_TABLE_MAP;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;
    $sql = "DELETE FROM {$table_map} WHERE user_id={$userID} and map_id in (SELECT map_id FROM {$tbl_name} WHERE user_id={$userID} and pjt_id={$pjt_id} and map_id>0);";
    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);
    
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_CHK_RESULT;
    $sql = "DELETE FROM {$tbl_name} WHERE user_id={$userID} and {$id}={$pjt_id};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);

    $tbl_name = $wpdb->prefix .KSK3D_TABLE_CHK_LOG;
    $sql = "DELETE FROM {$tbl_name} WHERE user_id={$userID} and {$id}={$pjt_id};";
    ksk3d_log("sql:".$sql);
    $dlt = $wpdb->query($sql);
    
    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }



}