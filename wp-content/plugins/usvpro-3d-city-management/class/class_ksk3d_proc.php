<?php
class ksk3d_proc extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_PROC;

  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_check.css'
    ],
    'where' => [
      '1' => " status in ('登録','待機','処理中','キャンセル中')",
      '2' => " 1"
    ],
    'order' => 'proc_id'
  ];

  static function view2(){
    if (isset($_POST["submit"]["proc_cancel"])) {
      return static::proc_cancel();
    } else if (isset($_POST["submit"]["proc_cancel_exec"])) {
      return static::proc_cancel_exec();
    } else if (isset($_POST["submit"]["proc_reprocess"])) {
      return static::proc_reprocess();
    } else if (isset($_POST["submit"]["proc_reprocess_exec"])) {
      return static::proc_reprocess_exec();
    } else {
      return static::disp();
    }
  }

  static $tab = [//0:常時表示 //ksk3d_data::viewは直接修正が必要
      array(
      'tab' => '1',
      'displayName' => '1未処理'
    ),array(
      'tab' => '2',
      'displayName' => '2全て'
/*
    ),array(
      'tab' => '3',
      'displayName' => '3操作'
*/
    )
  ];
  
  static $field = [//editerに文字列が入ると更新不可
    array(
      'tab' => '0',
      'displayName' => '処理ID',
      'dbField' => 'id',
      'editer' => 'disabled="disabled"',
      'format' => '%d',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
/*      'tab' => '0',
      'displayName' => 'ユーザ',
      'dbField' => 'user_id',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:200px;',
      'td-style' => '',
      'status' => ''
    ),array(
*/      'tab' => '0',
      'displayName' => '処理内容',
      'dbField' => 'process_disp',
      'editer' => '',
      'format' => '%s',
      'th-style' => 'width:300px;',
      'td-style' => '',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => 'ステータス',
      'dbField' => 'status',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:100px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => '登録日時',
      'dbField' => 'registration_date',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:180px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '0',
      'displayName' => '開始日時',
      'dbField' => 'proc_start_data',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:180px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    ),array(
      'tab' => '2',
      'displayName' => '終了日時',
      'dbField' => 'proc_end_data',
      'editer' => 'disabled="disabled"',
      'format' => '%s',
      'th-style' => 'width:180px;',
      'td-style' => 'text-align:center;',
      'status' => ''
    )
  ];

  //ボタンの処理と表示名は統一しておかないとエラー
  static $button_value = [
    '' => '一覧に戻る',
    'proc_cancel' => 'キャンセル',
    'proc_cancel_exec' => 'キャンセル実行',
    'proc_reprocess' => '再処理',
    'proc_reprocess_exec' => '再処理実行'
   ];

  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'proc_cancel',
      'displayName' => 'キャンセル',
      'th-style' => 'width:120px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => ''
    ),
    array(
      'tab' => '2',
      'submit' => 'proc_reprocess',
      'displayName' => '再処理',
      'th-style' => 'width:90px;',
      'td-style' => 'text-align:center',
      'format' => '',
      'status' => 'dev'
    ),
  ];

  static $post = [
    //一覧表
    'disp' => [
      'header-title' => 'バックグラウンド処理一覧',
      'header-text' => '',
      'main-text' => '',
      'footer-text' => ''
    ],
    //キャンセル
    'proc_cancel' => [
      'header-title' => 'キャンセル確認（バックグラウンド処理一覧）',
      'header-text' => '次のバックグラウンド処理をキャンセルします。よろしければ実行ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //キャンセル
    'proc_cancel_exec' => [
      'header-title' => 'キャンセル実行（バックグラウンド処理一覧）',
      'header-text' => 'バックグラウンド処理のキャンセルを実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //再処理の確認
    'proc_reprocess' => [
      'header-title' => '再処理の確認（バックグラウンド処理一覧）',
      'header-text' => '次のバックグラウンド処理を再処理します。<br>
なお、データセットが削除されている等により内部でエラーが発生した場合は、ステータスが処理中のままとなり、処理は完了しません。<br>
よろしければ実行ボタンを押してください。',
      'main-text' => '',
      'footer-text' => ''
    ],
    //再処理の実行
    'proc_reprocess_exec' => [
      'header-title' => '再処理の実行（バックグラウンド処理一覧）',
      'header-text' => 'バックグラウンド処理の再処理を登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];
  
  static $header_button = [
    'disp' => [//一覧表
    ],
    'proc_cancel' => [//キャンセル
    ],
    'proc_cancel_exec' => [//キャンセル実行
    ],
    'proc_reprocess' => [//再処理の確認
    ],
    'proc_reprocess_exec' => [//再処理の実行
    ]
  ];

  static $footer_button = [
    'disp' => [//一覧表
    ],
    'proc_cancel' => [//キャンセル
      array(
        'submit' => 'proc_cancel_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'proc_cancel_exec' => [//キャンセル実行
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'proc_reprocess' => [//再処理の確認
      array(
        'submit' => 'proc_reprocess_exec',
        'class' => 'btn-primary',
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ],
    'proc_reprocess_exec' => [//再処理の実行
      array(
        'submit' => '',
        'class' => 'btn-secondary',
      )
    ]
  ];

/**
  * キャンセルの確認
  */  
  static function proc_cancel(){
    $page = 'proc_cancel';
    //ksk3d_console_log("page:".$page);

    //押されたボタンのIDを取得する
    $form_id = static::ksk3d_get_form_id($page);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //ファイル名
    $text .= static::detail_disp($form_id);
//    $text .= ksk3d_table_2rows(array('ファイル名',$file_info['file_name']));

    //フッターボックス
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,"" ,"" ,"form_id" ,$form_id);

    return $text;
  }
  
/**
  * キャンセル実行
  */  
  static function proc_cancel_exec(){
    $page = 'proc_cancel_exec';
    //ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //キャンセル
    $text .= ksk3d_fn_proc::cancel($form_id ,"ユーザによってキャンセルされました.");

    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
  
/**
  * 再処理の確認
  */  
  static function proc_reprocess(){
    $page = 'proc_reprocess';
    //ksk3d_console_log("page:".$page);

    //押されたボタンのIDを取得する
    $form_id = static::ksk3d_get_form_id($page);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //ファイル名
    $text .= static::detail_disp($form_id);

    //フッターボックス
    $text .= static::ksk3d_box_footer($page ,"" ,"" ,"" ,"" ,"form_id" ,$form_id);

    return $text;
  }
  
/**
  * 再処理の実行
  */  
  static function proc_reprocess_exec(){
    $page = 'proc_reprocess_exec';
    //ksk3d_console_log("page:".$page);
    $form_id = $_POST["form_id"];
    ksk3d_console_log("form_id:".$form_id);

    //ヘッダーボックス
    $text = static::ksk3d_box_header($page);

    //メインボックス
    $text .= static::ksk3d_box_main($page);

    //キャンセル
    $text .= ksk3d_fn_proc::reprocess($form_id);

    //フッターボックス
    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
}