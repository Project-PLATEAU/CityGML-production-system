<?php
class ksk3d_op_dataset extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_DATA;
  static $tbl_ref = [
    'open_set_attrib'=>array(
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
    'where_format' => "CityGML"
  ];

  static function view2(){
    if (isset($_POST["submit"]["open_check"])) {
      return static::open_check();
    } else if (isset($_POST["submit"]["open_set_attrib"])) {
      return static::open_set_attrib();
    } else if (isset($_POST["submit"]["open_set_attrib_fromonefile"])) {
      return static::open_set_attrib(1);
    } else if (isset($_POST["submit"]["open_set_attrib_fromallfile"])) {
      return static::open_set_attrib(2);
    } else if (isset($_POST["submit"]["open_check2"])) {
      return static::open_check2();
    } else if (isset($_POST["submit"]["open_set_rule1"])) {
      return static::open_set_rule1();
    } else if (isset($_POST["submit"]["open_set_rule1_-1"])) {
      return static::open_set_rule1(-1);
    } else if (isset($_POST["submit"]["open_set_rule1_1"])) {
      return static::open_set_rule1(1);
    } else if (isset($_POST["submit"]["open_check_rule1"])) {
      return static::open_check_rule1();
    } else if (isset($_POST["submit"]["open_exec"])) {
      return static::open_exec();
    } else if (isset($_POST["submit"]["open_bgexec"])) {
      return static::open_bgexec();
    } else {
      return static::disp();
    }
  }
  
  static $tab = [//0:常時表示 //ksk3d_data::viewは直接修正が必要
    array(
      'tab' => '1',
      'displayName' => '1概要'
    )
  ];
  
  static $field = [//editerに文字列が入ると更新不可
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

  //ボタンの処理と表示名は統一しておかないとエラー
  static $button_value = [
    '' => '一覧に戻る',
    'open_check' => 'オープンデータ化加工',
    'open_set_attrib' => 'オープンデータ化のルール設定',
    'open_set_attrib_fromallfile' => '全てのファイルからタグを抽出',
    'open_set_attrib_fromonefile' => '1ファイルからタグを抽出',
    'open_check2' => '設定内容の確認',
    'open_set_rule1' => 'ルールの詳細設定',
    'open_check_rule1' => 'ルールの詳細設定確認',
    'open_exec' => '公開用データの生成',
    'open_bgexec' => 'バックグランドで実行',
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'open_check',
      'displayName' => 'オープンデータ化加工',
      'th-style' => 'width:240px;',
      'td-style' => 'text-align:center',
      'format' => 'CityGML|CityGML(iur)',
      'status' => ''
    )
  ];

  static $post = [
    //一覧表
    'disp' => [
      'header-title' => 'データセット一覧',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    //オープンデータ化加工
    'open_check' => [
      'header-title' => 'オープンデータ化加工',
      'header-text' => '次の3D都市モデルのファイルについて、公開用データを生成します。<br>
まずは、公開用データのルール設定のため、タグを抽出します。<br>
全てのファイルからタグを抽出する場合、ファイル数に応じて表示に時間がかかります。<br>
よろしければタグの抽出ボタンを押してください。
',
      'main-text' => '',
      'footer-text' => ''
    ],
    //オープンデータ化のルール設定
    'open_set_attrib_fromonefile' => [
      'header-title' => 'オープンデータ化のルール設定（1ファイルから抽出）',
      'header-text' => '各属性項目について、オープンデータ化のルールを設定してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //オープンデータ化のルール設定
    'open_set_attrib_fromallfile' => [
      'header-title' => 'オープンデータ化のルール設定（全てのファイルから抽出）',
      'header-text' => '各属性項目について、オープンデータ化のルールを設定してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //オープンデータ化のルール設定
    'open_set_attrib' => [
      'header-title' => 'オープンデータ化のルール設定',
      'header-text' => '各属性項目について、オープンデータ化のルールを設定してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //設定内容の確認
    'open_check2' => [
      'header-title' => '設定内容の確認',
      'header-text' => '次のルールに従って、公開用データを出力します。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //設定内容の確認
    'open_set_rule1' => [
      'header-title' => 'ルールの詳細設定（公開用データの生成）',
      'header-text' => 'ルールの詳細設定を行ってください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //設定内容の確認
    'open_check_rule1' => [
      'header-title' => 'ルールの詳細設定確認（公開用データの生成）',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    //公開用データの出力
    'open_exec' => [
      'header-title' => '公開用データの生成実行',
      'header-text' => '3D都市モデルのファイルについて、公開用データを生成しました。<br>
      データセット一覧からダウンロードください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //公開用データの出力（バックグラウンド処理）
    'open_bgexec' => [
      'header-title' => '公開用データの生成実行（バックグラウンド処理）',
      'header-text' => '公開用データの生成について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $field_ref = [//editerに文字列が入ると更新不可
    array(
      'id' => 'rule',
      'tab' => 'open_set_attrib',
      'displayName' => 'オープンデータ化ルール',
      'default' => '何もしない',
      'select' => '何もしない,削除,四捨五入,階級区分',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
/*
    array(
      'id' => 'round',
      'tab' => 'open_set_attrib',
      'displayName' => '四捨五入する場合の位',
      'default' => '100',
      'editer' => 'hidden',
      'format' => '%d',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'rank',
      'tab' => 'open_set_attrib',
      'displayName' => '階級区分する場合の区分数',
      'default' => '5',
      'editer' => 'hidden',
      'format' => '%d',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
*/
    array(
      'id' => 'rule_id',
      'tab' => 'open_set_attrib',
      'displayName' => '階級区分する場合の設定値のID',
      'default' => '',
      'editer' => 'hidden',
      'format' => '%d',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'tag_name',
      'tab' => 'open_set_attrib',
      'displayName' => 'タグ名称',
      'gmlValue' => 'tag_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'tag_path',
      'tab' => 'open_set_attrib',
      'displayName' => 'tag_path',
      'gmlValue' => 'tag_path',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'tag_attrib',
      'tab' => 'open_set_attrib',
      'displayName' => 'tag_attrib',
      'gmlValue' => 'tag_attrib',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'tag_attrib_name',
      'tab' => 'open_set_attrib',
      'displayName' => 'tag_attrib_name',
      'gmlValue' => 'tag_attrib_name',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'attrib_type',
      'tab' => 'open_set_attrib',
      'displayName' => '型',
      'dbField' => 'attrib_type',
      'gmlValue' => 'attrib_type',
      'default' => 'VARCHAR',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'attrib_name',
      'tab' => 'open_set_attrib',
      'displayName' => '属性名称',
      'dbField' => 'attrib_name',
      'gmlValue' => 'attrib_name',
      'default' => '[_tag_name]',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:180px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'attrib_unit',
      'tab' => 'open_set_attrib',
      'displayName' => '単位',
      'dbField' => 'attrib_unit',
      'gmlValue' => 'attrib_unit',
      'default' => '',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'attrib_value',
      'tab' => 'open_set_attrib',
      'displayName' => '属性値（サンプル）',
      'gmlValue' => 'attrib_value',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:180px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'id' => 'codelist',
      'tab' => 'open_set_attrib',
      'displayName' => 'コード',
      'gmlValue' => 'codelist',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:180px;',
      'td-style' => '',
      'status' => ''
    )
  ];

  static $header_button = [
    'disp' => [//一覧表
    ],
    'open_check' => [//オープンデータ化加工
    ],
    'open_set_attrib' => [//オープンデータ化のルール設定
    ],
    'open_check2' => [//設定内容の確認
    ],
    'open_set_rule1' => [//ルールの詳細設定（公開用データの生成）
    ],
    'open_check_rule1' => [//ルールの詳細設定（公開用データの生成）
    ],
    'open_exec' => [//公開用データの出力
    ],
    'open_bgexec' => [//バックグランドで実行
    ]
  ];

  static $footer_button = [
    'disp' => [//一覧表
    ],
    'open_check' => [//オープンデータ化加工
      array(
        'submit' => 'open_set_attrib_fromallfile',
        'class' => 'btn-primary',
        'onclick' => 'myform.ksk3d_key.value="open_set_attrib"'
      ),
      array(
        'submit' => 'open_set_attrib_fromonefile',
        'class' => 'btn-primary',
        'onclick' => 'myform.ksk3d_key.value=""'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
        'onclick' => 'myform.ksk3d_key.value=""'
      )
    ],
    'open_set_attrib' => [//オープンデータ化のルール設定
      array(
        'submit' => 'open_check2',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'open_check2' => [//設定内容の確認
/*
      array(
        'submit' => 'open_exec',
        'class' => 'btn-primary',
      ),
*/
      array(
        'submit' => 'open_set_rule1',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'open_set_attrib',
        'class' => 'btn-secondary',
        'display' => 'オープンデータ化のルール設定に戻る'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'open_set_rule1' => [//ルールの詳細設定（公開用データの生成）
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'open_check_rule1' => [//ルールの詳細設定（公開用データの生成）
      array(
        'submit' => 'open_bgexec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => 'open_check2',
        'class' => 'btn-secondary',
        'display' => 'ルールの確認に戻る'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'open_exec' => [//公開用データの出力
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'open_bgexec' => [//バックグランドで実行
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ]
  ];

/**
  * 属性一覧の保存
  */
  static function view_send_attrib($set_attrib){
    $text = '';
    $i = 0;
    foreach($set_attrib as $s){
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          $m = $list['id'];
          $v = preg_replace("/\\\'/" ,"'" ,$s[$m]);
//          echo "$s[$m]<br>";
          //ksk3d_console_log($set_attrib[$i][$m]);
//          if (isset($s[$m])){
            $text .= <<< EOL
      <input type="hidden" name="set_attrib[{$i}][{$m}]" value="{$v}">

EOL
;
//          }
        }
      }
      $i++;
    }
    return $text;
  }

/**
  * オープンデータ化加工確認
  */
  static function open_check(){
    $page = 'open_check';

//確認画面-新規、追記の確認、新しくデータセット一覧に追加

    //押されたボタンのIDを取得する
    $form_id = static::ksk3d_get_form_id($page);

    //ヘッダーボックス
    $text = static::ksk3d_box_header(
      $page,
      '',
      array(
        'alert' => 'open_set_attrib',
        'alert_message' => '次のページの表示は全てのファイルからタグを抽出するため、時間がかかります。ブラウザを閉じないようにしてください。'
      )
    );

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //データ2列表示
    $text .= static::detail_disp( $form_id );

    //form_id
    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

/**
  * オープンデータ化のルール設定
  */
  static function open_set_attrib($flg_file=0){
    $page = 'open_set_attrib';
    $tab = 'open_set_attrib';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //メインボックス、ヘッダー
    $text .= static::ksk3d_box_main_info1($form_id);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;

    //ファイル名
    $sql = "SELECT file_path,file_name,file_format FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    $file1 = $result['file_path']."/".$result['file_name'];
    ksk3d_console_log("file:".$file1);

    //メインボックス、一覧表(項目)
    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
//    foreach(array_merge(static::$field_ref ,static::button(static::$button_ref_)) as $list){
    foreach(static::$field_ref as $list){
      if (($list['tab']==0) or ($list['tab']==$tab)){
        if ($list['editer']!='hidden'){
          //Undefined offsetエラーが発生するとき、configファイルのFIELD,BUTTONのtab設定がTABの配列を超えている可能性がある
          $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }
      }
    }
    $text .= "        </tr>";

    //ksk3d_console_log("test7");

    //メインボックス、一覧表(内容)
    if (isset($_POST["set_attrib"])) {//設定値あり
      $set_attrib = ksk3d_stripslashes_deep($_POST["set_attrib"]);
      ksk3d_console_log($set_attrib);
    } else {
      //ksk3d_console_log("新規");
      $set_attrib = [];

      //CityGML分析
      //$result = ksk3d_citygml_test($file1);
      if ($flg_file==1) {
        $result = ksk3d_citygml_test_onefile($form_id);
      } else {
        $result = ksk3d_citygml_test_all($form_id);
      }
      ksk3d_console_log("test11");
      ksk3d_console_log($result);
      for ($i=0; $i<count($result); $i++){
        if (
          (isset($result[$i]['attrib_type']) && (preg_match('/geometry/i' ,$result[$i]['attrib_type'])==1))
          or (!empty($result[$i]['tag_attrib']) && (empty($result[$i]['tag_attrib_name'])))
        ){
          ksk3d_console_log("array_splice");
          array_splice($result ,$i ,1);
          //ksk3d_console_log($i);
          //ksk3d_console_log($result);
          $i--;
        }
      }
      ksk3d_console_log("test12");
      ksk3d_console_log($result);

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
      $attrib_n = 0;
      $attrib_name = [];

      //メインボックス、一覧表(データ)
      $i = 0;
      foreach($result as $gml){
        $sql = "SELECT {$sql_select} FROM {$tbl_ref_tbl} WHERE tag_name='{$gml['tag_name']}' {$wh};";
        ksk3d_console_log("sql:".$sql);
        $rows = $wpdb->get_results($sql, ARRAY_A);
        ksk3d_console_log($rows);
        //echo "test614<br>";
        foreach(static::$field_ref as $list){
          if (($list['tab']==0) or ($list['tab']==$tab)){
            if (!empty($list{'gmlValue'}) && (
              !empty($gml[$list['gmlValue']])
//              or (isset($gml[$list['gmlValue']]) && ($gml[$list['gmlValue']] == 0))
            )){
              //gmlValue
              $v = $gml["{$list['gmlValue']}"];
            } else if (!empty($list{'dbField'}) && isset($rows[0]["{$list['dbField']}"])){
              //dbField
              $v = $rows[0]["{$list['dbField']}"];
            } else if (isset($list{'default'})){
              //default
              $v = $list{'default'};
              if (preg_match('/\[_n%\]/', $v)==1){
                $attrib_n++;
                $v = preg_replace('/\[_n%\]/', $attrib_n, $v);
              }
              if (preg_match('/\[_tag_name\]/', $v)==1){
                $v = preg_replace('/\[_tag_name\]/', $gml['tag_name'], $v);
                $v = preg_replace('/\:/', '_', $v);
                if (isset($attrib_name[$v])){
                  $attrib_name[$v]++;
                  $v .= $attrib_name[$v];
                } else {
                  $attrib_name[$v]=1;
                }
              }
            } else {
              $v = "";
            }
            $set_attrib[$i]["{$list['id']}"] = $v;
          }
        }
        $i++;
      }
    }
    
    //メインボックス、一覧表(内容表示)
    $i = 0;
    $text2 = "";
    foreach($set_attrib as $attrib){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          $m = $list['id'];
          $v = $attrib["{$list['id']}"];
          //Firefoxはselectの初期値に対応していない
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
              //ksk3d_console_log($text);
          } else {
            $v_ = $v;
            if (mb_strlen($v_)>50){$v_ = mb_substr($v_ ,0 ,50) ."・・・";}

            if ($list['editer']=='disabled="disabled"'){
              $text .= "          <td>{$v_}</td>\n";
              $text2 .= "      <input type=\"hidden\" name=\"set_attrib[$i][$m]\" value=\"{$v}\">\n";
            } else if (!empty($list['editer'])){
              $text2 .= "      <input type=\"hidden\" name=\"set_attrib[$i][$m]\" value=\"{$v}\">\n";
            } else {
              $text .= "          <td><input type=\"tel\" id=\"form-ticker-symbol\" name=\"set_attrib[$i][$m]\" value=\"{$v}\" {$list['editer']}></td>\n";
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
    if (count($set_attrib)>0){
      $ck_disabled = "";
    } else {
      $text .="設定可能な属性項目が見つかりません。<br>";
      $ck_disabled = " disabled";
    }

ksk3d_console_log("test3");
    //form_id
    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="set_attrib_ct" value="{$i}">
{$text2}

EOL
;

    //フッターボックス
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,"" ,$ck_disabled);

    return $text;
  }

/**
  * 設定内容の確認
  */
  static function open_check2(){
    $page = 'open_check2';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = ksk3d_stripslashes_deep($_POST["set_attrib"]);
    } else {
      $set_attrib = [];
    }
    ksk3d_console_log($set_attrib);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //メインボックス、ヘッダー
    //$text .= ksk3d_box_main_info1( $form_id );
    
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

    //属性設定表示
    //メインボックス、一覧表(項目)
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

    //メインボックス、一覧表(内容表示)
    foreach($set_attrib as $attrib){
      $text .= "        <tr>\n";
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          if ($list['editer']!='hidden'){
            $m = $list['id'];
            $v = $attrib["{$m}"];
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

    //設定項目の配列作成
    $rule_ct=0;
    $rule=[];
    foreach(['四捨五入','階級区分'] as $rule1){
      $id=0;
      foreach($set_attrib as $attrib){
        if ($attrib['rule']==$rule1){
          $set_attrib[$id]['rule_id'] = $rule_ct;
          $text .= <<< EOL
      <input type="hidden" name="rule[{$rule_ct}][id]" value="{$id}">
      <input type="hidden" name="rule[{$rule_ct}][rule]" value="{$rule1}">
      <input type="hidden" name="rule[{$rule_ct}][round]" value="100">
      <input type="hidden" name="rule[{$rule_ct}][divisions]" value="5">
      <input type="hidden" name="rule[{$rule_ct}][name]" value="{$attrib['attrib_name']}">

EOL
;
          $rule_ct++;
        }
        $id++;
      }
    }
    $text .= <<< EOL
      <input type="hidden" name="rule_ct" value="{$rule_ct}">
      <input type="hidden" name="rule_i" value="0">

EOL
;


    //form_id
    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">

EOL
;

          //echo "test6<br>";

    //属性一覧の保存
    $text .= static::view_send_attrib($set_attrib);

    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

/**
  * ルールの詳細設定（公開用データの生成）
  */
  static function open_set_rule1($progress=0){
    $page = 'open_set_rule1';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    if (isset($_POST["set_attrib"])){
      $set_attrib = ksk3d_stripslashes_deep($_POST["set_attrib"]);
    } else {
      $set_attrib = [];
    }
    ksk3d_console_log($set_attrib);

    if (isset($_POST["rule"])){
      $rule = $_POST["rule"];
    } else {
      $rule = [];
    }

      ksk3d_console_log("rule");
      ksk3d_console_log($rule);


    $rule_ct = $_POST["rule_ct"];
    $rule_i = $_POST["rule_i"]+$progress;
    if ($rule_i+1 > $rule_ct){
      return static::open_check_rule1();
    }
    $rule_i_ = $rule_i+1;

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    $attrib_id = $rule[$rule_i]['id'];

    $text .=<<< EOL
    <p>詳細設定({$rule_i_}/{$rule_ct})</p>
    <table class="ksk3d_style_table_report">
      <tr><td>タグ名称</td><td>{$set_attrib[$attrib_id]['tag_name']}</td></tr>
      <tr><td>属性名称</td><td>{$set_attrib[$attrib_id]['attrib_name']}</td></tr>
      <tr><td>ルール</td><td>{$rule[$rule_i]['rule']}</td></tr>
    </table>
    <input type="hidden" name="rule[{$rule_i}][id]" value="{$rule[$rule_i]['id']}">
    <input type="hidden" name="rule[{$rule_i}][rule]" value="{$rule[$rule_i]['rule']}">
    <br>
    <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
      <tr><th style="width:150px;">設定項目</th><th style="width:300px;">設定内容</th></tr>

EOL
;

    if ($rule[$rule_i]['rule']=='四捨五入'){
      //四捨五入
      $text .=<<< EOL
      <tr><td>四捨五入する位</td><td><input type="tel" id="form-ticker-symbol" name="rule[{$rule_i}][round]" value="{$rule[$rule_i]['round']}"></td></tr>

EOL
;
    } else {
      //階級区分
      $text .=<<< EOL
      <tr>
        <td>区分の数</td>
        <td><input type="number" min="1" max="20" id="form-ticker-symbol" name="rule[{$rule_i}][divisions]" value="{$rule[$rule_i]['divisions']}"></td>
      </tr>
      <tr><td>タグの変更</td><td>タグをgen:StringAttributeに変更します。また、タグの変更に伴い、CityGML出力時にタグの順番がソートされます。</td></tr>
      <tr>
        <td>タグの名前</td>
        <td><input type="text" id="form-ticker-symbol" name="rule[{$rule_i}][name]" value="{$rule[$rule_i]['name']}"></td>
      </tr>
    </table>
    <br>
    <input type="submit" name="submit[{$page}]" class="btn-primary" value="区分の数を反映する"/>
    <p></p><br>
    <p>階級区分の設定</p>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
      $(function(){
        $('input').change(function() {
          var in_id = $(this).attr('id');
          if (in_id.match(/^in_/)==null){return;}
          var id_split = in_id.split('_');
          var id = Number(id_split[1]);
          var id1 = id-1;
          var id2 = id+1;
          
          var v_in = document.getElementById(in_id).value;
          document.getElementById("label_"+id).value = v_in + "-";

          if (id1>-1){
            document.getElementById("max_"+id1).innerText = v_in;
            var v_in1 = document.getElementById("in_"+id1).value;
            document.getElementById("label_"+id1).value = v_in1 + "-" + v_in;
          }

          if (id2 < {$rule[$rule_i]['divisions']}){
            var v_in2 = document.getElementById("in_"+id2).value;
            document.getElementById("label_"+id).value += v_in2;
          }
        });
      });
    </script>
    <table class="ksk3d_style_table_report">
      <tr>
        <th style="height:0px;width:160px;border:none;background:none;"></th>
        <th style="height:0px;width:20px;border:none;background:none;"></th>
        <th style="height:0px;width:140px;border:none;background:none;"></th>
        <th style="height:0px;width:20px;border:none;background:none;"></th>
        <th style="height:0px;width:260px;border:none;background:none;"></th>
      </tr>
      <tr>
        <th colspan="3">値</th>
        <th style="border:none;background:none;"></th>
        <th>階級区分設定後の値</th>
      </tr>
      <tr>
        <th>以上</th>
        <th></th>
        <th>未満</th>
        <th style="border:none;background:none;"></th>
        <th></th>
      </tr>

EOL
;

      for ($i=0;$i<$rule[$rule_i]['divisions'];$i++){
        if (!isset($rule[$rule_i][$i]['v'])){
          if ($i==0) {
            $rule[$rule_i][$i]['v'] = 0;
          } else if ($i==1) {
            $rule[$rule_i][$i]['v'] = 1;
          } else if ($i>1) {
            $rule[$rule_i][$i]['v'] = $rule[$rule_i][$i-1]['v']*2-$rule[$rule_i][$i-2]['v'];
          }
        }
      }

      ksk3d_console_log("rule");
      ksk3d_console_log($rule);

      for ($i=0;$i<$rule[$rule_i]['divisions'];$i++){
        if ($i<$rule[$rule_i]['divisions']-1){
          $v_max = $rule[$rule_i][$i+1]['v'];
        } else {
          $v_max = "";
        }
        $text .=<<< EOL
      <tr>
        <td><input type="tel" id="in_{$i}" name="rule[{$rule_i}][{$i}][v]" value="{$rule[$rule_i][$i]['v']}"></td>
        <td>~</td>
        <td><span id="max_{$i}" >{$v_max}</span></td>
        <td style="border:none;"></td>
        <td>
          <!--<span id="label_{$i}" >{$rule[$rule_i][$i]['v']}-{$v_max}</span>-->
          <input type="text" id="label_{$i}" name="rule[{$rule_i}][{$i}][label]" value="{$rule[$rule_i][$i]['v']}-{$v_max}">
        </td>
      </tr>

EOL
;
      }
    }

    //form_id
    $text .= <<< EOL
    </table><br>
    <input type="hidden" name="form_id" value="{$form_id}">
    <input type="hidden" name="rule_ct" value="{$rule_ct}">
    <input type="hidden" name="rule_i" value="{$rule_i}">

EOL
;
          //echo "test6<br>";

    //属性一覧の保存
    $text .= static::view_send_attrib($set_attrib);

    foreach($rule as $key1=>$v1){
    foreach($rule[$key1] as $key2=>$v){
      if ($key1 != $rule_i) {
        if (isset($rule[$key1][$key2]['v'])){
          $text .= <<< EOL
        <input type="hidden" name="rule[{$key1}][{$key2}][v]" value="{$rule[$key1][$key2]['v']}">
        <input type="hidden" name="rule[{$key1}][{$key2}][label]" value="{$rule[$key1][$key2]['label']}">

EOL
;
        } else {
          $text .= <<< EOL
        <input type="hidden" name="rule[{$key1}][{$key2}]" value="{$rule[$key1][$key2]}">

EOL
;
        }
      }
    }}

    
    if ($rule_i+1 < $rule_ct){
      //次へ
      $text2 = "      <input id='button' type='submit' name='submit[{$page}_1]' class='btn-primary' value='次の設定'/>\n";
    } else if ($rule_i+1 == $rule_ct){
      //確認に進む
      $text2 = "      <input id='button' type='submit' name='submit[open_check_rule1]' class='btn-primary' value='実行の確認'/>\n";
    }
    if ($rule_i >0){
      //前へ
      $text2 .= "      <input id='button' type='submit' name='submit[{$page}_-1]' class='btn-secondary' value='前の設定に戻る'/>\n";
    } else if ($rule_i ==0){
      //設定の確認に戻る
      $text2 .= "      <input id='button' type='submit' name='submit[open_check2]' class='btn-secondary' value='ルールの確認に戻る'/>\n";
    }

    //フッターボックス
    $text .= static::ksk3d_box_footer($page, 'option2', $text2);

    return $text;
  }

/**
  * ルールの詳細設定確認（公開用データの生成）
  */
  static function open_check_rule1(){
    $page = 'open_check_rule1';
    //ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_attrib = ksk3d_stripslashes_deep($_POST["set_attrib"]);
    ksk3d_console_log($set_attrib);
    if (isset($_POST["rule"])){
      $rule = $_POST["rule"];
    } else {
      $rule = [];
    }
    ksk3d_console_log("rule");
    ksk3d_console_log($rule);
    $rule_ct = $_POST["rule_ct"];
    $rule_i = $_POST["rule_i"];

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    if ($rule_ct==0){
      $text .= "ルールについて、詳細に設定する項目はありません。<br>実行ボタンをクリックしてください。";
    } else {
      $text .= "ルールの詳細設定が完了しました。<br>実行ボタンをクリックしてください。";
    }

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //form_id
    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="rule_ct" value="{$rule_ct}">
      <input type="hidden" name="rule_i" value="{$rule_i}">

EOL
;

    //属性一覧の保存
    $text .= static::view_send_attrib($set_attrib);

    foreach($rule as $key1=>$v1){
    foreach($rule[$key1] as $key2=>$v){
      if (isset($rule[$key1][$key2]['v'])){
        $text .= <<< EOL
      <input type="hidden" name="rule[{$key1}][{$key2}][v]" value="{$rule[$key1][$key2]['v']}">
      <input type="hidden" name="rule[{$key1}][{$key2}][label]" value="{$rule[$key1][$key2]['label']}">

EOL
;
      } else {
        $text .= <<< EOL
      <input type="hidden" name="rule[{$key1}][{$key2}]" value="{$rule[$key1][$key2]}">

EOL
;
      }
    }}

    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

/**
  * 公開用データの生成
  */
  static function open_exec(){
    $page = 'open_exec';
    //ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_attrib = ksk3d_stripslashes_deep($_POST["set_attrib"]);
    ksk3d_console_log($set_attrib);
    for ($i=count($set_attrib)-1; $i>=0; --$i){
      if (preg_match('/何もしない/', $set_attrib[$i]['rule'])==1){
        unset($set_attrib[$i]);
      }
    }
    ksk3d_console_log($set_attrib);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_citygml::attrib_mod" ,"オープンデータ用" ,$set_attrib);
    $text .= $result[1];

    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
  
/**
  * 公開用データの生成
  */
  static function open_bgexec(){
    $page = 'open_bgexec';
    //ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_attrib = ksk3d_stripslashes_deep($_POST["set_attrib"]);
    ksk3d_console_log($set_attrib);
    
    for ($i=count($set_attrib)-1; $i>=0; --$i){
      if (preg_match('/何もしない/', $set_attrib[$i]['rule'])==1){
        unset($set_attrib[$i]);
      }
    }
    ksk3d_console_log($set_attrib);

    if (isset($_POST["rule"])){
      $rule = $_POST["rule"];
    } else {
      $rule = [];
    }
    ksk3d_console_log("rule");
    ksk3d_console_log($rule);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    $text .= ksk3d_fn_proc::registration(
      "公開用データの生成",
      "ksk3d_conv",
      6,
      array(
        $form_id,
        "",
        "ksk3d_functions_citygml::attrib_mod_op",
        array("オープンデータ用"),
        $set_attrib,
        $rule
      ),
      5,
      1
    );

    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
}