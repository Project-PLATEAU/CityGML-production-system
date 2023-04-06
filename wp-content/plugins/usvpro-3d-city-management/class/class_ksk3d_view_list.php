<?php
class ksk3d_view_list{
  static $tbl;
  static $tbl_ref = [];
  static $setting = [];
  static $tab = [];
  static $field = [];

  static $button_value = [
    '' => '一覧に戻る',
    'detail' => '詳細',
    'edit' => '情報編集',
    'edit_check' => '編集内容を確認',
    'edit_exec' => '編集を確定',
    'delete_check' => '削除',
    'delete_exec' => '削除を確定',
    'map_view' => 'マップ',
    'regist' => '新規登録',
    'regist_check' => '登録内容を確認',
    'regist_exec' => '登録を確定',
    'regist_file' => '新規登録(ファイルアップロード)',
    'regist_file_uploading' => 'アップロード',
    'regist_file_up' => 'アップロード',
    'download_check' => 'ダウンロード',
    'download_exec' => 'ファイルをダウンロード',
    'detail_ref' => '要素一覧',
    'edit_ref_list' => '要素一覧編集',
    'edit_ref' => '情報編集',
    'edit_ref_check' => '編集内容を確認',
    'edit_ref_exec' => '編集を確定',
    'edit_ref_del' => '要素削除',
    'dataset2DB_check' => '内部データセットへ変換',
    'gml2db_set_attrib' => '属性項目の設定',
    'gml2db_set_high' => '高さの設定',
    'gml2DB_check2' => '内部データセットへ変換確認',
    'gml2DB_exec' => '内部データセットへ変換実行',
    'citygml_3dTiles' => '3dTiles変換',
    'confirm_xml_namespace' => '名前空間の確認',
    'schema' => '妥当性チェック',
    'schemaValidate' => '妥当性チェックの実施',
    'test' => '開発中確認',
    'zip_extractTo' => 'ZIP解凍'
   ];
   

  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'detail',
      'displayName' => '詳細',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    )
  ];
  static $button_ref_ = [];

  static $post = [];
  
  static $field_ref = [
    array(
      'tab' => 'attrib_edit',
      'displayName' => 'タグ名称',
      'gmlValue' => 'tag_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'attrib_edit',
      'displayName' => 'tag_path',
      'gmlValue' => 'tag_path',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'attrib_edit',
      'displayName' => 'tag_attrib',
      'gmlValue' => 'tag_attrib',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'attrib_edit',
      'displayName' => 'tag_attrib_name',
      'gmlValue' => 'tag_attrib_name',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'attrib_edit',
      'displayName' => 'フィールド名',
      'gmlValue' => 'field_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:150px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'attrib_edit',
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
      'tab' => 'attrib_edit',
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
      'tab' => 'attrib_edit',
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
      'tab' => 'attrib_edit',
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
      'tab' => 'attrib_edit',
      'displayName' => '属性値（サンプル）',
      'gmlValue' => 'attrib_value',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'attrib_edit',
      'displayName' => 'コード',
      'gmlValue' => 'codelist',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    )
  ];
  
  static $header_button = [];

  static $footer_button = [];
  
  static function button($btn=""){
    if (empty($btn)){
      $btn = static::$button_;
    }

    if (empty(get_option('ksk3d_option')['dev_fn'])){
      $keyIndex = array_keys(array_column($btn ,'status') ,'dev');
      foreach($keyIndex as $i => $v){
        unset($btn[$v]);
      }
      $btn = array_values($btn);
    }
    if (empty(get_option('ksk3d_option')['verify_fn'])){
      $keyIndex = array_keys(array_column($btn ,'status') ,'verify');
      foreach($keyIndex as $i => $v){
        unset($btn[$v]);
      }
      $btn = array_values($btn);
    }

    return $btn;
  }
  
  static function view(){
    static::ksk3d_user_log_1();

    ksk3d_console_log("class_ksk3d_view_list.php post");
    ksk3d_console_log($_POST);

    if (isset($_POST["submit"]["detail"])) {
      return static::detail();
    } else if (isset($_POST["submit"]["edit"])) {
      return static::edit();
    } else if (isset($_POST["submit"]["edit_check"])) {
      return static::edit_check();
    } else if (isset($_POST["submit"]["edit_exec"])) {
      return static::edit_exec();
    } else if (isset($_POST["submit"]["delete_check"])) {
      return static::delete_check();
    } else if (isset($_POST["submit"]["delete_exec"])) {
      return static::delete_exec();
    } else if (isset($_POST["submit"]["regist"])) {
      return static::regist();
    } else if (isset($_POST["submit"]["regist_check"])) {
      return static::regist_check();
    } else if (isset($_POST["submit"]["regist_exec"])) {
      return static::regist_exec();
    } else if (isset($_POST["submit"]["regist_file"])) {
      return static::regist_file();
    } else if (isset($_POST["submit"]["regist_file_uploading"])) {
      return static::regist_file_uploading();
    } else if (isset($_POST["submit"]["regist_file_up"])) {
      return static::regist_file_up();
    } else if (isset($_POST["submit"]["download_check"])) {
      return static::download_check();
    } else if (isset($_POST["submit"]["download_exec"])) {
      return static::download_exec();
    } else if (isset($_POST["submit"]["detail_ref"])) {
      return static::detail_ref();
    } else if (isset($_POST["submit"]["edit_ref_list"])) {
      return static::edit_ref_list();
    } else if (isset($_POST["submit"]["edit_ref"])) {
      return static::edit_ref();
    } else if (isset($_POST["submit"]["edit_ref_check"])) {
      return static::edit_ref_check();
    } else if (isset($_POST["submit"]["edit_ref_exec"])) {
      return static::edit_ref_exec();
    } else if (isset($_POST["submit"]["edit_ref_del"])) {
      return static::edit_ref_del();
    } else if (isset($_POST["submit"]["map_view"])) {
      return static::map_view();
    } else if (isset($_POST["submit"]["dataset_check"])) {
      return static::dataset_check();
    } else if (isset($_POST["submit"]["attrib_edit"])) {
      return static::attrib_edit();
    } else if (isset($_POST["submit"]["attrib_check"])) {
      return static::attrib_check();
    } else if (isset($_POST["submit"]["dataset2DB_check"])) {
      return static::dataset2DB_check();
    } else if (isset($_POST["submit"]["gml2db_set_attrib"])) {
      return static::gml2db_set_attrib();
    } else if (isset($_POST["submit"]["gml2db_set_high"])) {
      return static::gml2db_set_high();
    } else if (isset($_POST["submit"]["gml2DB_check2"])) {
      return static::gml2DB_check2();
    } else if (isset($_POST["submit"]["gml2DB_exec"])) {
      return static::gml2DB_exec();
    } else if (isset($_POST["submit"]["citygml_3dTiles"])) {
      return static::citygml_3dTiles();
    } else if (isset($_POST["submit"]["confirm_xml_namespace"])) {
      return static::confirm_xml_namespace();
    } else if (isset($_POST["submit"]["zip_extractTo"])) {
      return static::zip_extractTo();
    } else if (isset($_POST["submit"]["disp2"])) {
      
      return static::disp(2);
    } else if (isset($_POST["submit"]["disp3"])) {
      return static::disp(3);
    } else if (isset($_POST["submit"]["disp4"])) {
      return static::disp(4);
    } else {
      return static::view2();
    }
  }
  
  static function view2(){
    if (isset($_POST["submit"]["dataset2DB_check"])) {
      return static::dataset2DB_check();
    } else {
      return static::disp();
    }
  }

  static function disp($tab=1){
    $page = 'disp';
    if (!empty(filter_input(INPUT_GET, 'tab'))){$tab = filter_input(INPUT_GET, 'tab');}
    
    $text = static::ksk3d_box_header($page);

    $group[0] = "";
    foreach (static::$tab as $list) {
      $group[$list['tab']] = "ksk3d_none";
    }
    $group[$tab] = "";

    if (count(static::$tab) > 1){
      $text .= "    <div class='ksk3d_box_tab'>";
      $urlparams = filter_input_array(INPUT_GET);
      foreach (static::$tab as $list) {
        $urlparams['tab'] = $list['tab'];
        $url = "?".http_build_query($urlparams);
        ksk3d_console_log("url:".$url);
        $text .= "      <input id='ksk3d_tab_button' type='button' onclick=\"location.href='{$url}'\" class='ksk3d_style_table_list_tab_button' value='{$list['displayName']}' />";
      }
      $text .= "    </div>";
    }
    
    $text .= static::ksk3d_box_main($page);
    
    $wh=""; $mes_wh="";
    if (! empty(static::$setting['where_format'])) {
      $wh = " and file_format regexp '" .static::$setting['where_format'] ."' ";
      $mes_wh = "<p>データセットのうち、ファイルフォーマットが " .preg_replace('/\|/' ,' , ' ,preg_replace('/\*|\^|\$|\[\.|\.\]/' ,'' ,static::$setting['where_format'])) ." であるデータセットを表示しています</p>\n";
    }
    if (isset(static::$setting['where'])){
      $wh = " and " .static::$setting['where'][$tab];
    }
    if (isset(static::$setting['order'])){
      $order = static::$setting['order'];
    } else {
      $order = "id";
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

    $pageid = filter_input(INPUT_GET, 'pageid');
 
    $limit = 10;
 
    global $wpdb;
 
    $tbl_name = $wpdb->prefix .static::$tbl;
    $userID = ksk3d_get_current_user_id();
    $sql = "SELECT count(id) AS CNT FROM {$tbl_name} WHERE user_id={$userID} {$wh}";
    $rows = $wpdb->get_results($sql);
    $recordcount = $rows[0]->CNT;

    $offset = $pageid * $limit;
    
    if ($offset > $recordcount){
      $offset = 0;
      $pageid = 0;
    }

    if (isset(static::$setting) and isset(static::$setting['id'])){
      $id = static::$setting['id'];
    } else {
      $id = "id";
    }
    $sql = "SELECT * FROM {$tbl_name} WHERE user_id={$userID} {$wh} ORDER BY {$order} limit {$offset}, {$limit};";

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
        } else if (preg_match('/'.$list['format'].'/i', $row->file_format)==1){
          ksk3d_console_log('/'.$list['format'].'/,'.$row->file_format);
          $disable = "";
          $cls_disable = "";
        } else {
          $disable = "disabled";
          $cls_disable = " btn-secondary";
        }
        $text .= "<td style=\"{$list['td-style']}\" class=\"{$group[$list['tab']]}\">
        <input type='submit' name='submit[{$list['submit']}][{$row->id}]'
         class='button-primary{$cls_disable}' value='{$v}' {$disable}/></td>";
      }}
      $text .= "</tr>";
    }
    $text .= "</table>";
    
    $text .= static::ksk3d_box_footer($page ,"option0" ,$mes_wh);

    if ($recordcount > $limit) {
      $args = array(
        'label' => __('Per Page'),
        'default' => 10,
        'option' => 'disp'
      );
      $page_html = static::pagination($recordcount ,$tab);
      $text .= "<div class='admin_pagination'>";
      $text .= "<ul>";
      foreach ($page_html as $key => $value) {
        $text .= "<li>" . $value . "</li>";
      }
      $text .= "</ul>";
    }
    return $text;
  }

  static function detail_disp($form_id,$table="",$tab=""){
    global $wpdb;

    if ($table==""){$table=static::$tbl;}
    $tbl_name = $wpdb->prefix .$table;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    ksk3d_console_log("sql:".$prepared);
    $rows = $wpdb->get_results($prepared, ARRAY_A);

    $text = "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      ksk3d_console_log("tab:".$tab);
      ksk3d_console_log("list:".$list['tab']);
      
      if(($tab=="") or ($tab==$list['tab'])){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table>";
    return $text;
  }
  
  static function detail(){
    $page = 'detail';
    
    $form_id = static::ksk3d_get_form_id($page);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::detail_disp( $form_id );

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$form_id);

    return $text;
  }
 
  static function delete_check(){
    $page = 'delete_check';
    
    $form_id = static::ksk3d_get_form_id($page);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::detail_disp( $form_id );
 
    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function delete_exec(){
    $page = 'delete_exec';

    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "DELETE FROM {$tbl_name} WHERE id = %s;";
    $dlt = $wpdb->query($wpdb->prepare($sql, $form_id));

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function regist(){
    $page = 'regist';

    $text = static::ksk3d_box_header($page);

    if (isset($_POST["form_id"])) {
      $form_id = $_POST["form_id"];
      ksk3d_console_log("display_name:".$_POST['display_name']);
      foreach(static::$field as $list){
        $text .= "
    <tr>
      <td>{$list['displayName']}</td>
      <td>
        <input type=\"text\" name=\"{$list['dbField']}\" value=\"{$_POST[$list['dbField']]}\" {$list['editer']}>
      </td>
    </tr>
";
      }
      $text .="
      </table>
";
      foreach(static::$field as $list){
        if ($list['editer'] <> ''){
          $text .= "  <input type=\"hidden\" name=\"{$list['dbField']}\" value=\"{$_POST[$list['dbField']]}\">\n";
        }
      }
    } else {
      global $wpdb;

      $id_name = static::$setting['id'];
      $tbl_name = $wpdb->prefix .static::$tbl;
      $userID = ksk3d_get_current_user_id();
      $sql = "SELECT max({$id_name}) AS v FROM {$tbl_name} WHERE user_id={$userID}";
      $rows = $wpdb->get_results($sql);
      $form_id = ($rows[0]->v)+1;

      foreach(static::$field as $list){
        $text .= "
    <tr>
      <td>{$list['displayName']}</td>
      <td>
        <input type=\"text\" name=\"{$list['dbField']}\" value=\"\" {$list['editer']}>
      </td>
    </tr>
";
      }
      $text .="
      </table>
";
      foreach(static::$field as $list){
        if ($list['editer'] <> ''){
          $text .= "  <input type=\"hidden\" name=\"{$list['dbField']}\" value=\"\">\n";
        }
      }
    }

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
 
  static function regist_check(){
    $page = 'regist_check';

    $form_id = $_POST["form_id"];
    ksk3d_console_log("display_name:".$_POST['display_name']);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .=<<< EOL
      <table class="ksk3d_style_table_report">
EOL
;

    foreach(static::$field as $list){
        $text .= "    <tr><td>{$list['displayName']}</td><td>{$_POST[$list['dbField']]}</td></tr>";
    }
    $text .="
  </table>
";

    foreach(static::$field as $list){
      $text .= "  <input type=\"hidden\" name=\"{$list['dbField']}\" value=\"{$_POST[$list['dbField']]}\">\n";
    }

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
 
  static function regist_exec(){
    $page = 'regist_exec';

    $text = static::ksk3d_box_header($page);

    $data =[];
    foreach(static::$field as $list){
      if ($list['editer'] == '') {
        $data[$list['dbField']] = $_POST[$list['dbField']];
      }
    }
    $data['user_id'] = ksk3d_get_current_user_id();
    $data[static::$setting['id']] = $_POST["form_id"];
    $data['registration_date'] = current_time('mysql');


    global $wpdb;
    $table_name = $wpdb->prefix .static::$tbl;
    $result = $wpdb->insert(
      $table_name,
      $data
    );
 
    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page);
    
    return $text;
  }

  static function edit(){
    $page = 'edit';

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .=<<< EOL
      <table class="ksk3d_style_table_report">
EOL
;

    if (isset($_POST["form_id"])) {
      $form_id = $_POST["form_id"];
      ksk3d_console_log("display_name:".$_POST['display_name']);
      ksk3d_console_log(static::$setting['id'].":".$_POST[static::$setting['id']]);

      foreach(static::$field as $list){
        $text .= "
      <tr>
        <td>{$list['displayName']}</td>
        <td>
          <input type=\"text\" name=\"{$list['dbField']}\" value=\"{$_POST[$list['dbField']]}\" {$list['editer']}>
        </td>
      </tr>
";
      }
      $text .="
      </table>
";
      foreach(static::$field as $list){
        if ($list['editer'] <> ''){
          $text .= "  <input type=\"hidden\" name=\"{$list['dbField']}\" value=\"{$_POST[$list['dbField']]}\">\n";
        }
      }
    } else {
      $form_id = static::ksk3d_get_form_id($page);

      global $wpdb;

      $tbl_name = $wpdb->prefix .static::$tbl;
      $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
      $prepared = $wpdb->prepare($sql, $form_id);
      $rows = $wpdb->get_results($prepared, ARRAY_A);

      foreach(static::$field as $list){
        $text .= "
      <tr>
        <td>{$list['displayName']}</td>
        <td>
          <input type=\"text\" name=\"{$list['dbField']}\" value=\"{$rows[0][$list['dbField']]}\" {$list['editer']}>
        </td>
      </tr>
";
      }
      $text .="
      </table>
";
      foreach(static::$field as $list){
        if ($list['editer'] <> ''){
          $text .= "      <input type=\"hidden\" name=\"{$list['dbField']}\" value=\"{$rows[0][$list['dbField']]}\">\n";
        }
      }
    }

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
 
  static function edit_check(){
    $page = 'edit_check';

    $form_id = $_POST["form_id"];
    ksk3d_console_log("display_name:".$_POST['display_name']);
    ksk3d_console_log(static::$setting['id'].":".$_POST[static::$setting['id']]);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .=<<< EOL
      <table class="ksk3d_style_table_report">
EOL
;
    foreach(static::$field as $list){
        $text .= "    <tr><td>{$list['displayName']}</td><td>{$_POST[$list['dbField']]}</td></tr>";
    }
    $text .="
  </table>
";
    foreach(static::$field as $list){
      $text .= "  <input type=\"hidden\" name=\"{$list['dbField']}\" value=\"{$_POST[$list['dbField']]}\">\n";
    }

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
 
  static function edit_exec(){
    $page = 'edit_exec';

    $text = static::ksk3d_box_header($page);

    global $wpdb;

    $form_id = $_POST["form_id"];
    $update_date = date("Y-m-d H:i:s");
    ksk3d_console_log("display_name:".$_POST['display_name']);
    ksk3d_console_log(static::$setting['id'].":".$_POST[static::$setting['id']]);

    $data = [];
    $format = [];
    foreach(static::$field as $list){
      if ($list['editer'] == '') {
        $data[$list['dbField']] = $_POST[$list['dbField']];
        array_push($format ,$list['format']);
      }
    }

    ksk3d_console_log("data");
    ksk3d_console_log($data);
    ksk3d_console_log("format");
    ksk3d_console_log($format);

    $tbl_name = $wpdb->prefix .static::$tbl;
    $result = $wpdb->update(
      $tbl_name,
      $data,
      array('id' => $form_id,),
      $format,
      array('%d')
    );

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function pagination($recordcount ,$tab=1){
    $count = $recordcount;
    $limit = 10;
 
    if (0 === $count) {
      return '';
    }
 
    $urlparams = filter_input_array(INPUT_GET);
    $items = [];

    if (isset($urlparams['pageid'])){
      $intCurrentPage = (int) $urlparams['pageid'];
    } else {
      $intCurrentPage = 0;
    }
 
    $intMaxpage = ceil($count / $limit);
 
    $intStartpage = (2 < $intCurrentPage) ? $intCurrentPage - 3 : 0;
    $intEndpage = (($intStartpage + 7) < $intMaxpage) ? $intStartpage + 7 : $intMaxpage;
 
    $urlparams['page'] = filter_input(INPUT_GET, 'page');
    $urlparams['pageid'] = 0;
    if ($tab>1){$urlparams['tab'] = $tab;}
    $items[] = sprintf('<span><a href="?%s">%s</a></span>'
      , http_build_query($urlparams)
      , '最初'
    );
 
    if (0 < $intCurrentPage) {
      $urlparams['pageid'] = $intCurrentPage - 1;
      $items[] = sprintf('<span><a href="?%s">%s</a></span>'
        , http_build_query($urlparams)
        , '前へ'
      );
    }
 
    for ($i = $intStartpage; $i < $intEndpage; $i++) {
      $urlparams['pageid'] = $i;
      $items[] = sprintf('<span%s><a href="?%s">%s</a></span>'
        , ($intCurrentPage == $i) ? ' class="current"' : ''
        , http_build_query($urlparams)
        , $i + 1
      );
    }
 
    if ($intCurrentPage < $intMaxpage - 1) {
      $urlparams['pageid'] = $intCurrentPage + 1;
      $items[] = sprintf('<span><a href="?%s">%s</a></span>'
        , http_build_query($urlparams)
        , '次へ'
      );
    }
 
    $urlparams['pageid'] = $intMaxpage - 1;
    $items[] = sprintf('<span><a href="?%s">%s</a></span>'
      , http_build_query($urlparams)
      , '最後'
    );
 
    return $items;
  }

  static function detail_ref($tab=1){
    $page = 'detail_ref';
    ksk3d_console_log($page);

    $form_id = static::ksk3d_get_form_id($page);
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
    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(array_merge(static::$field_ref) as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>";

    $sql = "a.id as id";
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
      }
    }

    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.user_id={$userID} and a.{$tbl_id_name}={$tbl_id} {$wh} ORDER BY a.id;";
    ksk3d_console_log("sql:".$sql);
    $rows = $wpdb->get_results($sql);

    ksk3d_console_log("SQL Results: count= ".count($rows));
    ksk3d_console_log($rows);
    $idx = 0;
    if (count($rows)>0) {
      foreach($rows as $row) {
        $text .= "<tr>";
        ksk3d_console_log("Row Values (index:$idx):");
        ksk3d_console_log($row);
        foreach(static::$field_ref as $list){
          if (($list['tab']=='0') or ($list['tab']==$tab)){
            $v = $row->{$list['dbField_ref']};
            if ($list['dbField_ref']=='file_size'){$v = ksk3d_sprintf_bytes($v);}
            $text .= "<td style=\"{$list['td-style']}\">{$v}</td>";
          }
        }
        $text .= "</tr>";
        $idx++;
      }
    }
    $text .= "</table>";

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$form_id);

    return $text;
  }

  static function edit_ref_list($form_id=0){
    $page = 'edit_ref_list';
    $tab = "";
    ksk3d_console_log("page:".$page);

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
    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(array_merge(static::$field_ref ,static::button(static::$button_ref_)) as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
      }
    }
    $text .= "        </tr>";

    $sql = "a.id as id";
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
      }
    }

    $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.user_id={$userID} and a.{$tbl_id_name}={$tbl_id} {$wh} ORDER BY a.id;";
    $rows = $wpdb->get_results($sql);

    ksk3d_console_log($rows);
    if (count($rows)>0){
      foreach($rows as $row) {
        $text .= "<tr>";
        ksk3d_console_log($row);
        foreach(static::$field_ref as $list){
          if (($list['tab']=='0') or ($list['tab']==$tab)){
            $v = $row->{$list['dbField_ref']};
            if ($list['dbField_ref']=='file_size'){$v = ksk3d_sprintf_bytes($v);}
            $text .= "<td style=\"{$list['td-style']}\">{$v}</td>";
          }
        }
        foreach(static::button(static::$button_ref_) as $list){
          if (($list['tab']=='0') or ($list['tab']==$tab)){
            $v = static::$button_value[$list['submit']];
            if ((!isset($list['format'])) or (empty($list['format']))){
              $disable = "";
              $cls_disable = "";
            } else if (empty($row->file_format)) {
              $disable = "disabled";
              $cls_disable = " btn-secondary";
            } else if (preg_match('/'.$list['format'].'/i', $row->file_format) != 1){
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

  static function edit_ref(){
    $page = 'edit_ref';
    $tab = "";

    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    ksk3d_console_log("parrent_id:".$parrent_id);
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
      if ($list['tab']=='0'){
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

    $text .=<<< EOL
      <table class="ksk3d_style_table_report">
EOL
;

    if (isset($_POST["form_id"])) {
      $form_id = $_POST["form_id"];

      foreach(static::$field_ref as $list){
        if (($list['tab']=='0') or ($list['tab']==$tab)){
          $text .= "
      <tr>
        <td>{$list['displayName']}</td>
        <td>
          <input type=\"text\" name=\"{$list['dbField_ref']}\" value=\"{$_POST[$list['dbField_ref']]}\" {$list['editer']}>
        </td>
      </tr>
";
        }
      }
      $text .="
      </table>
";
      foreach(static::$field_ref as $list){
        if (($list['tab']=='0') or ($list['tab']==$tab)){
        if ($list['editer'] <> ''){
          $text .= "  <input type=\"hidden\" name=\"{$list['dbField_ref']}\" value=\"{$_POST[$list['dbField_ref']]}\">\n";
        }}
      }
    } else {
      $form_id = static::ksk3d_get_form_id($page);

      $sql = "a.id as id";
      foreach(static::$field_ref as $list){
        if (($list['tab']=='0') or ($list['tab']==$tab)){
          $sql .= "," .$list['dbField'] ." as " .$list['dbField_ref'];
        }
      }
      $sql = "SELECT {$sql} FROM {$tbl_ref_tbl} a LEFT OUTER JOIN {$tbl_ref_ref} b ON {$tbl_ref_on} WHERE a.id={$form_id} {$wh} ORDER BY a.id;";
      $rows = $wpdb->get_results($sql, ARRAY_A);

      foreach(static::$field_ref as $list){
        if (($list['tab']=='0') or ($list['tab']==$tab)){
          $text .= "
      <tr>
        <td>{$list['displayName']}</td>
        <td>
          <input type=\"text\" name=\"{$list['dbField_ref']}\" value=\"{$rows[0][$list['dbField_ref']]}\" {$list['editer']}>
        </td>
      </tr>
";
        }
      }
      $text .="
      </table>
";
      foreach(static::$field_ref as $list){
        if (($list['tab']=='0') or ($list['tab']==$tab)){
        if ($list['editer'] <> ''){
          $text .= "      <input type=\"hidden\" name=\"{$list['dbField_ref']}\" value=\"{$rows[0][$list['dbField_ref']]}\">\n";
        }}
      }
    }

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function edit_ref_check(){
    $page = 'edit_ref_check';
    $tab = "";

    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];
    ksk3d_console_log("parrent_id:".$parrent_id);
    ksk3d_console_log("form_id:".$form_id);
    ksk3d_console_log("display_name:".$_POST['display_name']);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $parrent_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $text .= "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if ($list['tab']=='0'){
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

    $text .=<<< EOL
      <table class="ksk3d_style_table_report">
EOL
;
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "    <tr><td>{$list['displayName']}</td><td>{$_POST[$list['dbField_ref']]}</td></tr>";
      }
    }
    $text .="
  </table>
";
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
        $text .= "  <input type=\"hidden\" name=\"{$list['dbField_ref']}\" value=\"{$_POST[$list['dbField_ref']]}\">\n";
      }
    }

    $text .= <<< EOL
      <input type="hidden" name="parrent_id" value="{$parrent_id}">
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
 
  static function edit_ref_exec(){
    $page = 'edit_ref_exec';
    $tab = "";
    $parrent_id = $_POST["parrent_id"];
    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    global $wpdb;
    $update_date = date("Y-m-d H:i:s");
    ksk3d_console_log("display_name:".$_POST['display_name']);

    $data = [];
    $format = [];
    foreach(static::$field_ref as $list){
      if (($list['tab']=='0') or ($list['tab']==$tab)){
      if ($list['editer'] == '') {
        $data[$list['dbField_ref']] = $_POST[$list['dbField_ref']];
        array_push($format ,$list['format']);
      }}
    }

    ksk3d_console_log("data");
    ksk3d_console_log($data);
    ksk3d_console_log("format");
    ksk3d_console_log($format);

    $tbl_name = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $result = $wpdb->update(
      $tbl_name,
      $data,
      array('id' => $form_id,),
      $format,
      array('%d')
    );

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$parrent_id);

    return $text;
  }

  static function edit_ref_del(){
    $page = 'edit_ref_del';
    $parrent_id = $_POST["parrent_id"];
    $form_id = static::ksk3d_get_form_id($page);
    
    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl_ref[0]['table'];
    $sql = "DELETE FROM {$tbl_name} WHERE id = %d;";
    $dlt = $wpdb->query($wpdb->prepare($sql, $form_id));

    return static::edit_ref_list($parrent_id);
  }

  static function regist_file($error_message_flg = null){
    $page = 'regist_file';

    $form_id = static::ksk3d_get_form_id($page);
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);
    $text .= "  </form>";

    $text .= static::ksk3d_box_main($page);

    $ck_disabled = "";
    if (!ksk3d_check_usedsize()){
      $text .= KSK3D_USEDSIZE_ERR_MES;
      $ck_disabled = " disabled";
    }

    $progressname = ini_get("session.upload_progress.name");
    $kmfs = KSK3D_MAX_FILE_SIZE;
    $text .=<<< EOL
      <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="{$progressname}" value="example" />
        <input type="hidden" name="form_id" value="{$form_id}">
        <input type="hidden" name="MAX_FILE_SIZE" value="{$kmfs}">
        <input type="file" id="files" name="upfilename" />
        <input type="submit" id="file_up" value="アップロード" {$ck_disabled}>
        <input type="hidden" name="submit[regist_file_up]" value="on">
      </form>
EOL
;
    $text .= ksk3d_upload_progress();

    update_user_meta( ksk3d_get_current_user_id(), "ksk3d_token", "1"); 

    $text .= static::ksk3d_box_footer($page ,'option0' ,'     <form action="" method="post">' ,$form_id);

    return $text;
  }

  static function regist_file_uploading(){
    $page = 'regist_file_uploading';
    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= <<<EOL
<FORM NAME=F>
  <input type="hidden" name="_FILES" value="{$_FILES}">
  <input type="hidden" name="submit[regist_file_up]" value="on">
</FORM>
window.onload = function() {
  document.F.submit();
};
EOL
;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$form_id);

    return $text;
  }

  static function regist_file_up()
  {
    $page = 'regist_file_up';
    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    $user_id = ksk3d_get_current_user_id();
    ksk3d_console_log('Uploaded file info:');
    ksk3d_console_log($_FILES);
    
    if (get_user_meta($user_id, "ksk3d_token", true) == "1") {

      if ($_FILES["upfilename"]["error"] == 0) {
        if (is_uploaded_file($_FILES["upfilename"]["tmp_name"])) {
          $file_id = ksk3d_get_max_file_id();
          $upload_dir = ksk3d_upload_dir() . "/" . $file_id;
          if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
            chmod($upload_dir, 0777);
          }

          $upload_file_name = $upload_dir . "/" . $_FILES["upfilename"]["name"];
          ksk3d_console_log("upload_file_name:" . $upload_file_name);
          if (move_uploaded_file($_FILES["upfilename"]["tmp_name"], $upload_file_name)) {
            chmod($upload_file_name, 0777);
          }
          $message = "ファイルをアップロードしました";
          $upfilename = pathinfo($_FILES["upfilename"]["name"]);

          if (preg_match('/^zip$/i', $upfilename['extension']) == 1) {
            $zip_name = $upfilename['basename'];
            $zip_file = $upload_dir . "/" . $zip_name;
            move_uploaded_file($upload_file_name, $zip_file);
          } else {
            $zip_name = "dataset_" . $file_id . ".zip";
            $zip_file = ksk3d_fileid_zip_Compress($file_id, true);
          }

          $zip_fileinfo = ksk3d_zip_fileinfo($zip_file, true);

          update_user_meta($user_id, "ksk3d_token", "2");

          global $wpdb;
          $tbl_name = $wpdb->prefix . static::$tbl;
          $result = $wpdb->insert(
            $tbl_name,
            array(
              'user_id' =>  ksk3d_get_current_user_id(),
              'display_name' =>  $upfilename['filename'],
              'file_id' =>  $file_id,
              'file_format' =>  $zip_fileinfo['format'],
              'file_name' =>  $zip_fileinfo['basename'],
              'file_path' =>  $zip_fileinfo['extract_filepath'],
              'file_size' =>  $_FILES["upfilename"]["size"],
              'zip_name' =>  $zip_name,
              'zip_path' =>  $upload_dir,
              'registration_date' =>  current_time('mysql')
            )
          );
        } else {
          $message = "ファイルのアップロードが失敗しました";
        }
      } else {
        $message = "ファイルのアップロードが失敗しました";
        trigger_error("File upload failed:" . $_FILES["upfilename"]["error"], E_USER_NOTICE);
        ksk3d_console_log("File upload failed:" . $_FILES["upfilename"]["error"]);
      }
    } else {
      $message = "ファイルは既にアップロードされています";
    }

    $text .= static::ksk3d_box_main($page);

    $text .= $message;

    $text .= static::ksk3d_box_footer($page, "", "", $form_id);

    return $text;
  }

  static function download_check(){
    $page = 'download_check';

    $form_id = static::ksk3d_get_form_id($page);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::detail_disp( $form_id );

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function download_exec(){
    $page = 'download_exec';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT file_id,file_name,file_path,zip_name,zip_path FROM {$tbl_name} WHERE id = {$form_id}";
    $data = $wpdb->get_row($sql ,ARRAY_A);
    if (empty($data['zip_path']) or empty($data['zip_name'])){
      $zip_file = ksk3d_upload_dir()."/".$data['file_id']."/"."dataset_".$data['file_id'].".zip";
    } else {
      $zip_file = $data['zip_path']."/".$data['zip_name'];
    }
    ksk3d_console_log($zip_file);
    
    
    if (!is_file($zip_file)){
      ksk3d_fileid_zip_Compress($data['file_id'], true);
    }

    $zip_url = KSK3D_CONT_USERS_URL .substr($zip_file ,mb_strlen(KSK3D_CONT_USERS_PATH));
    ksk3d_download($zip_url);
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }


  static function dataset_check($page=""){
    if (empty($page)){$page = 'dataset_check';}

    $form_id = static::ksk3d_get_form_id($page);

    $text = static::ksk3d_box_header($page);

    $ck_disabled = "";
    if (!ksk3d_check_usedsize()){
      $text .= KSK3D_USEDSIZE_ERR_MES;
      $ck_disabled = " disabled";
    }

    $text .= static::ksk3d_box_main($page);

    $text .= static::detail_disp($form_id);

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,"" ,$ck_disabled);

    return $text;
  }
  
  static function dataset2DB_check($page=""){
    if (empty($page)){
      $page = 'dataset2DB_check';
    }

    $form_id = static::ksk3d_get_form_id($page);

    $text = static::ksk3d_box_header($page);

    $ck_disabled = "";
    if (!ksk3d_check_usedsize()){
      $text .= KSK3D_USEDSIZE_ERR_MES;
      $ck_disabled = " disabled";
    }

    $text .= static::ksk3d_box_main($page);

    $text .= static::detail_disp($form_id);

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
EOL
;
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,"" ,$ck_disabled);

    return $text;
  }

  static function attrib_edit(){
    ksk3d_console_log('attrib_edit');
    $page = 'attrib_edit';
    $tab = 'attrib_edit';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1($form_id);

    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;

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
      $set_attrib = [];

      $result = ksk3d_citygml_test($file1);

      $sql = "";
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          if (!empty($list{'dbField'})){
            $sql .= ",".$list{'dbField'};
          }
        }
      }
      $sql_select = substr($sql ,1);
      $tbl_attrib_tpl = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE_TPL_VALUE;
      $wh = "";

      $i = 0;
      foreach($result as $gml){
        $sql = "SELECT {$sql_select} FROM {$tbl_attrib_tpl} WHERE tag_name='{$gml['tag_name']}' {$wh};";
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
              ksk3d_console_log($text);
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
      <input type="hidden" name="set_attrib_ct" value="{$i}">
{$text2}
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

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
      $set_high = $_POST["set_high"];
      ksk3d_console_log($set_attrib);
      ksk3d_console_log($set_high);
    } else {
      $set_attrib = [];
      $set_high = [
        'menu' => 0,
        'value1_field' => '',
        'value1_times' => 1,
        'value2' => 8
      ];

      $result = ksk3d_citygml_test($file1);

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
      foreach($result as $gml){
        //$sql = "SELECT {$sql_select} FROM {$tbl_ref_tbl} WHERE tag_name='{$gml['tag_name']}' {$wh};";
        $sql = "SELECT {$sql_select} FROM {$tbl_ref_tbl} WHERE \"{$gml['tag_path']}\" like CONCAT('%',tag_name) {$wh};";
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
              ksk3d_console_log($text);
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
      <input type="hidden" name="set_attrib_ct" value="{$i}">
      <input type="hidden" name="set_high[menu]" value="{$set_high['menu']}">
      <input type="hidden" name="set_high[value1_field]" value="{$set_high['value1_field']}">
      <input type="hidden" name="set_high[value1_times]" value="{$set_high['value1_times']}">
      <input type="hidden" name="set_high[value2]" value="{$set_high['value2']}">
{$text2}
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function gml2db_set_high(){
    $page = 'gml2db_set_high';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_attrib = $_POST["set_attrib"];
    ksk3d_console_log($set_attrib);
    $set_attrib_ct = $_POST["set_attrib_ct"];
    $set_high = $_POST["set_high"];
    ksk3d_console_log($set_high);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_main_info1($form_id);
    
    global $wpdb;

    $text_select = "";
    foreach ($set_attrib as $attrib){
      ksk3d_console_log($attrib);
      if (preg_match("/int|double/i" ,$attrib["attrib_type"])==1){
        if ($set_high['value1_field'] == $attrib['attrib_name']){
          $tmp_ck = "selected";
        } else {
          $tmp_ck = "";
        }
        $text_select .= "            <option value = \"{$attrib['attrib_name']}\" {$tmp_ck}>{$attrib['attrib_name']}</option>\n";
      }
    }

    if ((empty($text_select)) and ($set_high["menu"] == 1)) {
      $set_high["menu"] = 0;
    }
    $radio_ck = [
      0 => '',
      1 => '',
      2 => ''
    ];
    $radio_ck[$set_high["menu"]] = "checked=\"checked\"";

    if (empty($text_select)){
      $text .= <<< EOL
        <input type="radio" name="set_high[menu]" value="1" disabled="disabled">地物を立ち上げる（属性値から設定）<br>
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
    <input type="radio" name="set_high[menu]" value="1" {$radio_ck[1]}>地物を立ち上げる（属性値から設定）<br>
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
        <input type="radio" name="set_high[menu]" value="2" {$radio_ck[2]}>地物を立ち上げる（固定値）<br>
          <table class="ksk3d_style_table_list">
            <tr><th>設定値</th></tr>
            <tr><td><input type="number" name="set_high[value2]" min="0" step="0.1" value="{$set_high['value2']}"></td></tr>
          </table>
        <br>
        <input type="radio" name="set_high[menu]" value="0" {$radio_ck[0]}>地物の立ち上げをしない<br>
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

  static function attrib_check(){
    $page = 'attrib_check';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_attrib = $_POST["set_attrib"];
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
  
  static function gml2DB_check2(){
    $page = 'gml2DB_check2';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_attrib = $_POST["set_attrib"];
    ksk3d_console_log($set_attrib);
    $set_high = $_POST["set_high"];
    ksk3d_console_log($set_high);

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
      <input type="hidden" name="set_high[menu]" value="{$set_high['menu']}">
      <input type="hidden" name="set_high[value1_field]" value="{$set_high['value1_field']}">
      <input type="hidden" name="set_high[value1_times]" value="{$set_high['value1_times']}">
      <input type="hidden" name="set_high[value2]" value="{$set_high['value2']}">

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
    $set_attrib = $_POST["set_attrib"];
    $set_high = $_POST["set_high"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $userID = ksk3d_get_current_user_id();  
    $file_id2 = ksk3d_get_max_file_id();
    $upload_dir = ksk3d_upload_dir() ."/" .$file_id2;

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();  
    ksk3d_console_log("charset_collate:".$charset_collate);
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "INSERT INTO {$tbl_name} (
  `user_id`,
  `file_id`,
  `display_name`,
  `file_format`,
  `file_name`,
  `registration_date`,
  `meta_name`,
  `meta_path`,
  `memo_city`,
  `memo`,
  `meshsize`,
  `camera_position`
  )
  SELECT 
    `user_id`,
    {$file_id2},
    `display_name`,
    '内部データセット',
    '内部データセット',
    CURRENT_TIMESTAMP,
    `meta_name`,
    IF(`meta_path`is NOT NULL,'{$upload_dir}',NULL),
    `memo_city`,
    `memo`,
    0,
    ''
  FROM {$tbl_name}
  WHERE id={$form_id};";

    ksk3d_log( "sql:" .$sql );
    $wpdb->query($sql);

    $sql = "SELECT file_path,file_name,file_format FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    $file1 = $result['file_path']."/".$result['file_name'];
    ksk3d_console_log("file:".$file1);

    ksk3d_DB_create_attrib(
      KSK3D_TABLE_ATTRIB .$userID ."_" .$file_id2,
      $set_attrib
    );

    ksk3d_DB_create_geom(
      KSK3D_TABLE_GEOM .$userID ."_" .$file_id2
    );

    ksk3d_DB_insert_attrib(
      $userID,
      $file_id2,
      $set_attrib
    );

    if (preg_match('/基盤地図情報/' ,$result['file_format'])>0){
      $result = ksk3d_fgd2DB(
        $file1,
        KSK3D_TABLE_ATTRIB .$userID ."_" .$file_id2,
        KSK3D_TABLE_GEOM .$userID ."_" .$file_id2
      );
    } elseif (preg_match('/citygml/i' ,$result['file_format'])>0){
      $result = ksk3d_citygml2DB(
        $file1,
        KSK3D_TABLE_ATTRIB .$userID ."_" .$file_id2,
        KSK3D_TABLE_GEOM .$userID ."_" .$file_id2,
        $set_attrib,
        $set_high
      );
    } else {
      $result['message'] = "フォーマットが不明です";
    }
    $text .= $result['message'];

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function zip_extractTo(){
    $page = 'zip_extractTo';
    ksk3d_console_log("page:".$page);

    $form_id = static::ksk3d_get_form_id($page);

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;

    $sql = "SELECT file_path,file_name FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    ksk3d_console_log("form_id:".$form_id);
    $file = $result['file_path']."/".$result['file_name'];
    ksk3d_console_log("filename:".$file);
    $zip_info = pathinfo($file);

    
    $zip_extract = ksk3d_zip_extractTo($file);
    ksk3d_console_log("zip_extract");
    ksk3d_console_log($zip_extract);

    $file_size = ksk3d_dir_size($result['file_path']);
    ksk3d_console_log("file_size:".$file_size);

    if ($zip_extract){
      list($file_format, $ext) = ksk3d_dir_format($result['file_path']);
      ksk3d_console_log($zip_extract['basename1'].",".$ext);
      $filename = $zip_extract['basename1'].".".$ext;
        ksk3d_console_log("match_check");
      if (preg_match('{\*/\*\.\*}',$filename)==1){
        ksk3d_console_log("match1");
        $filename = $basename;
        $file_info1 = ksk3d_format($zip_extract['filename1']);
      } else if (preg_match('{\*/\*}',$filename)==1){
        ksk3d_console_log("match2");
        $filename1 = ksk3d_get_file1($result['file_path'] ,"\.".$ext);
        if ($filename1!=false){
          $filename = substr($filename1,strlen($result['file_path'])+1);
          $f = pathinfo($filename);
          $filename = $f['dirname']."/*.".$ext;
        }
      }
      ksk3d_console_log("filename:".$filename);
      
      $sql = "UPDATE {$tbl_name} SET
      zip_name=file_name,
      zip_path=file_path,
      file_format='{$file_format}',
      file_name='{$filename}',
      file_size={$file_size}
      WHERE id = %d;";
      $prepared = $wpdb->prepare($sql, $form_id);
      $wpdb->query($prepared);
  
      $option1 = "解凍しました";
    } else {
      $option1 = "解凍に失敗しました";
    }
    $text .= static::ksk3d_box_footer($page ,'option1' ,$option1);

    return $text;
  }

  static function ksk3d_get_form_id( $page ){
    if (is_array($_POST["submit"][$page])) {
      foreach($_POST["submit"][$page] as $key => $value){}
      return $key;
    } else {
      return 0;
    }
  }

  static function ksk3d_user_log_1(){
    if (isset($_POST["submit"])) {
      foreach($_POST["submit"] as $key => $value){}
      if (isset(static::$button_value[$key])){ksk3d_user_log(static::$post[$key]['header-title']);}

      return true;
    } else {
      return false;
    }
  }

  static function ksk3d_alert($message=""){
    
  }

  static function ksk3d_box_header($page,$text1="",$option=[]){
    $kcu = KSK3D_CSS_URL;
    $kdc_t = static::$post[$page]['header-title'];
    $kdc_ht = static::$post[$page]['header-text'];

    $text = '';
    if (isset($option['alert'])){
      if (!isset($option['alert_message'])){
        $option['alert_message'] = '実行してもよろしいですか？';
      }
      $text =<<< EOL
    <script>


      function submitChk () {
        if (myform.ksk3d_key.value=='{$option['alert']}'){
          return confirm ('{$option['alert_message']}');
        }

      }
    </script>
EOL
;
      $text_form = ' enctype="multipart/form-data" onsubmit="return submitChk();"';
    } else {
      $text_form = '';
    }

    foreach(static::$setting['css'] as $list){
      $text .= <<<EOL
<link rel="stylesheet" href="{$kcu}/{$list}">
EOL
;
    }
    
    if (! empty(get_option('ksk3d_option')['ksk3d_development_function'])){
      $text .= "<link rel=\"stylesheet\" href=\"{$kcu}/{$list}\">";
    }

    $text .= <<< EOL
<div class="wrap">
  <form name="myform" action="" method="post"{$text_form}>
    <div class="ksk3d_box_header">
      <h2 class="ksk3d_box_header_title">{$kdc_t}</h2>
      <p class="ksk3d_box_header_text">{$kdc_ht}</p>
EOL
;
    if (count(static::$header_button[$page]) > 0){
      foreach(static::$header_button[$page] as $list){
        $v = static::$button_value[$list['submit']];
        if (isset($list['onclick'])){$v .= "' onclick='{$list['onclick']}";}
        $text .= "      <input id='button' type='submit' name='submit[{$list['submit']}]' class='{$list['class']}' value='$v' />\n";
      }
    }
    $text .= "{$text1}\n    </div>\n";
    return $text;
  }

  static function ksk3d_box_main( $page ){
    $kdc_mt = static::$post[$page]['main-text'];

    $text =<<< EOL
    <div class="ksk3d_box_main">
      <p class="ksk3d_box_main_text">{$kdc_mt}</p>
EOL
;
    return $text;
  }

  static function ksk3d_box_main_info1( $form_id ){
    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT * FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $rows = $wpdb->get_results($prepared, ARRAY_A);
    $text = "    <table class=\"ksk3d_style_table_report\">";
    foreach(static::$field as $list){
      if ($list['tab']==0){
        $text .= "      <tr><td>{$list['displayName']}</td><td>{$rows[0][$list['dbField']]}</td></tr>";
      }
    }
    $text .= "    </table><br>";
    return $text;
  }

  static function ksk3d_box_footer( $page ,$key='' ,$value='' ,$form_id=0 ,$btn_st1="" ,$send_name="" ,$send_var=""){
    $argv = [
      'option0' => '',
      'option1' => '',
      'option2' => '',
      'option3' => ''
    ];
    $argv[$key]=$value;
    $kdc_ft = static::$post[$page]['footer-text'];

    $text =<<< EOL
    </div>
    {$argv['option0']}<div class="ksk3d_box_footer">
{$argv['option1']}      <p class="ksk3d_box_footer_text">{$kdc_ft}</p>{$argv['option2']}
EOL
;
    $v = "";
    if (count(static::$footer_button[$page]) > 0){
      foreach(static::$footer_button[$page] as $list){
        $s = preg_replace('/\[\{\$form_id\}\]/' ,"][{$form_id}" ,$list['submit']);
        if (isset($list['display'])){
          $v = $list['display'];
        } else {
          $v = static::$button_value[preg_replace('/\[.*\]/','',$list['submit'])];
        }
        if ($list['class']=='btn-primary'){
          $btn_st2 = $btn_st1;
        } else {
          $btn_st2 = "";
        }
        if (isset($list['onclick'])){
          $v .= "' onclick='{$list['onclick']}";
          $flg_onclick = true;
        }
        $text .= "      <input id='button' type='submit' name='submit[{$s}]' class='{$list['class']}' value='{$v}' {$btn_st2}/>\n";
      }
      if (isset($flg_onclick)){
        $text .= "      <input name=\"ksk3d_key\" type=\"hidden\" value=\"\" />\n";
      }
    }
    
    
    if (!is_array($send_name)){
      $send_name = [$send_name];  
      $send_var = [$send_var];    
    }
    for ($i=0; $i<count($send_name); $i++){
      if (is_array($send_var[$i])){
        foreach($send_var[$i] as $key=>$v){
          if (is_array($v)){
            foreach($v as $key2=>$v2){
              $text .= "      <input type=\"hidden\" name=\"{$send_name[$i]}[{$key}][{$key2}]\" value=\"{$v2}\">\n";
            }
          } else {
            $text .= "      <input type=\"hidden\" name=\"{$send_name[$i]}[{$key}]\" value=\"{$v}\">\n";
          }
        }
      } else {
        $text .= "      <input type=\"hidden\" name=\"{$send_name[$i]}\" value=\"{$send_var[$i]}\">\n";
      }
    }
    
    $text .=<<< EOL
{$argv['option3']}    </div>
  </form>
</div>
EOL
;
    return $text;
  }
}