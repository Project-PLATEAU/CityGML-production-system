<?php
class ksk3d_data_internal_ksk extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_DATA;

  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_data.css'
    ],
    'id' => 'file_id',
    'where_format' => "^内部データセット$"
  ];

  static function view2(){
    if (isset($_POST["submit"]["dataset2DB_check"])) {
      return static::dataset2DB_check();
    } else if (isset($_POST["submit"]["internal2ksk_edit"])) {
      return static::internal2ksk_edit();
    } else if (isset($_POST["submit"]["internal2ksk_check"])) {
      return static::internal2ksk_check();
    } else if (isset($_POST["submit"]["internal2ksk_exec"])) {
      return static::internal2ksk_exec();
    } else if (isset($_POST["submit"]["internal2ksk_bgexec"])) {
      return static::internal2ksk_bgexec();
    } else {
      return static::disp();
    }
  }
  
  static $tab = [ 
    array(
      'tab' => '1',
      'displayName' => '1概要'
    )
  ];
  
  static $field = [
    array(
      'tab' => '0',
      'displayName' => 'データセットID',
      'dbField' => 'file_id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => 'データセット名',
      'dbField' => 'display_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => '1',
      'displayName' => 'フォーマット',
      'dbField' => 'file_format',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    )
  ];

  static $button_value = [
    '' => '一覧に戻る',
    'dataset2DB_check' => '3Dグラフに集計',
    'internal2ksk_edit' => '集計の設定',
    'internal2ksk_check' => '設定の確認',
    'internal2ksk_exec' => '3Dグラフに集計実行',
    'internal2ksk_bgexec' => 'バックグランドで実行'
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'dataset2DB_check',
      'displayName' => '3Dグラフに集計',
      'th-style' => 'width:160px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    )
  ];

  static $post = [
    'disp' => [
      'header-title' => 'データセット一覧（内部データセットを3Dグラフに集計）',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'dataset2DB_check' => [
      'header-title' => 'データセットの確認（内部データセットを3Dグラフに変換）',
      'header-text' => '次のデータセットについて、3Dグラフに変換します。よろしければ設定ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2ksk_edit' => [
      'header-title' => '集計の設定（内部データセットを3Dグラフに変換）',
      'header-text' => '集計の設定を行い、設定後、確認ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2ksk_check' => [
      'header-title' => '設定の確認（内部データセットを3Dグラフに変換）',
      'header-text' => '集計に関する設定内容を確認して、実行ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2ksk_exec' => [
      'header-title' => '内部データセットを3Dグラフに変換実行',
      'header-text' => '内部データセットを3Dグラフに変換を実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2ksk_bgexec' => [
      'header-title' => '内部データセットを3Dグラフに変換実行（バックグラウンド）',
      'header-text' => '内部データセットを3Dグラフに変換について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $header_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
    ],
    'internal2ksk_edit' => [
    ],
    'internal2ksk_check' => [
    ],
    'internal2ksk_exec' => [
    ],
    'internal2ksk_bgexec' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
      array(
        'submit' => 'internal2ksk_edit',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'internal2ksk_edit' => [
      array(
        'submit' => 'internal2ksk_check',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'internal2ksk_check' => [
      array(
        'submit' => 'internal2ksk_bgexec',
        'class' => 'btn-primary'
      ),
       array(
        'submit' => 'internal2ksk_edit',
        'class' => 'btn-secondary',
        'display' => '集計の設定に戻る'
      ),
     array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'internal2ksk_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'internal2ksk_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];

  static function internal2ksk_edit(){
    $page = 'internal2ksk_edit';
    ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    if (isset($_POST["set_mesh"])){
      $set_mesh = $_POST["set_mesh"];
    } else {
      $set_mesh = [
        'high_attrib'=>'',
        'high_times'=>1,
        'meshcode'=>3
      ];
    }
    ksk3d_console_log($set_mesh);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $data_info = ksk3d_fn_db::sel_data($form_id);
    $attribs = ksk3d_functions_internal::sel_attrib("" ,$data_info['file_id']);
    $attribs = array_filter($attribs ,"ksk3d_dataset_internal::attributes_filter_int_or_double");
    $htm_select = "";
    $attrib_high = [];
    foreach($attribs as &$attrib){
      if ($set_mesh['high_attrib'] == "{$attrib['attrib_field']}"){$selected = " selected";} else {$selected = "";}
      $htm_select .= "<option value=\"{$attrib['attrib_field']}\" {$selected}>{$attrib['attrib_name']}の合計</option>";
      $attrib_high["{$attrib['attrib_field']}"] = "{$attrib['attrib_name']}の合計";
      
      $attrib['field'] = "round(sum({$attrib['attrib_field']}),6) as {$attrib['attrib_field']}";
      $attrib['attrib_name'] = $attrib['attrib_name']."の合計";
      if (preg_match('/int/i' ,$attrib['attrib_type'])==1){
        $attrib['tag_name'] = "gen:intAttribute/gen:value";
      } else {
        $attrib['tag_name'] = "gen:doubleAttribute/gen:value";
      }
    }
    if (empty($attribs)){
      $disable = "disabled";
      $htm_select = "数値型の属性項目がみつかりません。";
    } else {
      $disable = "";
      $htm_select = "<select name=\"set_mesh[high_attrib]\">".$htm_select."</select>";
    }
    
    $attrib_mesh = [
      2=>'2次メッシュ',
      3=>'3次メッシュ',
      4=>'4次メッシュ'
    ];

    foreach($attrib_mesh as $key=>$v){
      $v_meshcode[$key] = "";
    }
    $v_meshcode[$set_mesh['meshcode']] = " selected";

    $text .= <<<EOL
      <table class="ksk3d_style_table_report">
        <tr>
          <td>高さ</td>
          <td>{$htm_select}</td>
        </tr>
        <tr>
          <td>高さの倍率</td>
          <td>
            <input type="number" name="set_mesh[high_times]" step="any" value="{$set_mesh['high_times']}">
          </td>
        </tr>
        <tr>
          <td>色</td>
          <td>色はマップの機能で設定します</td>
        </tr>
        <tr>
          <td>メッシュコード</td>
          <td>
            <select name="set_mesh[meshcode]">
              <option value="2" {$v_meshcode[2]}>2次メッシュ</option>
              <option value="3" {$v_meshcode[3]}>3次メッシュ</option>
              <option value="4" {$v_meshcode[4]}>4次メッシュ</option>
            </select>
          </td>
        </tr>
      </table>
    
EOL
;

    $text .= static::ksk3d_box_footer($page,"","","",$disable,array("form_id","attrib_high","attrib_mesh","attrib"),array($form_id,$attrib_high,$attrib_mesh,$attribs));

    return $text;
  }

  static function internal2ksk_check(){
    $page = 'internal2ksk_check';
    ksk3d_console_log("page:".$page);
    $tab = 'internal2ksk_check';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_mesh = $_POST["set_mesh"];
    ksk3d_console_log("set_mesh");
    ksk3d_console_log($set_mesh);
    $attrib_high = $_POST["attrib_high"];
    $attrib_mesh = $_POST["attrib_mesh"];
    $attrib = $_POST["attrib"];
    ksk3d_console_log($attrib);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= <<<EOL
      <table class="ksk3d_style_table_report">
        <tr>
          <td>高さ</td>
          <td>{$attrib_high[$set_mesh['high_attrib']]}</td>
        </tr>
        <tr>
          <td>高さの倍率</td>
          <td>{$set_mesh['high_times']}</td>
        </tr>
        <tr>
          <td>色</td>
          <td>色はマップの機能で設定します</td>
        </tr>
        <tr>
          <td>メッシュコード</td>
          <td>{$attrib_mesh[$set_mesh['meshcode']]}</td>
        </tr>
      </table>
    
EOL
;

    $text .= static::ksk3d_box_footer($page,"","","","",array("form_id","set_mesh","attrib_high","attrib"),array($form_id,$set_mesh,$attrib_high,$attrib));

    return $text;
  }

  static function internal2ksk_exec(){
    $page = 'internal2ksk_exec';
    ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_mesh = $_POST["set_mesh"];
    ksk3d_console_log("set_mesh");
    ksk3d_console_log($set_mesh);
    $attrib_high = $_POST["attrib_high"];
    $attrib = $_POST["attrib"];
    ksk3d_console_log($attrib);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $user_id = ksk3d_get_current_user_id();
    global $wpdb;
    $tbl_data = $wpdb->prefix .KSK3D_TABLE_DATA;

    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_internal::attrib_agg_ksk" ,array("3Dグラフ","内部データセット","内部データセット") ,$attrib ,$set_mesh);
    $intermediate1 = $result[0];
    $text .= $result[1];

    $set_feature = [
      'template'=>"building",
      'filename'=>'[meshcode]_bldg_6697.gml',
      'feature'=>'bldg:Building',
      'lod'=>'bldg:lod1Solid',
      'geom'=>'gml:Solid',
      'srs'=>'WGS84',
      'mesh'=>1
    ];
    $set_attrib = ksk3d_functions_internal::sel_attrib($user_id ,$intermediate1);
    ksk3d_console_log("set_attrib");
    ksk3d_console_log($set_attrib);
    
    $filename = preg_replace('/\[meshcode\]/' ,'*' ,$set_feature['filename']);
    $result = ksk3d_fn_db::sel("select id from {$tbl_data} where user_id={$user_id} and file_id={$intermediate1};");
    $result = ksk3d_conv($result[0]['id'] ,"" ,"ksk3d_functions_internal::export_mesh_citygml" ,array("citygml","CityGML",$filename) ,"" ,array($set_feature,$set_attrib));
    $intermediate2 = $result[0];
    $text .= $result[1];


    $result = ksk3d_citygml23DTiles_ex($intermediate2, false, "", 1000);
    $text .= $result[0];

    $wpdb->query("delete from {$tbl_data} where user_id={$user_id} and file_id in ({$intermediate1},{$intermediate2})");

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function internal2ksk_bgexec(){
    $page = 'internal2ksk_bgexec';
    ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_mesh = $_POST["set_mesh"];
    ksk3d_console_log("set_mesh");
    ksk3d_console_log($set_mesh);
    $attrib_high = $_POST["attrib_high"];
    $attrib = $_POST["attrib"];
    ksk3d_console_log($attrib);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $user_id = ksk3d_get_current_user_id();

    $text .= ksk3d_fn_proc::registration(
      "3Dグラフ生成",
      "ksk3d_functions_internal::export_ksk_bg",
      5,
      array(
        $user_id,
        $form_id,
        $set_mesh,
        $attrib_high,
        $attrib
      ),
      150,
      60
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

}