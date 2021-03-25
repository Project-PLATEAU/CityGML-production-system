<?php
class ksk3d_data extends ksk3d_view_list{
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
    'id' => 'file_id'
  ];

  static function view2(){
    if (isset($_POST["submit"]["additional_file"])) {
      return static::additional_file();
    } else if (isset($_POST["submit"]["additional_file_up"])) {
      return static::additional_file_up();
    } else {
      return static::disp();
    }
  }

  static $tab = [ 
    array(
      'tab' => '1',
      'displayName' => '1概要'
    ),array(
      'tab' => '3',
      'displayName' => '2操作'
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
    ),array(
      'tab' => '1',
      'displayName' => 'ファイル名',
      'dbField' => 'file_name',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => '1',
      'displayName' => 'ファイルサイズ',
      'dbField' => 'file_size',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:right;',
      'status' => ''
    ),array(
      'tab' => '1',
      'displayName' => '登録日時',
      'dbField' => 'registration_date',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '2',
      'displayName' => 'メモ',
      'dbField' => 'memo',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    )
  ];

  static $button_value = [
    '' => '一覧に戻る',
    'detail' => '詳細',
    'edit' => '情報編集',
    'edit_check' => '編集内容を確認',
    'edit_exec' => '編集を確定',
    'delete_check' => '削除',
    'delete_exec' => '削除を確定',
    'regist_file' => '新規登録(ファイルアップロード)',
    'regist_file_uploading' => 'アップロード',
    'regist_file_up' => 'アップロード',
    'download_check' => 'ダウンロード',
    'download_exec' => 'ファイルをダウンロード',
    'DB_index_check' => 'インデックス',
    'DB_index_exec' => 'インデックスを作成実行',
    'gml2DB_check' => '内部データセットへ変換',
    'gml2db_set_attrib' => '属性項目の設定',
    'gml2db_set_high' => '高さの設定',
    'gml2DB_check2' => '内部データセットへ変換確認',
    'gml2DB_exec' => '内部データセットへ変換実行',
    'zip_extractTo' => 'ZIP解凍',
    'additional_file' => '追加アップロード',
    'additional_file_up' => '追加アップロード'
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
    ),array(
      'tab' => '3',
      'submit' => 'edit',
      'displayName' => '情報編集',
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    ),array(
      'tab' => '3',
      'submit' => 'additional_file',
      'displayName' => '追加アップロード',
      'th-style' => 'width:160px;',
      'td-style' => 'text-align:center',
      'format' => '^(?!.*(内部データセット|3DTiles))',
      'status' => ''
    ),array(

      'tab' => '3',
      'submit' => 'download_check',
      'displayName' => 'ダウンロード',
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:center',
      'format' => '^(?!.*(内部データセット|3DTiles))',
      'status' => ''
    ),array(
      'tab' => '3',
      'submit' => 'delete_check',
      'displayName' => '削除',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'format' => '\\S',
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
    'detail' => [
      'header-title' => 'データセット詳細情報',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit' => [
      'header-title' => 'データセット情報編集',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_check' => [
      'header-title' => 'データセット情報編集確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_exec' => [
      'header-title' => 'データセット更新実行',
      'header-text' => '',
      'main-text' => '更新が完了しました',
      'footer-text' => ''
    ],
    'delete_check' => [
      'header-title' => 'データセット削除確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'delete_exec' => [
      'header-title' => 'データセット削除実行',
      'header-text' => '',
      'main-text' => '削除が完了しました',
      'footer-text' => ''
    ],
    'regist_file' => [
      'header-title' => '新規登録（ファイルアップロード）',
      'header-text' => '',
      'main-text' => 'ファイルを選択した後にアップロードボタンを押してください',
      'footer-text' => ''
    ],
    'regist_file_uploading' => [
      'header-title' => 'ファイルアップロード中',
      'header-text' => '',
      'main-text' => '画面を閉じないでください。',
      'footer-text' => ''
    ],
    'regist_file_up' => [
      'header-title' => 'ファイルアップロード完了',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'download_check' => [
      'header-title' => 'ダウンロード確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'download_exec' => [
      'header-title' => 'ダウンロード実行',
      'header-text' => '',
      'main-text' => 'ダウンロードを実行しました',
      'footer-text' => ''
    ],
    'gml2DB_check' => [
      'header-title' => '内部データセットへ変換確認',
      'header-text' => '次のデータセットを内部データセットへ変換します。よろしければ設定に進んでください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2db_set_attrib' => [
      'header-title' => '属性項目の設定',
      'header-text' => '属性項目を設定してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'gml2db_set_high' => [
      'header-title' => '高さの設定',
      'header-text' => '高さの設定方法を選択してください。',
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
    'zip_extractTo' => [
      'header-title' => 'ZIP解凍',
      'header-text' => 'ZIPファイルの解凍を実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'additional_file' => [
      'header-title' => '追加アップロード',
      'header-text' => 'ファイルを選択した後にアップロードボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'additional_file_up' => [
      'header-title' => '追加ファイルアップロード完了',
      'header-text' => '',
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
      array(
        'submit' => 'regist_file',
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
    'regist_file' => [
    ],
    'regist_file_uploading' => [
    ],
    'regist_file_up' => [
    ],
    'download_check' => [
    ],
    'download_exec' => [
    ],
    'gml2DB_check' => [
    ],
    'gml2db_set_attrib' => [
    ],
    'gml2db_set_high' => [
    ],
    'gml2DB_check2' => [
    ],
    'gml2DB_exec' => [
    ],
    'zip_extractTo' => [
    ],
    'additional_file' => [
    ],
    'additional_file_up' => [
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
        'class' => 'btn-primary',
        'display' => '編集画面に戻る'
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
    'regist_file' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
   ],
    'regist_file_uploading' => [
   ],
    'regist_file_up' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'download_check' => [
      array(
        'submit' => 'download_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'download_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'gml2DB_check' => [
      array(
        'submit' => 'gml2db_set_attrib',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
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
    'gml2db_set_high' => [
      array(
        'submit' => 'gml2DB_check2',
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
    'gml2DB_check2' => [
      array(
        'submit' => 'gml2DB_exec',
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
        'class' => 'btn-secondary',
      )
    ],
    'zip_extractTo' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'additional_file' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'additional_file_up' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];
  
  static function delete_exec(){
    $page = 'delete_exec';

    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $sql = "SELECT file_id,file_name,file_path FROM {$tbl_name} WHERE id = {$form_id}";
    $file = $wpdb->get_row($sql ,ARRAY_A);

    ksk3d_dataset_delete($file['file_id']);
    
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function additional_file(){
    $page = 'additional_file';

    $form_id = static::ksk3d_get_form_id($page);

    $text = static::ksk3d_box_header($page);
    $text .= "  </form>";
    $ck_disabled = "";
    if (!ksk3d_check_usedsize()){
      $text .= KSK3D_USEDSIZE_ERR_MES;
      $ck_disabled = " disabled";
    }

    $kmfs = KSK3D_MAX_FILE_SIZE;
    $text .=<<< EOL
      <form target="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_id" value="{$form_id}">
        <input type="hidden" name="MAX_FILE_SIZE" value="{$kmfs}">
        <input type="file" name="upfilename" />
        <input type="submit" value="アップロード" {$ck_disabled}>
        <input type="hidden" name="submit[additional_file_up]" value="on">
      </form>
EOL
;
    update_user_meta( ksk3d_get_current_user_id(), "ksk3d_token", "1"); 

    $text .= static::ksk3d_box_footer($page ,'option0' ,'     <form action="" method="post">' ,$form_id);

    return $text;
  }

  static function additional_file_up(){
    $page = 'additional_file_up';
    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    $user_id = ksk3d_get_current_user_id();
    if (get_user_meta($user_id, "ksk3d_token" ,true) == "1"){

      if ($_FILES["upfilename"]["error"]==0){
        if (is_uploaded_file($_FILES["upfilename"]["tmp_name"])) {
          $data = ksk3d_fn_db::sel_data($form_id);
          $file_id = $data['file_id'];
          $upload_dir = ksk3d_upload_dir() ."/" .$file_id;
          if (! file_exists($upload_dir)){
            mkdir($upload_dir);
            chmod($upload_dir, 0777);
          }
          
          $upload_info = pathinfo($_FILES["upfilename"]["name"]);
          $file = $upload_dir ."/" .$_FILES["upfilename"]["name"];
          if (! is_file($file)){
            move_uploaded_file($_FILES["upfilename"]["tmp_name"], $file);

            $zip_pathinfo = ksk3d_functions_zip::pathinfo($file_id);
            $zip_file = $zip_pathinfo['fullpath'];
            if (is_file($zip_file)){
              if (preg_match('/^zip$/i',$upload_info['extension'])==1){
                ksk3d_functions_zip::filemoveall($file ,$zip_file);
                unlink($file);
              } else {
                $zip = new ZipArchive();
                $res = $zip->open($zip_file);
                $zip->addFile($file, substr($file ,mb_strlen($upload_dir)+1));
                $zip->close();
                unlink($file);
              }
            } else {
              if (preg_match('/^zip$/i',$upload_info['extension'])==1){
                if (move_uploaded_file($_FILES["upfilename"]["tmp_name"], $file)) {
                  chmod($zip_file, 0777);
                }
              } else {
                ksk3d_fileid_zip_Compress($file_id ,true);
              }
            }
            $message = "ファイルをアップロードしました";

            update_user_meta($user_id ,"ksk3d_token" ,"2");

          } else {
            $message = "ファイルは既に存在します";
          }
        } else {
          $message = "ファイルのアップロードが失敗しました";
        }
      } else {
        $message = "ファイルのアップロードが失敗しました";
      }
    } else {
      $message = "ファイルは既にアップロードされています";
    }

    $text .= static::ksk3d_box_main($page);

    $text .= $message;

    $text .= static::ksk3d_box_footer($page ,"" ,"" ,$form_id);

    return $text;
  }

}