<?php
class ksk3d_data_internal_citygml extends ksk3d_view_list{
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
    } else if (isset($_POST["submit"]["internal2citygml_feature"])) {
      return static::internal2citygml_feature();
    } else if (isset($_POST["submit"]["internal2citygml_attrib"])) {
      return static::internal2citygml_attrib();
    } else if (isset($_POST["submit"]["internal2citygml_exec"])) {
      return static::internal2citygml_exec();
    } else if (isset($_POST["submit"]["internal2citygml_bgexec"])) {
      return static::internal2citygml_bgexec();
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
    'dataset2DB_check' => 'CityGMLに変換',
    'internal2citygml_feature' => '地物の設定',
    'internal2citygml_attrib' => '属性の設定',
    'internal2citygml_exec' => 'CityGMLに変換実行',
    'internal2citygml_bgexec' => 'バックグランドで実行',
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'dataset2DB_check',
      'displayName' => 'CityGMLに変換',
      'th-style' => 'width:140px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    )
  ];

  static $post = [
    'disp' => [
      'header-title' => '内部データセットをCityGMLに変換（データセット一覧）',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'dataset2DB_check' => [
      'header-title' => 'データセットの確認（内部データセットをCityGMLに変換）',
      'header-text' => '次のデータセットについて、CityGMLに変換します。よろしければ地物の設定ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2citygml_feature' => [
      'header-title' => '地物の設定（内部データセットをCityGMLに変換）',
      'header-text' => '地物について設定を行い、設定後、属性の設定ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2citygml_attrib' => [
      'header-title' => '属性の設定（内部データセットをCityGMLに変換）',
      'header-text' => '属性について設定を行い、設定後、実行ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2citygml_exec' => [
      'header-title' => '内部データセットをCityGMLに変換実行',
      'header-text' => '内部データセットをCityGMLに変換を実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'internal2citygml_bgexec' => [
      'header-title' => '内部データセットをCityGMLに変換実行（バックグラウンド処理）',
      'header-text' => '内部データセットをCityGMLに変換について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $field_ref = [
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => 'タグ名称',
      'dbField' => 'tag_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:350px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => '単位',
      'dbField' => 'attrib_unit',
      'default' => '',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:80px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => 'コードリスト',
      'dbField' => 'codelist_name',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:300px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => '属性名称',
      'dbField' => 'attrib_name',
      'default' => '属性[_n%]',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => 'フィールド名',
      'dbField' => 'attrib_field',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => '型',
      'dbField' => 'attrib_type',
      'default' => 'VARCHAR',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => '桁',
      'dbField' => 'attrib_digit',
      'default' => '100',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    ),
    array(
      'tab' => 'internal2citygml_attrib',
      'displayName' => 'tag_path',
      'dbField' => 'tag_path',
      'editer' => 'hidden',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => '',
      'status' => ''
    )
  ];
  
  static $header_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
    ],
    'internal2citygml_feature' => [
    ],
    'internal2citygml_attrib' => [
    ],
    'internal2citygml_exec' => [
    ],
    'internal2citygml_bgexec' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
      array(
        'submit' => 'internal2citygml_feature',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'internal2citygml_feature' => [
      array(
        'submit' => 'internal2citygml_attrib',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'internal2citygml_attrib' => [
      array(
        'submit' => 'internal2citygml_bgexec',
        'class' => 'btn-primary'
      ),
       array(
        'submit' => 'internal2citygml_feature',
        'class' => 'btn-secondary',
        'display' => '地物の設定に戻る'
      ),
     array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'internal2citygml_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'internal2citygml_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];

  static function internal2citygml_feature(){
    $page = 'internal2citygml_feature';
    ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);


    if (isset($_POST["set_feature"])){
      ksk3d_console_log("postあり");
      $set_feature = $_POST["set_feature"];
    } else {
      $set_feature = [
        'template'=>'',
        'filename'=>'[meshcode]_bldg_6697.gml',
        'feature'=>'bldg:Building',
        'lod'=>'bldg:lod1Solid',
        'geom'=>'gml:Solid',
        'srs'=>'http://www.opengis.net/def/crs/EPSG/0/6697',
        'mesh'=>3
      ];
    }
    ksk3d_console_log($set_feature);

    if ($set_feature['geom']=="gml:Solid"){
      $sel21 = " selected";
      $sel22 = "";
    } else {
      $sel21 = "";
      $sel22 = " selected";
    }
    $sel4 = array("","","","","");
    $sel4[$set_feature['mesh']] = " selected";
      
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $text2 = <<<EOL
<script type="text/javascript">    
  var item = new Array();
  
EOL
;

    $text .= <<<EOL
      <table class="ksk3d_style_table_report">
        <tr><td>テンプレート</td><td><select name="set_feature[template]" onchange="changeOptions(this.form)" value="">
EOL
;
    $template = array(
      KSK3D_PATH ."/storage/citygml/bldg_building.gml",
      KSK3D_PATH ."/storage/citygml/gen_GenericCityObject.gml"
    );
    for ($i=0; $i<count($template); $i++){
      $filepath = pathinfo($template[$i]);
      if ($set_feature['template']==$filepath['filename']){$sel=" selected";} else {$sel="";}
      $text .= "            <option value=\"{$filepath['filename']}\" {$sel}>".$filepath['filename']."</option>";
      $stat = ksk3d_functions_citygml::status($template[$i]);

      $text2 .= <<<EOL
    item["{$filepath['filename']}"] = new Array();
    item["{$filepath['filename']}"][0]="{$stat['FeatureType']}";
    item["{$filepath['filename']}"][1]="{$stat['LOD']}";
    item["{$filepath['filename']}"][2]="{$stat['Geometry']}";
    item["{$filepath['filename']}"][3]="{$stat['srsName']}";
    
EOL
;
    }

    $text .= <<<EOL
          </select>
        </td></tr>
      </table>
      <br>
      <br>
      <table class="ksk3d_style_table_report">
        <tr><td>ファイル名</td><td><input type="text" id="setting_filename" name="set_feature[filename]" value="{$set_feature['filename']}"></td></tr>
        <tr>
          <td>FeatureType</td>
          <td>
            <input id="setting0" type="text" list="feature" name="set_feature[feature]"  value="{$set_feature['feature']}">
            <datalist id="feature">
              <option value="bldg:Building">
              <option value="gen:GenericCityObject">
            </datalist>
          </td>
        </tr>
        <tr>
          <td>LOD</td>
          <td>
            <input id="setting1" type="text" list="lod" name="set_feature[lod]"      value="{$set_feature['lod']}">
            <datalist id="lod">
              <option value="bldg:lod1Solid">
              <option value="gen:lod0Geometry">
            </datalist>
          </td>
        </tr>
        <tr>
          <td>Geometry</td>
          <td>
            <select id="setting2" name="set_feature[geom]"     value="{$set_feature['geom']}">
              <option value="gml:Solid" {$sel21}>gml:Solid</option>
              <option value="gml:MultiSurface" {$sel22}>gml:MultiSurface</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>srsName</td>
          <td>
            <input id="setting3" type="text" list="srs" name="set_feature[srs]"      value="{$set_feature['srs']}">
            <datalist id="srs">
              <option value="http://www.opengis.net/def/crs/EPSG/0/6697">
              <option value="http://www.opengis.net/def/crs/EPSG/0/6668">
            </datalist>
          </td>
        </tr>
<!--
        <tr>
          <td>メッシュサイズ</td>
          <td>
            <select id="setting4" name="set_feature[mesh]"     value="{$set_feature['mesh']}">
              <option value="2" {$sel4[2]}>2次メッシュ</option>
              <option value="3" {$sel4[3]}>3次メッシュ</option>
              <option value="4" {$sel4[4]}>4次メッシュ</option>
            </select>
          </td>
        </tr>
-->
      </table>
    
EOL
;
    $text2 .= <<<EOL
  function changeOptions(frmObj) {
    var n = frmObj.elements["set_feature[template]"].value;
    console.log(n);
    for(i=0; i<4; i++ ) {
      document.getElementById("setting"+i).value=item[n][i];
    }
    var filename2 = item[n][0].split(':');
    var filename3 = item[n][3].split('/');
    document.getElementById("setting_filename").value="[meshcode]_"+filename2[0]+"_"+filename3[filename3.length - 1]+".gml";
  }
</script>

EOL
;
    $text .= $text2;

    $text .= <<< EOL
      <input type="hidden" name="form_id" value="{$form_id}">
      <input type="hidden" name="set_feature[mesh]" value="{$set_feature['mesh']}">
EOL
;

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function internal2citygml_attrib(){
    $page = 'internal2citygml_attrib';
    ksk3d_console_log("page:".$page);
    $tab = 'internal2citygml_attrib';
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_feature = $_POST["set_feature"];
    ksk3d_console_log($set_feature);
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;

    $tbl_name = $wpdb->prefix .KSK3D_TABLE_DATA;
    $sql = "SELECT file_id FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $result = $wpdb->get_row($prepared ,ARRAY_A);
    $file_id = $result['file_id'];
    ksk3d_console_log("file_id:".$file_id);

    $text .=<<< EOL
      <table class="ksk3d_style_table_list wp-list-table widefat striped posts">
        <tr>
EOL
;
    foreach(static::$field_ref as $list){
      if (($list['tab']==0) or ($list['tab']==$tab)){
        if (!empty($list['dbField'])){
        if ($list['editer']!='hidden'){
          $text .= "          <th style=\"{$list['th-style']}\">{$list['displayName']}</th>\n";
        }}
      }
    }
    $text .= "        </tr>";

    if (isset($_POST["set_attrib"])) {
      $set_attrib = $_POST["set_attrib"];
      ksk3d_console_log($set_attrib);
    } else {
      $set_attrib = [];

      $set_attrib = ksk3d_functions_internal::sel_attrib("" ,$file_id);
      $set_attrib = array_filter($set_attrib ,"ksk3d_dataset_internal::attributes_filter_not_geometry");
      ksk3d_console_log("sel_attrib");
      ksk3d_console_log($set_attrib);
    }

    $i = 0;
    $text2 = "";
    foreach($set_attrib as $attrib){
      $text .= "        <tr>\n";
      ksk3d_console_log ("attrib:");
      ksk3d_console_log($attrib);
      foreach(static::$field_ref as $list){
        if (($list['tab']==0) or ($list['tab']==$tab)){
          if (!empty($list['dbField'])){
            $m = $list['dbField'];
            $v = $attrib["{$m}"];

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
              ksk3d_console_log ("not select");
              $v_ = $v;
              if (mb_strlen($v_)>50){$v_ = mb_substr($v_ ,0 ,50) ."・・・";}
              
              if (preg_match('/^tag_name$/i' ,$list['dbField'])==1){
                ksk3d_console_log ("tag_name");
                $datalist1 = " autocomplete=\"on\" list=\"datalist{$i}\"";
                $datalist2 = "<datalist id=\"datalist{$i}\">";
                
                if (preg_match('/varchar/i' ,$attrib['attrib_type'])==1){
                  $datalist2 .= "<option value=\"gen:stringAttribute/gen:value\">";
                } else if (preg_match('/int/i' ,$attrib['attrib_type'])==1){
                  $datalist2 .= "<option value=\"gen:intAttribute/gen:value\">";
                } else if (preg_match('/double/i' ,$attrib['attrib_type'])==1){
                  $datalist2 .= "<option value=\"gen:doubleAttribute/gen:value\">";
                }
                
                if ($attrib['attrib_name']=="gml_id"){
                  $v = "@gml:id";
                  $datalist2 .= "<option value=\"{$v}\">";
                } else if (preg_match('/ogr\:|[^\:]/i' ,$attrib['tag_name'])==1){
                  if (preg_match('/varchar/i' ,$attrib['attrib_type'])==1){
                    $v = "gen:stringAttribute/gen:value";
                  } else if (preg_match('/int/i' ,$attrib['attrib_type'])==1){
                    $v = "gen:intAttribute/gen:value";
                  } else if (preg_match('/double/i' ,$attrib['attrib_type'])==1){
                    $v = "gen:doubleAttribute/gen:value";
                  }
                } else {
                  if (!empty($attrib['tag_path'])){
                    if (preg_match('/gen:\S+attribute/i' ,$v)){
                      $v = preg_replace('/^.+?\//' ,'' ,$attrib['tag_path'])."/".$v;
                    } else {
                      $v = preg_replace('/^.+?\//' ,'' ,$attrib['tag_path']);
                    }
                    if (preg_match('/\:/' ,$v)==1){$datalist2 .= "<option value=\"{$v}\">";}
                  }
                }
                
                ksk3d_console_log ("ATTRIBUTE_TPL_VALUE_1");
                $tbl = $wpdb->prefix .KSK3D_TABLE_ATTRIBUTE_TPL_VALUE;
                ksk3d_console_log ("tbl:".$tbl);
                ksk3d_console_log ("attrib_name:".$attrib['attrib_name']);
                if (mb_strlen($attrib['attrib_name'])>4){
                  $attrib_name = mb_substr($attrib['attrib_name'],0,4);
                } else {
                  $attrib_name = $attrib['attrib_name'];
                }
                ksk3d_console_log ("attrib_name2:".$attrib_name);
                $cands = ksk3d_fn_db::sel("select tag_name from {$tbl} where user_id=1 and template_id=1 and attrib_name like '%{$attrib_name}%' limit 10;");
                ksk3d_console_log ("cands:");
                ksk3d_console_log ($cands);
                if (!empty($cands)){
                  foreach($cands as $cand){
                    $tag_name = $cand['tag_name'];
                    if (preg_match('/ur?\:/' ,$tag_name)==1){
                      $tag_name = "uro:buildingDetails/uro:BuildingDetails/".$tag_name;
                    }
                    $datalist2 .= "<option value=\"{$cand['tag_name']}\">";
                  }
                }
                ksk3d_console_log ("ATTRIBUTE_TPL_VALUE_2");

                if (preg_match('/varchar/i' ,$attrib['attrib_type'])==1){
                  $datalist2 .= "<option value=\"gen:intAttribute/gen:value\"><option value=\"gen:doubleAttribute/gen:value\">";
                } else if (preg_match('/int/i' ,$attrib['attrib_type'])==1){
                  $datalist2 .= "<option value=\"gen:stringAttribute/gen:value\"><option value=\"gen:doubleAttribute/gen:value\">";
                } else if (preg_match('/double/i' ,$attrib['attrib_type'])==1){
                  $datalist2 .= "<option value=\"gen:stringAttribute/gen:value\"><option value=\"gen:intAttribute/gen:value\">";
                }
                
                $datalist2 .= "</datalist>";
              } else {
                $datalist1 = "";
                $datalist2 = "";
              }

              if ($list['editer']=='disabled="disabled"'){
                $text .= "          <td>{$v_}</td>\n";
                $text2 .= "      <input type=\"hidden\" name=\"set_attrib[$i][$m]\" value=\"{$v}\">\n";
              } else if (!empty($list['editer'])){
                $text2 .= "      <input type=\"hidden\" name=\"set_attrib[$i][$m]\" value=\"{$v}\">\n";
              } else {
                $text .= "          <td><input type=\"text\" name=\"set_attrib[$i][$m]\" value=\"{$v}\" {$list['editer']} {$datalist1}>{$datalist2}</td>\n";
              }
            }
          }
        }
      }
      $text .= "        </tr>\n";
      $i++;
    }
    $text .="
        </table>
{$text2}
";
    if (count($set_attrib)==0){
      $text .= "出力できる属性がありません。<br>";
    }
    
    $text .= static::ksk3d_box_footer($page,"","","","",array("form_id","set_feature"),array($form_id,$set_feature));

    return $text;
  }

  static function internal2citygml_exec(){
    $page = 'internal2citygml_exec';
    ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_feature = $_POST["set_feature"];
    ksk3d_console_log("set_feature");
    ksk3d_console_log($set_feature);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
      ksk3d_console_log("set_attrib1");
      ksk3d_console_log($set_attrib);
    } else {
      ksk3d_console_log("set_attrib is null");
      $set_attrib = [];
    }

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $filename = preg_replace('/\[meshcode\]/' ,'*' ,$set_feature['filename']);

    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_internal::export_mesh_citygml" ,array("citygml","CityGML",$filename) ,"" ,array($set_feature,$set_attrib));
    $text .= $result[1];

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function internal2citygml_bgexec(){
    $page = 'internal2citygml_exec';
    ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);
    $set_feature = $_POST["set_feature"];
    ksk3d_console_log("set_feature");
    ksk3d_console_log($set_feature);
    if (isset($_POST["set_attrib"])){
      $set_attrib = $_POST["set_attrib"];
      ksk3d_console_log("set_attrib1");
      ksk3d_console_log($set_attrib);
    } else {
      ksk3d_console_log("set_attrib is null");
      $set_attrib = [];
    }

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $filename = preg_replace('/\[meshcode\]/' ,'*' ,$set_feature['filename']);

    $text .= ksk3d_fn_proc::registration(
      "内部データセットをCityGMLに変換",
      "ksk3d_conv",
      6,
      array(
        $form_id,
        "",
        "ksk3d_functions_internal::export_mesh_citygml",
        array("citygml","CityGML",$filename),
        "",
        array($set_feature,$set_attrib)
      ),
      5,
      1
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
}