<?php
class ksk3d_map extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_MAP;

  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_map.css'
    ],
    'id' => 'map_id'
  ];

  static function view2(){
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
      'displayName' => 'マップID',
      'dbField' => 'map_id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => 'マップ名',
      'dbField' => 'display_name',
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
    'edit][{$form_id}' => '情報編集',
    'edit_check' => '編集内容を確認',
    'edit_exec' => '編集を確定',
    'delete_check' => '削除',
    'delete_exec' => '削除を確定',
    'map_view' => 'マップ',
    'regist' => '新規登録',
    'regist_check' => '登録内容を確認',
    'regist_exec' => '登録を確定',
   ];
  
  static $button_ = [
    array(
      'tab' => '0',
      'submit' => 'map_view',
      'displayName' => 'マップ', 
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => '0',
      'submit' => 'edit',
      'displayName' => '情報編集', 
      'th-style' => 'width:150px;',
      'td-style' => 'text-align:center',
      'status' => ''
    ),array(
      'tab' => '0',
      'submit' => 'delete_check',
      'displayName' => '削除', 
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center',
      'status' => ''
    )
  ];
  
  static $post = [
    'disp' => [
      'header-title' => 'マップ一覧',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'detail' => [
      'header-title' => 'マップ詳細情報',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit' => [
      'header-title' => 'マップ情報編集',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_check' => [
      'header-title' => 'マップ情報編集確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'edit_exec' => [
      'header-title' => 'マップ情報更新完了',
      'header-text' => '',
      'main-text' => '更新が完了しました',
      'footer-text' => ''
    ],
    'delete_check' => [
      'header-title' => 'マップ削除確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'delete_exec' => [
      'header-title' => 'マップ削除完了',
      'header-text' => '',
      'main-text' => '削除が完了しました',
      'footer-text' => ''
    ],
    'map_view' => [
      'header-title' => '',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist' => [
      'header-title' => '新規登録',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist_check' => [
      'header-title' => '登録内容を確認',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    'regist_exec' => [
      'header-title' => '登録完了',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ]
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
    'map_view' => [
    ],
    'regist' => [
    ],
    'regist_check' => [
    ],
    'regist_exec' => [
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
    'map_view' => [
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
        'class' => 'btn-primary',
        'display' => '登録画面に戻る'
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
    ]
  ];  

    
  static function map_view(){
    $page = 'map_view';

    $form_id = SELF::ksk3d_get_form_id($page);
    $text = SELF::ksk3d_box_header($page);

    $text .= SELF::ksk3d_box_main($page);

    $userID = ksk3d_get_current_user_id();

    global $wpdb;
    $tbl_name = $wpdb->prefix .KSK3D_TABLE_MAP;
    $sql = "SELECT map_id FROM {$tbl_name} WHERE id = %d;";
    $prepared = $wpdb->prepare($sql, $form_id);
    $mapID = $wpdb->get_var($prepared);
    
    $dataID = "";
    if (empty(get_option('ksk3d_option')['verify_fn'])){
      $map_url = KSK3D_MAP_URL; 
    } else {
      $map_url = KSK3D_MAP_URL_VERIFY; 
    }

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
    $text .= SELF::ksk3d_box_footer($page);

    return $text;
  }
  

  static function delete_exec(){
    $page = 'delete_exec';

    $form_id = $_POST["form_id"];

    $text = static::ksk3d_box_header($page);

    $user_id = ksk3d_get_current_user_id();

    global $wpdb;
    $tbl_name = $wpdb->prefix .static::$tbl;
    $tbl_ref = $wpdb->prefix .KSK3D_TABLE_MAP_LAYER;

    $sql = "DELETE FROM {$tbl_ref} WHERE user_id={$user_id} and map_id in (SELECT map_id FROM {$tbl_name} WHERE id = %s);";
    $dlt = $wpdb->query($wpdb->prepare($sql, $form_id));

    $sql = "DELETE FROM {$tbl_name} WHERE id = %s;";
    $dlt = $wpdb->query($wpdb->prepare($sql, $form_id));

    $text .= static::ksk3d_box_main($page);

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }




}