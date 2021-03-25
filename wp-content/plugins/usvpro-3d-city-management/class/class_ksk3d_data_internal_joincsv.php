<?php
class ksk3d_data_internal_joincsv extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_DATA;

  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_data.css'
    ],
    'id' => 'file_id',
    'where_format' => "内部データセット"
  ];

  static function view2(){
    if (isset($_POST["submit"]["dataset2DB_check"])) {
      return static::dataset2DB_check();
    } else if (isset($_POST["submit"]["joincsv_select"])) {
      return static::joincsv_select();
    } else if (isset($_POST["submit"]["joincsv_key"])) {
      return static::joincsv_key();
    } else if (isset($_POST["submit"]["joincsv_exec"])) {
      return static::joincsv_exec();
    } else if (isset($_POST["submit"]["joincsv_bgexec"])) {
      return static::joincsv_bgexec();
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
      'tab' => '0',
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
    'dataset2DB_check' => 'CSVインポート',
    'joincsv_select' => 'CSVの選択',
    'joincsv_key' => '属性を選択',
    'joincsv_exec' => 'CSVインポート実行',
    'joincsv_bgexec' => 'バックグランドで実行'
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'dataset2DB_check',
      'displayName' => 'CSVインポート',
      'th-style' => 'width:140px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    ),
    array(
      'tab' => 'joincsv_select',
      'submit' => 'joincsv_key',
      'displayName' => '属性を選択',
      'th-style' => 'width:140px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    )
  ];

  static $post = [
    'disp' => [
      'header-title' => '内部データセットに属性（CSV）をインポート（データセット一覧）',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'dataset2DB_check' => [
      'header-title' => 'データセットの確認（内部データセットに属性（CSV）をインポート）',
      'header-text' => '次のデータセットにCSVをインポートします。よろしければCSVの選択ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'joincsv_select' => [
      'header-title' => '属性（CSV）の選択（内部データセットに属性（CSV）をインポート）',
      'header-text' => '属性（CSV）を選択してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'joincsv_key' => [
      'header-title' => '結合するキーの設定（内部データセットに属性（CSV）をインポート）',
      'header-text' => '結合するキーの設定を行い、実行ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'joincsv_exec' => [
      'header-title' => '内部データセットに属性（CSV）をインポート実行',
      'header-text' => '内部データセットに属性（CSV）をインポートを実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'joincsv_bgexec' => [
      'header-title' => '内部データセットに属性（CSV）をインポート実行（バックグラウンド）',
      'header-text' => '内部データセットに属性（CSV）をインポートについて、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $header_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
    ],
    'joincsv_select' => [
    ],
    'joincsv_key' => [
    ],
    'joincsv_exec' => [
    ],
    'joincsv_bgexec' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
      array(
        'submit' => 'joincsv_select',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'joincsv_select' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'joincsv_key' => [
      array(
        'submit' => 'joincsv_bgexec',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => 'joincsv_select',
        'class' => 'btn-secondary',
        'display' => 'CSVの選択に戻る'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'joincsv_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'joincsv_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];

  static function joincsv_select(){
    $page = 'joincsv_select';
    $tab = 'joincsv_select';
    ksk3d_console_log("page:".$page);
    $dataset1_id = $_POST["form_id"];
    ksk3d_console_log("dataset1_id:".$dataset1_id);

    $text = static::ksk3d_box_header($page);

    $group[0] = "";
    foreach (static::$tab as $list) {
      $group[$list['tab']] = "ksk3d_none";
    }
    $group[$tab] = "";

    if (count(static::$tab) > 1){
      $text .= "    <div class='ksk3d_box_tab'>";
      foreach (static::$tab as $list) {
        $text .= "      <input id='ksk3d_tab_button' type='submit' name='submit[disp{$list['tab']}]' class='ksk3d_style_table_list_tab_button' value='{$list['displayName']}' />";
      }
      $text .= "    </div>";
    }
    $text .= static::ksk3d_box_main($page);
    
    $wh=""; $mes_wh=""; $format="^内部データセット[.(.]属性[.).]$";
    if (! empty($format)) {
      $wh = " and file_format regexp '" .$format ."' ";
      $mes_wh = "<p>データセットのうち、ファイルフォーマットが " .preg_replace('/\|/' ,' , ' ,preg_replace('/\*|\^|\$|\[\.|\.\]/' ,'' ,$format)) ." であるデータセットを表示しています<br></p>\n";
    }

    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(array_merge(static::$field ,static::BUTTON()) as $list){
      if (isset($group[$list['tab']])){
        $text .= "          <th style=\"{$list['th-style']}\" class=\"{$group[$list['tab']]}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>";

    $pageid2 = filter_input(INPUT_GET, 'pageid2');
    $limit = 10;
 
    global $wpdb;
 
    $tbl_name = $wpdb->prefix .static::$tbl;
    $userID = ksk3d_get_current_user_id();
    $sql = "SELECT count(user_id) AS CNT FROM {$tbl_name} WHERE user_id={$userID} {$wh}";
    $rows = $wpdb->get_results($sql);
    $recordcount = $rows[0]->CNT;

    $offset = $pageid2 * $limit;

    if (isset(static::$setting) and isset(static::$setting['id'])){
      $id = static::$setting['id'];
    } else {
      $id = "id";
    }
    $sql = "SELECT * FROM {$tbl_name} WHERE user_id={$userID} {$wh} ORDER BY {$id} limit {$offset}, {$limit};";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql);

    foreach($rows as $row) {
      $text .= "<tr>";
      foreach(static::$field as $list){
      if (isset($group[$list['tab']])){
        $v = $row->{$list['dbField']};
        if ($list['dbField']=='file_size'){$v = ksk3d_sprintf_bytes($v);}
        $text .= "<td style=\"{$list['td-style']}\" class=\"{$group[$list['tab']]}\">{$v}</td>";
      }}
      foreach(static::button() as $list){
      if (isset($group[$list['tab']])){
        $v = static::$button_value[$list['submit']];
        if (empty($list['format'])){
          $disable = "";
          $cls_disable = "";
        } else if (empty($row->file_format)) {
          $disable = "disabled";
          $cls_disable = " btn-secondary";
        } else if (preg_match('/!/',$list['format'])==1){
          if (preg_match('/'.$list['format'].'/',$row->file_format)==1){
            $disable = "";
            $cls_disable = "";
          } else {
            $disable = "disabled";
            $cls_disable = " btn-secondary";
          }
        } else if (strpos($list['format'], $row->file_format) === false){
          $disable = "disabled";
          $cls_disable = " btn-secondary";
        } else {
          $disable = "";
          $cls_disable = "";
        }
        $text .= "<td style=\"{$list['td-style']}\" class=\"{$group[$list['tab']]}\">
        <input type='submit' name='submit[{$list['submit']}][{$row->id}]'
         class='button-primary{$cls_disable}' value='{$v}' {$disable}/></td>";
      }}
      $text .= "</tr>";
    }
    $text .= "</table>";
    
    $text .= <<< EOL
      <input type="hidden" name="dataset1_id" value="{$dataset1_id}">
EOL
;
    
    $text .= static::ksk3d_box_footer($page ,"option0" ,$mes_wh);

    if ($recordcount > $limit) {
      $args = array(
        'label' => __('Per Page'),
        'default' => 10,
        'option' => 'disp'
      );
      $page_html = static::pagination($recordcount);
      $text .= "<div class='admin_pagination'>";
      $text .= "<ul>";
      foreach ($page_html as $key => $value) {
        $text .= "<li>" . $value . "</li>";
      }
      $text .= "</ul>";
    }
    return $text;
  }

  static function joincsv_key(){
    $page = 'joincsv_key';
    ksk3d_console_log("page:".$page);
    $tab = 'internal2citygml_attrib';
    $dataset1_id = $_POST["dataset1_id"];
    ksk3d_console_log("dataset1_id:".$dataset1_id);
    
    $dataset2_id = static::ksk3d_get_form_id($page);
    ksk3d_console_log("dataset2_id:".$dataset2_id);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $dataset1_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);

    $text .= "<p>ベースとなるデータセット</p>";
    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";
    
    $prepared = $wpdb->prepare($sql, $dataset2_id);
    $rows2 = $wpdb->get_results($prepared, ARRAY_A);

    $text .= "<p>結合するデータセット（属性）</p>";
    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows2[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";
    
    $userID = ksk3d_get_current_user_id();
    $tbl_attribute = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE;
    $sql = "SELECT * FROM {$tbl_attribute} WHERE user_id={$userID} and file_id=%d;";
    $prepared = $wpdb->prepare($sql, $rows[0]['file_id']);
    ksk3d_console_log("sql:".$prepared);
    $attribs1 = $wpdb->get_results($prepared, ARRAY_A);

    $sql = "SELECT * FROM {$tbl_attribute} WHERE user_id={$userID} and file_id=%d;";
    $prepared = $wpdb->prepare($sql, $rows2[0]['file_id']);
    ksk3d_console_log("sql:".$prepared);
    $attribs2 = $wpdb->get_results($prepared, ARRAY_A);

    $text .= "<p>結合する属性の選択</p>\n";
    $text .= "    <table class=\"ksk3d_style_table_report\">\n";

    $text .= "<tr><td>ベースとなるデータセット</td>\n";
    $text .= "<td><select name=\"dataset1_key\" width=\"400px\">\n";
    foreach($attribs1 as $attrib){
      $text .= "<option value=\"{$attrib['attrib_field']}\">{$attrib['attrib_name']}</option>\n";
    }
    $text .= "</select></td></tr>\n";

    $text .= "<tr><td>結合するデータセット</td>\n";
    $text .= "<td><select name=\"dataset2_key\">\n";
    $sel2 = "";
    foreach($attribs2 as $attrib){
      $text .= "<option value=\"{$attrib['attrib_field']}\">{$attrib['attrib_name']}</option>\n";
    }
    $text .= "</select></td></tr>\n";

    $text .= "    </table><br>\n";

    if (empty($attribs1) or empty($attribs2)){
      $ck_disabled = " disabled";
    } else {
      $ck_disabled = "";
    }

    $text .= static::ksk3d_box_footer($page,"","","",$ck_disabled,array("dataset1_id","dataset2_id","ref_id"),array($dataset1_id,$dataset2_id,$rows2[0]['file_id']));

    return $text;
  }

  static function joincsv_exec(){
    $page = 'joincsv_exec';
    ksk3d_console_log("page:".$page);
    $dataset1_id = $_POST["dataset1_id"];
    ksk3d_console_log("dataset1_id:".$dataset1_id);
    $dataset2_id = $_POST["dataset2_id"];
    ksk3d_console_log("dataset2_id:".$dataset2_id);
    $dataset1_key = $_POST["dataset1_key"];
    ksk3d_console_log("dataset1_key:".$dataset1_key);
    $dataset2_key = $_POST["dataset2_key"];
    ksk3d_console_log("dataset2_key:".$dataset2_key);
    $ref_id = $_POST["ref_id"];
    ksk3d_console_log("ref_id:".$ref_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $result = ksk3d_conv($dataset1_id ,"" ,"ksk3d_functions_internal::join_attrib" ,array("属性結合") ,array('ref_id'=>$ref_id,'dataset1_key'=>$dataset1_key,'dataset2_key'=>$dataset2_key));
    $text .= $result[1];

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function joincsv_bgexec(){
    $page = 'joincsv_bgexec';
    ksk3d_console_log("page:".$page);
    $dataset1_id = $_POST["dataset1_id"];
    ksk3d_console_log("dataset1_id:".$dataset1_id);
    $dataset2_id = $_POST["dataset2_id"];
    ksk3d_console_log("dataset2_id:".$dataset2_id);
    $dataset1_key = $_POST["dataset1_key"];
    ksk3d_console_log("dataset1_key:".$dataset1_key);
    $dataset2_key = $_POST["dataset2_key"];
    ksk3d_console_log("dataset2_key:".$dataset2_key);
    $ref_id = $_POST["ref_id"];
    ksk3d_console_log("ref_id:".$ref_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= ksk3d_fn_proc::registration(
      "内部データセットに属性（CSV）をインポート",
      "ksk3d_conv",
      5,
      array(
        $dataset1_id,
        "",
        "ksk3d_functions_internal::join_attrib",
        array("属性結合"),
        array('ref_id'=>$ref_id,'dataset1_key'=>$dataset1_key,'dataset2_key'=>$dataset2_key)
      ),
      10,
      5
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
}