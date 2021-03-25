<?php
class ksk3d_data_gml extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_DATA;
  static $tbl_ref = [
    'gml2db_set_attrib'=>array(
      'table' => KSK3D_TABLE_ATTRIBUTE_TPL_VALUE,
      'wh' => ''
    ),
  ];
  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_data.css'
    ],
    'id' => 'file_id',
    'where_format' => "^gml$"
  ];

  static function view2(){
    if (isset($_POST["submit"]["dataset2DB_check"])) {
      return static::dataset2DB_check();
    } else if (isset($_POST["submit"]["gml2db_set_attrib"])) {
      return static::gml2db_set_attrib();
    } else if (isset($_POST["submit"]["gml2DB_check2"])) {
      return static::gml2DB_check2();
    } else if (isset($_POST["submit"]["gml2DB_exec"])) {
      return static::gml2DB_exec();
    } else if (isset($_POST["submit"]["gml2DB_bgexec"])) {
      return static::gml2DB_bgexec();
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
    'dataset2DB_check' => '内部データセットへ変換',
    'gml2db_set_attrib' => '属性項目の設定',
    'gml2DB_check2' => '内部データセットへ変換確認',
    'gml2DB_exec' => '内部データセットへ変換実行',
    'gml2DB_bgexec' => 'バックグランドで実行',
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'dataset2DB_check',
      'displayName' => '内部データセットへ変換',
      'th-style' => 'width:240px;',
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
    'dataset2DB_check' => [
      'header-title' => '内部データセットへ変換確認',
      'header-text' => '次のデータセットを内部データセットへ変換します。よろしければ実行ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2db_set_attrib' => [
      'header-title' => '属性項目の設定',
      'header-text' => '属性項目を設定してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2DB_check2' => [
      'header-title' => '内部データセットへ変換確認',
      'header-text' => '次の設定で内部データセットへ変換します。よろしければ実行ボタンを押してください',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2DB_exec' => [
      'header-title' => '内部データセットへ変換実行',
      'header-text' => 'データセットを内部データセットへ変換を実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2DB_bgexec' => [
      'header-title' => '内部データセットへ変換実行（バックグラウンド処理）',
      'header-text' => 'データセットを内部データセットへ変換について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $field_ref = [
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'タグ名称',
      'gmlValue' => 'tag_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'tag_path',
      'gmlValue' => 'tag_path',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'tag_attrib',
      'gmlValue' => 'tag_attrib',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'tag_attrib_name',
      'gmlValue' => 'tag_attrib_name',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'フィールド名',
      'gmlValue' => 'field_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '型',
      'dbField' => 'attrib_type',
      'gmlValue' => 'attrib_type',
      'default' => 'VARCHAR',
      'editer' => '',
      'select' => 'VARCHAR,INT,DOUBLE',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '桁',
      'dbField' => 'attrib_digit',
      'default' => '100',
      'editer' => '',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '属性名称',
      'dbField' => 'attrib_name',
      'gmlValue' => 'attrib_name',
      'default' => '属性[_n%]',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '単位',
      'dbField' => 'attrib_unit',
      'gmlValue' => 'attrib_unit',
      'default' => '',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => '属性値（サンプル）',
      'gmlValue' => 'attrib_value',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'gml2db_set_attrib',
      'displayName' => 'コード',
      'gmlValue' => 'codelist',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    )
  ];

  static $header_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
    ],
    'gml2db_set_attrib' => [
    ],
    'gml2DB_check2' => [
    ],
    'gml2DB_exec' => [
    ],
    'gml2DB_bgexec' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
      array(
        'submit' => 'gml2db_set_attrib',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'gml2db_set_attrib' => [
      array(
        'submit' => 'gml2DB_check2',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'gml2DB_check2' => [
      array(
        'submit' => 'gml2DB_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'gml2db_set_attrib',
        'class' => 'btn-secondary',
        'display' => '属性の設定に戻る'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'gml2DB_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'gml2DB_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ]
  ];

  static function gml2db_set_attrib(){
    $page = 'gml2db_set_attrib';
    $tab = 'gml2db_set_attrib';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1($form_id);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;

    $sql = "SELECT file_path,file_name,file_format FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    $file1 = $result['file_path']."/".$result['file_name'];
    ksk3d_console_log("file:".$file1);

    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(static::$field_ref as $list){
      if (($list['tab']==0) or ($list['tab']==$tab)){
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

      $result = ksk3d_functions_gml::test($file1);
      $sql = "";
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
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
            if (($list['tab']==0) or ($list['tab']==$tab)){
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
        if (($list['tab']==0) or ($list['tab']==$tab)){
          if (empty($list{'gmlValue'}) or ((!empty($list['dbField'])) and (empty($gml[$list['gmlValue']])))){
            $m = $list['dbField'];
            $v = $attrib["{$m}"];
          } else {
            $m = $list['gmlValue'];
            $v = $attrib["{$m}"];
          }
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
            $v_ = $v;
            if (mb_strlen($v_)>50){$v_ = mb_substr($v_ ,0 ,50) ."・・・";}

            if ($list['editer']=='disabled="disabled"'){
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
      <input type="hidden" name="form_id" value="{$form_id}">
{$text2}
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }


  static function gml2DB_check2(){
    $page = 'gml2DB_check2';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
    } else {
      $set_attrib = [];
    }
    ksk3d_console_log($set_attrib);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if ($list['tab']==0){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";

    $text .=<<< EOL
      <p>属性の設定内容</p>
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(static::$field_ref as $list){
      if (($list['tab']==0) or ($list['tab']==$tab)){
        if ($list['editer']!='hidden'){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }
      }
    }
    $text .= "        </tr>";

    foreach($set_attrib as $attrib){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          if ($list['editer']!='hidden'){
            if (!empty($list['dbField'])){
              $m = $list['dbField'];
              $v = $attrib[$list['dbField']];
            } else {
              $m = $list['gmlValue'];
              $v = $attrib[$list['gmlValue']];
            }
            if ($list['editer']=='disabled="disabled"'){
              if (mb_strlen($v)>50){$v = mb_substr($v ,0 ,50) ."・・・";}
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
      <input type="hidden" name="form_id" value="{$form_id}">

EOL
;
    $i = 0;
    foreach($set_attrib as $s){
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          if (!empty($list['dbField'])){
            $m = $list['dbField'];
          } else {
            $m = $list['gmlValue'];
          }
          $s[$m] = preg_replace('/\\\'/' ,'\'' ,$s[$m]);
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

  static function gml2DB_exec(){
    $page = 'gml2DB_exec';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
    } else {
      $set_attrib = [];
    }

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_gml::internal" ,array("内部データセット","内部データセット") ,$set_attrib);
    $text .= $result[1];

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function gml2DB_bgexec(){
    $page = 'gml2DB_bgexec';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
    } else {
      $set_attrib = [];
    }

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= ksk3d_fn_proc::registration(
      "GMLを内部データセットに変換",
      "ksk3d_conv",
      5,
      array(
        $form_id,
        "",
        "ksk3d_functions_gml::internal",
        array("内部データセット","内部データセット"),
        $set_attrib
      ),
      5,
      1
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

}