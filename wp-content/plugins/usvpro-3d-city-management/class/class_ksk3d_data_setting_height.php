<?php
class ksk3d_data_setting_height extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_DATA;
  static $tbl_ref = [
    array(
      'table' => KSK3D_TABLE_ATTRIBUTE,
      'on' => 'a.file_id = b.file_id and a.user_id=b.user_id',
      'wh' => ''
    ),
  ];
  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_data.css'
    ],
    'id' => 'file_id',
    'where_format' => "^内部データセット$"
  ];

  static function view2(){
    if (isset($_POST["submit"]["setting_feature_height"])) {
      return static::setting_feature_height();
    } else if (isset($_POST["submit"]["setting_feature_height_check"])) {
      return static::setting_feature_height_check();
    } else if (isset($_POST["submit"]["setting_feature_height_exec"])) {
      return static::setting_feature_height_exec();
    } else if (isset($_POST["submit"]["setting_feature_height_bgexec"])) {
      return static::setting_feature_height_bgexec();
    } else if (isset($_POST["submit"]["setting_elevation"])) {
      return static::setting_elevation();
    } else if (isset($_POST["submit"]["setting_elevation_check"])) {
      return static::setting_elevation_check();
    } else if (isset($_POST["submit"]["setting_elevation_exec"])) {
      return static::setting_elevation_exec();
    } else if (isset($_POST["submit"]["setting_elevation_bgexec"])) {
      return static::setting_elevation_bgexec();
    }
    return static::disp();
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
    'detail' => '詳細',
    'setting_feature_height' => '地物高さを設定',
    'setting_feature_height_check' => '地物高さの設定内容を確認',
    'setting_feature_height_exec' => '地物高さの設定実行',
    'setting_feature_height_bgexec' => 'バックグランドで実行',
    'setting_elevation' => '標高を設定',
    'setting_elevation_check' => '標高の設定内容を確認',
    'setting_elevation_exec' => '標高の設定実行',
    'setting_elevation_bgexec' => 'バックグランドで実行',
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'setting_feature_height',
      'displayName' => '地物高さを設定',
      'th-style' => 'width:160px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    ),array(
      'tab' => '1',
      'submit' => 'setting_elevation',
      'displayName' => '標高を設定',
      'th-style' => 'width:160px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    )
  ];

  static $post = [
    'disp' => [
      'header-title' => 'データセット一覧',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_feature_height' => [
      'header-title' => '地物高さを設定',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_feature_height_check' => [
      'header-title' => '地物高さの設定内容を確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_feature_height_exec' => [
      'header-title' => '地物高さの設定実行',
      'header-text' => '',
      'main-text' => '実行しました',
      'footer-text' => ''
    ],
    'setting_feature_height_bgexec' => [
      'header-title' => '地物高さの設定（バックグラウンド処理）',
      'header-text' => '地物高さの設定について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_elevation' => [
      'header-title' => '標高を設定',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_elevation_check' => [
      'header-title' => '標高の設定内容を確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_elevation_exec' => [
      'header-title' => '標高の設定実行',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'setting_elevation_bgexec' => [
      'header-title' => '標高の設定（バックグラウンド処理）',
      'header-text' => '標高の設定について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $header_button = [
    'disp' => [
    ],
    'setting_feature_height' => [
    ],
    'setting_feature_height_check' => [
    ],
    'setting_feature_height_exec' => [
    ],
    'setting_feature_height_bgexec' => [
    ],
    'setting_elevation' => [
    ],
    'setting_elevation_check' => [
    ],
    'setting_elevation_exec' => [
    ],
    'setting_elevation_bgexec' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'setting_feature_height' => [
      array(
        'submit' => 'setting_feature_height_check',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_feature_height_check' => [
      array(
        'submit' => 'setting_feature_height_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'setting_feature_height',
        'class' => 'btn-secondary',
        'display' => '設定画面に戻る'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_feature_height_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_feature_height_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_elevation' => [
      array(
        'submit' => 'setting_elevation_check',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_elevation_check' => [
      array(
        'submit' => 'setting_elevation_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'setting_elevation',
        'class' => 'btn-secondary',
        'display' => '設定画面に戻る'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_elevation_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'setting_elevation_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];
  
  static function setting_feature_height(){
    $page = 'setting_feature_height';

    if (isset($_POST["set_high"])) {
      $form_id = $_POST["form_id"];
      $set_high = $_POST["set_high"];
      ksk3d_console_log($set_high);
    } else {
      $form_id = static::ksk3d_get_form_id($page);
      $set_high = [
        'menu' => 1,
        'value1_field' => '',
        'value1_times' => 1,
        'value2' => 8
      ];
    }

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1($form_id);

    $userID = ksk3d_get_current_user_id();

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $tbl_ref = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $tbl_on = static::$tbl_ref[0]['on'];
    
    $sql = "SELECT b.* FROM {$tbl_name} a LEFT OUTER JOIN {$tbl_ref} b ON {$tbl_on} WHERE a.user_id={$userID} and a.id = %d ORDER BY b.attrib_id ;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $set_attrib = $wpdb->get_results($prepared ,ARRAY_A);
    ksk3d_console_log("sql:".$prepared);

    $text_select = "";
    $text_field_value = "";
    foreach ($set_attrib as $attrib){
      ksk3d_console_log($attrib);
      if (preg_match("/int|double/i" ,$attrib['attrib_type'])==1){
        if (empty($set_high['value1_field'])){
          $set_high['value1_field'] = $attrib['attrib_field'];
        }
        if ($set_high['value1_field'] == $attrib['attrib_field']){
          $tmp_ck = "selected";
        } else {
          $tmp_ck = "";
        }
        $text_select .= "            <option value = \"{$attrib['attrib_field']}\" {$tmp_ck}>{$attrib['attrib_name']}</option>\n";

        $text_field_value .= <<<EOL
        <input type="hidden" name="set_high[attib_value][{$attrib['attrib_field']}]" value="{$attrib['attrib_name']}">
EOL
;

      }
    }

    if ((empty($text_select)) and ($set_high["menu"] == 1)) {
      $set_high["menu"] = 2;
    }
    $radio_ck = [
      0 => '',
      1 => '',
      2 => ''
    ];
    $radio_ck[$set_high["menu"]] = "checked=\"checked\"";

    if (empty($text_select)){
      $text .= <<< EOL
        <input type="radio" name="set_high[menu]" value="1" disabled="disabled">高さを設定する（属性値から設定）<br>
          <p>INT型、またはDOUBLE型の属性がないため、選択できません。</p>
          <table class="ksk3d_style_table_list">
            <tr><th>属性項目</th><th>倍率</th></tr>
            <tr><td></td><td><input type="text" name="set_high[value1_times]" value="{$set_high['value1_times']}" disabled="disabled"></td></tr>
          </table>
          <input type="hidden" name="set_high[value1_field]" value="">
          <input type="hidden" name="set_high[value1_times]" value="{$set_high['value1_times']}">
EOL
;
    } else {
      $text .= <<< EOL
    <input type="radio" name="set_high[menu]" value="1" {$radio_ck[1]}>高さを設定する（属性値から設定）<br>
          <table class="ksk3d_style_table_list">
            <tr><th>属性項目</th><th>倍率</th></tr>
            <tr><td>
              <select name= "set_high[value1_field]">
{$text_select}
              </select></td>
            <td><input type="number" name="set_high[value1_times]" value="{$set_high['value1_times']}"></td></tr>
          </table>
EOL
;
    }
    
    $text .= <<< EOL
        <br>
        <input type="radio" name="set_high[menu]" value="2" {$radio_ck[2]}>高さを設定する（固定値）<br>
          <table class="ksk3d_style_table_list">
            <tr><th>設定値</th></tr>
            <tr><td><input type="number" name="set_high[value2]" min="0" step="any" value="{$set_high['value2']}"></td></tr>
          </table>
EOL
;

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
{$text_field_value}
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function setting_feature_height_check(){
    $page = 'setting_feature_height_check';

    $form_id = $_POST["form_id"];
    $set_high = $_POST["set_high"];
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1( $form_id );
    
    if ($set_high['menu']==1){
      $mes_high1 = "地物を立ち上げる（属性値から設定）";
      $mes_high2 = $set_high['attib_value']["{$set_high['value1_field']}"] ."　×" .$set_high['value1_times'] ."倍";
    } else if ($set_high['menu']==2){
      $mes_high1 = "地物を立ち上げる（固定値）";
      $mes_high2 = $set_high['value2'];
    } else {
      $mes_high1 = "高さを設定しない";
      $mes_high2 = "";
    }

    $text .= <<<EOL
        </table>
      <br>
      <p>高さの設定内容</p>
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr><th>設定方法</th><th>設定パラメータ</th></tr>
        <tr><td>{$mes_high1}</td><td>{$mes_high2}</td></tr>
      </table>
      
EOL
;

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="set_high[menu]" value="{$set_high['menu']}">
      <input type="hidden" name="set_high[value1_field]" value="{$set_high['value1_field']}">
      <input type="hidden" name="set_high[value1_times]" value="{$set_high['value1_times']}">
      <input type="hidden" name="set_high[value2]" value="{$set_high['value2']}">

EOL
;
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function setting_feature_height_exec(){
    $page = 'setting_feature_height_exec';
    $form_id = $_POST["form_id"];
    $set_high = $_POST["set_high"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    ksk3d_functions_internal::update_feature_height($form_id, $set_high);

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function setting_feature_height_bgexec(){
    $page = 'setting_feature_height_bgexec';
    $form_id = $_POST["form_id"];
    $set_high = $_POST["set_high"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= ksk3d_fn_proc::registration(
      "地物高さの設定",
      "ksk3d_functions_internal::update_feature_height",
      2,
      array(
        $form_id,
        $set_high
      ),
      5,
      1
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
  static function setting_elevation(){
    $page = 'setting_elevation';

    if (isset($_POST["set_high"])) {
      $form_id = $_POST["form_id"];
      $set_high = $_POST["set_high"];
      ksk3d_console_log($set_high);
    } else {
      $form_id = static::ksk3d_get_form_id($page);
      $set_high = [
        'menu' => 1,
        'value1_field' => '',
        'value1_times' => 1,
        'value2' => 8
      ];
    }

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1($form_id);

    $userID = ksk3d_get_current_user_id();

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $tbl_ref = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $tbl_on = static::$tbl_ref[0]['on'];
    
    $sql = "SELECT b.* FROM {$tbl_name} a LEFT OUTER JOIN {$tbl_ref} b ON {$tbl_on} WHERE a.user_id={$userID} and a.id = %d ORDER BY b.attrib_id ;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $set_attrib = $wpdb->get_results($prepared ,ARRAY_A);
    ksk3d_console_log("sql:".$prepared);

    $text_select = "";
    $text_field_value = "";
    foreach ($set_attrib as $attrib){
      ksk3d_console_log($attrib);
      if (preg_match("/int|double/i" ,$attrib['attrib_type'])==1){
        if (empty($set_high['value1_field'])){
          $set_high['value1_field'] = $attrib['attrib_field'];
        }
        if ($set_high['value1_field'] == $attrib['attrib_field']){
          $tmp_ck = "selected";
        } else {
          $tmp_ck = "";
        }
        $text_select .= "            <option value = \"{$attrib['attrib_field']}\" {$tmp_ck}>{$attrib['attrib_name']}</option>\n";

        $text_field_value .= <<<EOL
        <input type="hidden" name="set_high[attib_value][{$attrib['attrib_field']}]" value="{$attrib['attrib_name']}">
EOL
;

      }
    }

    if ((empty($text_select)) and ($set_high["menu"] == 1)) {
      $set_high["menu"] = 2;
    }
    $radio_ck = [
      0 => '',
      1 => '',
      2 => ''
    ];
    $radio_ck[$set_high["menu"]] = "checked=\"checked\"";

    if (empty($text_select)){
      $text .= <<< EOL
        <input type="radio" name="set_high[menu]" value="1" disabled="disabled">高さを設定する（属性値から設定）<br>
          <p>INT型、またはDOUBLE型の属性がないため、選択できません。</p>
          <table class="ksk3d_style_table_list">
            <tr><th>属性項目</th><th>倍率</th></tr>
            <tr><td></td><td><input type="text" name="set_high[value1_times]" value="{$set_high['value1_times']}" disabled="disabled"></td></tr>
          </table>
          <input type="hidden" name="set_high[value1_field]" value="">
          <input type="hidden" name="set_high[value1_times]" value="{$set_high['value1_times']}">
EOL
;
    } else {
      $text .= <<< EOL
    <input type="radio" name="set_high[menu]" value="1" {$radio_ck[1]}>高さを設定する（属性値から設定）<br>
          <table class="ksk3d_style_table_list">
            <tr><th>属性項目</th><th>倍率</th></tr>
            <tr><td>
              <select name= "set_high[value1_field]">
{$text_select}
              </select></td>
            <td><input type="number" name="set_high[value1_times]" value="{$set_high['value1_times']}"></td></tr>
          </table>
EOL
;
    }
    
    $text .= <<< EOL
        <br>
        <input type="radio" name="set_high[menu]" value="2" {$radio_ck[2]}>高さを設定する（固定値）<br>
          <table class="ksk3d_style_table_list">
            <tr><th>設定値</th></tr>
            <tr><td><input type="number" name="set_high[value2]" min="0" step="any" value="{$set_high['value2']}"></td></tr>
          </table>
EOL
;

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
{$text_field_value}
EOL
;
    
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
  static function setting_elevation_check(){
    $page = 'setting_elevation_check';

    $form_id = $_POST["form_id"];
    $set_high = $_POST["set_high"];
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1( $form_id );
    
    if ($set_high['menu']==1){
      $mes_high1 = "標高を設定（属性値から設定）";
      $mes_high2 = $set_high['attib_value']["{$set_high['value1_field']}"] ."　×" .$set_high['value1_times'] ."倍";
    } else if ($set_high['menu']==2){
      $mes_high1 = "標高を設定（固定値）";
      $mes_high2 = $set_high['value2'];
    } else {
      $mes_high1 = "高さを設定しない";
      $mes_high2 = "";
    }

    $text .= <<<EOL
        </table>
      <br>
      <p>高さの設定内容</p>
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr><th>設定方法</th><th>設定パラメータ</th></tr>
        <tr><td>{$mes_high1}</td><td>{$mes_high2}</td></tr>
      </table>
      
EOL
;

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="set_high[menu]" value="{$set_high['menu']}">
      <input type="hidden" name="set_high[value1_field]" value="{$set_high['value1_field']}">
      <input type="hidden" name="set_high[value1_times]" value="{$set_high['value1_times']}">
      <input type="hidden" name="set_high[value2]" value="{$set_high['value2']}">

EOL
;
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
  static function setting_elevation_exec(){
    $page = 'setting_elevation_exec';
    $form_id = $_POST["form_id"];
    $set_high = $_POST["set_high"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $result = ksk3d_functions_internal::update_elevation($form_id ,$set_high);

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
  static function setting_elevation_bgexec(){
    $page = 'setting_elevation_bgexec';
    $form_id = $_POST["form_id"];
    $set_high = $_POST["set_high"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= ksk3d_fn_proc::registration(
      "標高の設定",
      "ksk3d_functions_internal::update_elevation",
      2,
      array(
        $form_id,
        $set_high
      ),
      5,
      1
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
}