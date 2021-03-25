<?php
class ksk3d_data_fgd extends ksk3d_view_list{
  static $tbl = KSK3D_TABLE_DATA;

  static $setting = [
    'css' => [
      'ksk3d_style.css',
      'ksk3d_data.css'
    ],
    'id' => 'file_id',
    'where_format' => "^gml[.(.]基盤地図情報[.).]$"
  ];

  static function view2(){
    if (isset($_POST["submit"]["dataset2DB_check"])) {
      return static::dataset2DB_check();
    } else if (isset($_POST["submit"]["fgd2DB_exec"])) {
      return static::fgd2DB_exec();
    } else if (isset($_POST["submit"]["fgd2DB_bgexec"])) {
      return static::fgd2DB_bgexec();
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
    'fgd2DB_exec' => '内部データセットへ変換実行',
    'fgd2DB_bgexec' => 'バックグランドで実行',
   ];
  
  static $button_ = [
    array(
      'tab' => '1',
      'submit' => 'dataset2DB_check',
      'displayName' => '内部データセットへ変換',
      'th-style' => 'width:240px;',
      'td-style' => 'text-align:center',
      'format' => '基盤地図情報',
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
    'fgd2DB_exec' => [
      'header-title' => '内部データセットへ変換実行',
      'header-text' => 'データセットを内部データセットへ変換を実行しました。',
      'main-text' => '',
      'footer-text' => ''
    ],
    'fgd2DB_bgexec' => [
      'header-title' => '内部データセットへ変換実行（バックグラウンド処理）',
      'header-text' => 'データセットを内部データセットへ変換について、バックグラウンド処理に登録しました。',
      'main-text' => '',
      'footer-text' => ''
    ]
  ];

  static $header_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
    ],
    'fgd2DB_exec' => [
    ],
    'fgd2DB_bgexec' => [
    ]
  ];

  static $footer_button = [
    'disp' => [
    ],
    'dataset2DB_check' => [
      array(
        'submit' => 'fgd2DB_bgexec',
        'class' => 'btn-primary'
      ),
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'fgd2DB_exec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ],
    'fgd2DB_bgexec' => [
      array(
        'submit' => '',
        'class' => 'btn-secondary'
      )
    ]
  ];
  

  static function fgd2DB_exec(){
    $page = 'fgd2DB_exec';
    $form_id = $_POST["form_id"];
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);

    $result = ksk3d_conv($form_id ,"" ,"ksk3d_functions_fgd::internal" ,array("内部データセット","内部データセット"));
    $text .= $result[1];



    $result = ksk3d_fgd2DB(
      $file1,
      KSK3D_TABLE_ATTRIB .$userID ."_" .$file_id2,
      KSK3D_TABLE_GEOM .$userID ."_" .$file_id2
    );
    $text .= $result['message'];

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }

  static function fgd2DB_bgexec(){
    $page = 'fgd2DB_bgexec';
    $form_id = $_POST["form_id"];
    
    $text = static::ksk3d_box_header($page);

    $text .= static::ksk3d_box_main($page);
    $text .= ksk3d_fn_proc::registration(
      "基盤地図情報を内部データセットに変換",
      "ksk3d_conv",
      4,
      array(
        $form_id,
        "",
        "ksk3d_functions_fgd::internal",
        array("内部データセット","内部データセット")
      ),
      5,
      1
    );

    $text .= static::ksk3d_box_footer($page);

    return $text;
  }
}