<?php
class ksk3d_functions_citygml{
  static function attrib2generic($file1 ,$file2 ,$set_attrib){
    ksk3d_log("fn:ksk3d_functions_citygml::attrib2generic:({$file1} ,{$file2})");

    $doc = new DOMDocument();
    $doc->load($file1);

    $CityModel = $doc->getElementsByTagName('CityModel');
    $uri_gen = $CityModel->item(0)->getAttribute("xmlns:gen");
    if (empty($uri_gen)){
      $CityModel->item(0)->setAttribute("xmlns:gen" ,"http://www.opengis.net/citygml/generics/2.0");
      $CityModel->item(0)->setAttribute("xsi:schemaLocation" ,
        $CityModel->item(0)->getAttribute("xsi:schemaLocation")." http://www.opengis.net/citygml/generics/2.0 http://schemas.opengis.net/citygml/generics/2.0/generics.xsd"
      );
    }
    
    $cityObjectMember = $doc->getElementsByTagName('cityObjectMember');
    $xpath = new DOMXpath($doc);

    foreach ($cityObjectMember as $cityObject){
      $feature_path = $cityObject->getNodePath()."/";
      foreach($set_attrib as $attrib){
        $attrib['tag_path'] = preg_replace("/\\\\+'/" ,"'" ,$attrib['tag_path']);
        if (preg_match('/geometry/i' ,$attrib['attrib_type'])!=1){
            echo "3-".$attrib['tag_path']."<br>";
            ksk3d_console_log("attrib['tag_path']:".$attrib['tag_path']);
            if ((!empty($attrib['tag_path'])) and ($xpath->evaluate($attrib['tag_path'] ,$cityObject) != false)){
              $query = $xpath->query($attrib['tag_path'] ,$cityObject);
              echo "query-".$query->item(0)->nodeValue."<br>";
              if ($query->length > 0){
                if (preg_match('{/gen:[^/]+attribute(\[.+?\])?/gen:value}i' ,$query->item(0)->getNodePath()) != 1){
                  $tag = $query->item(0)->nodeValue;
                  if (preg_match('/int/i',$attrib['attrib_type'])){
                    $tag2 = "gen:intAttribute";
                  } else if (preg_match('/double/i',$attrib['attrib_type'])){
                    $tag2 = "gen:doubleAttribute";
                  } else {
                    $tag2 = "gen:stringAttribute";
                  }

                  $element = $doc->createElement($tag2);
                  $element->appendChild($doc->createElement("gen:value" ,$tag));
                  
                  $node_class = get_class($query->item(0));
                  if ($node_class != "DOMAttr"){
                    foreach($query->item(0)->attributes as $attrib2){
                      $element->setAttribute($attrib2->name ,$attrib2->value);
                    }
                  }
                  $element->setAttribute('name' ,$attrib['attrib_name']);

                  echo "test44<br>";
                  foreach ($cityObject->childNodes as $c){
                    $feature_path2 = $c;
                    if (get_class($c)=='DOMElement') {break;}
                  }
                  if ($node_class == "DOMAttr"){
                    $feature_path2->appendChild($element);
                  } else {
                    if ($feature_path2->getNodePath() == $query->item(0)->parentNode->getNodePath()){
                      $query->item(0)->parentNode->replaceChild($element ,$query->item(0));
                    } else {
                      $query->item(0)->parentNode->removeChild($query->item(0));
                      $feature_path2->appendChild($element);
                    }
                  }
                }
              }
            }
        }
      }
    }

    $doc->save($file2);
    return true;
  }

  static function attrib_mod_op($file1 ,$file2 ,$set_attrib ,$rule){
    ksk3d_log("fn:ksk3d_functions_citygml::attrib_mod:({$file1} ,{$file2})");
    $flg_sort = false;

    $set_attrib2 = array_reverse($set_attrib);

    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    $doc->load($file1);
    $xpath = new DOMXpath($doc);
    $cityObjectMembers = $doc->getElementsByTagName('cityObjectMember');
    foreach ($cityObjectMembers as $cityObjectMember){
      $cityObjectMember_path = $cityObjectMember->getNodePath()."/";
      ksk3d_console_log("cityObjectMember_path:" .$cityObjectMember_path);

      foreach($set_attrib2 as $attrib){
        $tag_path = stripslashes($attrib['tag_path']);
        ksk3d_console_log("tag_path:" .$tag_path);
        $entries = $xpath->query($tag_path ,$cityObjectMember);
        if ($entries->length > 0){
          for ($i1=0; $i1>=0; --$i1){
            $entry = $entries->item($i1);
            ksk3d_console_log("getNodePath:" .$entry->getNodePath());
            if (preg_match('/削除/' ,$attrib['rule'])==1){
              if (preg_match('/gen\:.+attribute/i' ,$entry->parentNode->nodeName)==1){
                $node2 = $entry->parentNode->parentNode;
                $entry->parentNode->parentNode->removeChild($entry->parentNode);
              } else if (preg_match('/KeyValuePair/i' ,$entry->parentNode->nodeName)==1){
                $node2 = $entry->parentNode->parentNode;
                $entry->parentNode->parentNode->removeChild($entry->parentNode);
              } else {
                $node2 = $entry->parentNode;
                $entry->parentNode->removeChild($entry);
              }
              
              ksk3d_dataset_gml::remove_blanknode($node2);

            } else if (preg_match('/四捨五入/' ,$attrib['rule'])==1){
              $v1 = $entry->nodeValue;
              $entry->nodeValue = round($v1/$rule[$attrib['rule_id']]['round'])*$rule[$attrib['rule_id']]['round'];

            } else if (preg_match('/階級区分/' ,$attrib['rule'])==1){
              ksk3d_console_log("rule:kaikyu");
              if (preg_match('/KeyValuePair/i' ,$entry->parentNode->nodeName)==1){
                $v1 = $entry->parentNode->childNodes->item(1)->firstChild->nodeValue;
                $v2 = $v1;
              } else {
                $v1 = $entry->nodeValue;
                $v2 = $v1;
              }
              if (preg_match('/^[^\s]+$/i' ,$v1)==1){
                for ($i=0;$i<$rule[$attrib['rule_id']]['divisions'];$i++){
                  if ((float)$v1 >= $rule[$attrib['rule_id']][$i]['v']){
                    $v2 = $rule[$attrib['rule_id']][$i]['label'];
                  } else {
                    break;
                  }
                }
              }
              $element = $doc->createElement("gen:stringAttribute");
              $element->appendChild($doc->createElement("gen:value" ,$v2));
              $element->setAttribute('name' ,stripslashes($rule[$attrib['rule_id']]['name']));
              ksk3d_console_log("name:".stripslashes($rule[$attrib['rule_id']]['name']));

              if (preg_match('/gen\:.+attribute/i' ,$entry->parentNode->nodeName)==1){
                $entry->parentNode->parentNode->replaceChild($element ,$entry->parentNode);
              } else {
                if (preg_match('/KeyValuePair/i' ,$entry->parentNode->nodeName)==1){
                  $node2 = $entry->parentNode->parentNode;
                } else {
                  $node2 = $entry;
                }
                ksk3d_console_log("元のノードの削除:" .$node2->getNodePath());
                $node2->parentNode->removeChild($node2);
                ksk3d_dataset_gml::remove_blanknode($node2);

                $tag_path_root = ".//".preg_replace('{^(.+?)/.*$}' ,'$1' ,$tag_path."/");
                $cityObjects2 = $xpath->query($tag_path_root ,$cityObjectMember);
                if ($cityObjects2->length > 0){
                  $feature_path2 = $cityObjects2->item(0);
                  ksk3d_console_log("追加先のノード2:".$feature_path2->getNodePath());
                  $node2 = $feature_path2->appendChild($element);
                } else {
                  ksk3d_console_log("追加先のノードが見つかりません。:".$tag_path_root);
                }
              }
              $flg_sort = true;
            }
          }
        }
      }
    }
    if ($flg_sort){
      $CityModel = $doc->getElementsByTagName('CityModel');
      $uri_gen = $CityModel->item(0)->getAttribute("xmlns:gen");
      if (empty($uri_gen)){
        $CityModel->item(0)->setAttribute("xmlns:gen" ,"http://www.opengis.net/citygml/generics/2.0");
        $CityModel->item(0)->setAttribute("xsi:schemaLocation" ,
          $CityModel->item(0)->getAttribute("xsi:schemaLocation")." http://www.opengis.net/citygml/generics/2.0 http://schemas.opengis.net/citygml/generics/2.0/generics.xsd"
        );
      }
      $doc->save($file2);

      ksk3d_functions_citygml::tagsort($file2, $file2);
    } else {
      $doc->save($file2);
    }
    return true;
  }

  static function citygml2DB($filename, $tbl_attrib, $tbl_geom, $set_attrib)
  {
    ksk3d_console_log("filename1:" . $filename);
    global $wpdb;

    $i = 0;
    $i2 = 0;
    $sql_a = "";
    $sql_g = "";

    $sql_a_field = "";
    // GEOMETRY型以外の属性名を取得してSQL用のフィールド名リストとする
    foreach ($set_attrib as $attrib) {
      if (preg_match('/geometry/i', $attrib["attrib_type"]) == 0) {
        $sql_a_field .= $attrib["field_name"] . ",";
      }
    }
    $sql_a_field = substr($sql_a_field, 0, -1);
    ksk3d_console_log("sql_a");
    ksk3d_console_log($sql_a_field);

    $doc = new DOMDocument();
    $doc->load($filename);
    $cityObjectMember = $doc->getElementsByTagName('cityObjectMember');
    $xpath = new DOMXpath($doc);

    foreach ($cityObjectMember as $cityObject) {
      $feature_path = $cityObject->getNodePath() . "/";
      $sql_a_value = "";
      // exteriorの座標リスト
      $coord = ""; 
      // interiorの座標リスト
      $coord2 = "";
      $zmin = null;
      $zmax = null;
      $zmin1 = "";
      $zmax1 = "";
      foreach ($set_attrib as $attrib) {
        if (preg_match('/geometry/i', $attrib['attrib_type']) == 1) {
          // ジオメトリ型だった場合

          // タグのパスの先頭要素を置換 bldg:Building/bldg:lod1Solid -> .//bldg:lod1Solid
          $tag_path = preg_replace('/^.+?\//', './/', $attrib['tag_path']);
          // パスを追加 bldg:Building/bldg:lod1Solid -> .//bldg:lod1Solid//gml:exterior//gml:posList
          // exteriorのposListを検索
          $geom = $xpath->query($tag_path . '//gml:exterior//gml:posList', $cityObject);
          if (count($geom) > 0) {
            $coord = "";
            $coord2 = "";
            if (preg_match('{/gml:exterior/.+/gml:exterior/}', $geom[0]->getNodePath()) == 1) {
              // exteriorの場合？
              
              // 先頭のデータのみ処理
              $g = $geom[0];
              // XYを反転させて、Zを消して、XYのカンマ区切りにする（X Y Z X Y Zという並びだった場合Y X,Y Xとなる）
              $xy = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/', '${2} ${1},', trim($g->nodeValue) . " ");
              $xy = preg_replace('/^,|,(\t*?)$/', '', $xy);
              $coord = "({$xy}),";

              // ジオメトリ全てに対して処理
              foreach ($geom as $g) {
                // Zのみのデータを作成する
                $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/', '${3},', trim($g->nodeValue) . " ");
                $z_array = explode(',', substr($z, 0, -1));
                // Zの最大最小を求める
                $zmin1 = min($z_array);
                $zmax1 = max($z_array);
                if (is_null($zmin)) {
                  $zmin = $zmin1;
                  $zmax = $zmax1;
                } else {
                  if ($zmin > $zmin1) {
                    $zmin = $zmin1;
                  }
                  if ($zmax < $zmax1) {
                    $zmax = $zmax1;
                  }
                }
              }
              // interiorのposListを検索
              $tag_path = preg_replace('/^.+?\//', './/', $attrib['tag_path']);
              $geom = $xpath->query('.//gml:interior//gml:posList', $g->parentNode->parentNode->parentNode->parentNode);
              if (count($geom) > 0) {
                $g = $geom[0];
                $xy = implode("\n", array_reverse(explode("\n", trim($g->nodeValue))));
                $xy = preg_replace("/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/", '${2} ${1},', $xy . " ");
                $xy = preg_replace('/^,|,(\t*?)$/', '', $xy);
                $coord2 = "({$xy})";

                foreach ($geom as $g) {
                  $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/', '${3},', trim($g->nodeValue) . " ");
                  $z_array = explode(',', substr($z, 0, -1));
                  $zmin1 = min($z_array);
                  $zmax1 = max($z_array);
                  if (is_null($zmin)) {
                    $zmin = $zmin1;
                    $zmax = $zmax1;
                  } else {
                    if ($zmin > $zmin1) {
                      $zmin = $zmin1;
                    }
                    if ($zmax < $zmax1) {
                      $zmax = $zmax1;
                    }
                  }
                }
                $coord2 = "ST_GeomFromText('MULTIPOLYGON((" . $coord2 . "))', 4326)";
              } else {
                $coord2 = "NULL";
              }
            } else {
              // exterior以外の場合？

              foreach ($geom as $g) {
                $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/', '${3},', trim($g->nodeValue) . " ");
                $z_array = explode(',', substr($z, 0, -1));
                $zmin1 = min($z_array);
                $zmax1 = max($z_array);
                if (is_null($zmin)) {
                  $zmin = $zmin1;
                  $zmax = $zmax1;
                } else {
                  if ($zmin > $zmin1) {
                    $zmin = $zmin1;
                  }
                  if ($zmax < $zmax1) {
                    $zmax = $zmax1;
                  }
                }

                // こちらは全ての座標要素を格納している？
                $xy = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/', '${2} ${1},', trim($g->nodeValue) . " ");
                $xy = preg_replace('/^,|,(\t*?)$/', '', $xy);
                $coord .= "({$xy}),";
              }

              $tag_path = preg_replace('/^.+?\//', './/', $attrib['tag_path']);
              $geom = $xpath->query($tag_path . '//gml:interior//gml:posList', $cityObject);
              if (count($geom) > 0) {
                foreach ($geom as $g) {
                  $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/', '${3},', trim($g->nodeValue) . " ");
                  $z_array = explode(',', substr($z, 0, -1));
                  $zmin1 = min($z_array);
                  $zmax1 = max($z_array);
                  if (is_null($zmin)) {
                    $zmin = $zmin1;
                    $zmax = $zmax1;
                  } else {
                    if ($zmin > $zmin1) {
                      $zmin = $zmin1;
                    }
                    if ($zmax < $zmax1) {
                      $zmax = $zmax1;
                    }
                  }

                  $xy = implode("\n", array_reverse(explode("\n", trim($g->nodeValue))));
                  $xy = preg_replace("/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/", '${2} ${1},', $xy . " ");
                  $xy = preg_replace('/^,|,(\t*?)$/', '', $xy);
                  $coord2 .= "({$xy}),";
                }
                $coord2 = "ST_GeomFromText('MULTIPOLYGON((" . substr($coord2, 0, -1) . "))', 4326)";
              } else {
                $coord2 = "NULL";
              }

              if (is_null($zmin)) {
                $zmin = 0;
                $zmax = 0;
              }
            }
          }
        } else {
          // GEOMETRY型以外の場合
          if (isset($attrib['tag_attrib']) && !empty($attrib['tag_attrib'])) {
            $tag_path = preg_replace('/(Attribute)\[.*?\]/i', '$1[@' . $attrib['tag_attrib'] . '=\'' . $attrib['tag_attrib_name'] . '\']', $attrib['tag_path']);
            $tag_path = preg_replace('/^.+?\//', './/', $tag_path);
            $query = $xpath->query($tag_path, $cityObject);
            if ($query->length > 0) {
              $tag = $query->item(0)->nodeValue;
            } else {
              $tag = "NULL";
            }
          } else {
            $path = preg_replace("/^.+?\//", './/', $attrib['tag_path']);
            $query = $xpath->query($path, $cityObject);
            if ($query->length > 0) {
              $tag = $query->item(0)->nodeValue;
            } else {
              $tag = "NULL";
            }
          }
          if (preg_match('/char/i', $attrib["attrib_type"]) and $tag != "NULL") {
            $tag = "'" . $tag . "'";
          }
          $sql_a_value .= "," . $tag;
        }
      }
      if (!empty($coord)) {
        $i++;

        if (is_null($zmin)) {
          $zmin = 0;
          $zmax = 0;
        }
        
        // GEOMETRY型をWKTに変換して挿入するテキストを作成
        $sql_g .= "\n(ST_GeomFromText('MULTIPOLYGON((" . substr($coord, 0, -1) . "))', 4326) ,{$coord2} ,'{$zmin}' ,'" . ($zmax - $zmin) . "'),";
        $sql_a .= "\n(" . substr($sql_a_value, 1) . "),";
      }

      if ($i > 99) {
        $i2 += $i;
        $i = 0;
        if (!empty($sql_a_field)) {
          if (!empty($sql_g)) {
            $sql_a = "INSERT INTO {$tbl_attrib} ({$sql_a_field}) VALUES " . substr($sql_a, 0, -1);
            ksk3d_log("sql:" . $sql_a);
            $wpdb->query($sql_a);
            $sql_a = "";
            $sql_g = "INSERT INTO {$tbl_geom} (the_geom ,hole ,z ,m) VALUES " . substr($sql_g, 0, -1);
            ksk3d_log("sql:" . $sql_g);
            $wpdb->query($sql_g);
            $sql_g = "";
          }
        }
      }
    }
    if ($i > 0) {
      $i2 += $i;
      if (!empty($sql_a_field)) {
        if (!empty($sql_g)) {
          $sql_a = "INSERT INTO {$tbl_attrib} ({$sql_a_field}) VALUES " . substr($sql_a, 0, -1);
          ksk3d_log("sql:" . $sql_a);
          $wpdb->query($sql_a);
          $sql_g = "INSERT INTO {$tbl_geom} (the_geom ,hole ,z ,m) VALUES " . substr($sql_g, 0, -1);
          ksk3d_log("sql:" . $sql_g);
          $wpdb->query($sql_g);
        }
      }
    }
    $message = "データを" . $i2 . "行追加しました。<br>\n";
    ksk3d_log("insert:{$i2}行追加しました。");

    return array(
      true,
      'message' => $message
    );
  }

  static function cityobject2building($file1 ,$file2 ,$set_attrib){
    ksk3d_log("fn:ksk3d_citygml::cityobject2building:");
    ksk3d_log("  \$file1: $file1");
    ksk3d_log("  \$file2: $file2");
    $template = KSK3D_PATH ."/storage/citygml/building.gml";
    ksk3d_console_log("template:".$template);
    $doc2 = new DOMDocument();
    $doc2->preserveWhiteSpace = false;
    $doc2->formatOutput = true;
    $doc2->load($template);
    $xpath2 = new DOMXpath($doc2);

    $nodeList = $doc2->getElementsByTagName('CityModel');
    $CityModel2 = $nodeList->item(0);

    $cityObjectMember2 = $doc2->getElementsByTagName('cityObjectMember');
    $cityObjectMember2_1 = $cityObjectMember2->item(0);
    $str_cityObjectMember2 = $cityObjectMember2_1->nodeName;
    $path_cityObjectMember2 = $cityObjectMember2_1->getNodePath();
    $path_exterior = $xpath2->query("//gml:exterior//gml:posList" ,$cityObjectMember2_1)->item(0)->getNodePath();
    $path_exterior = substr($path_exterior ,strlen($path_cityObjectMember2)+1);
    $path_exterior = preg_replace('/^.+?\/|\[\d+\]/' ,'' ,$path_exterior);
    ksk3d_console_log("path_exterior:".$path_exterior);
    $CityModel2->removeChild($cityObjectMember2_1);

    $doc = new DOMDocument();
    $doc->load($file1);
    $xpath = new DOMXpath($doc);

    $coord_min = $xpath->query("//gml:Envelope/gml:lowerCorner")->item(0)->nodeValue;
    $xyz = explode(" " ,$coord_min);
    $reverse_xy = ($xyz[1]>90);

    $coord_min = ksk3d_dataset_gml::poslist_xyz_yxz($coord_min ,$reverse_xy);
    $xpath2->query("//gml:Envelope/gml:lowerCorner")->item(0)->nodeValue = $coord_min;
    
    $coord_max = $xpath->query("//gml:Envelope/gml:upperCorner")->item(0)->nodeValue;
    $coord_max = ksk3d_dataset_gml::poslist_xyz_yxz($coord_max ,$reverse_xy);
    $xpath2->query("//gml:Envelope/gml:upperCorner")->item(0)->nodeValue = $coord_max;


    $cityObjectMembers = $doc->getElementsByTagName('cityObjectMember');
    foreach ($cityObjectMembers as $cityObjectMember){
      $path_cityObjectMember = $cityObjectMember->getNodePath();
      $elem_cityObject2 = $doc2 ->createElement($str_cityObjectMember2);
      $cityObject2 = $CityModel2->appendChild($elem_cityObject2);
      foreach ($cityObjectMember->childNodes as $cityObject){
      if (get_class($cityObject) == "DOMElement" ){
        $elem_bldg = $doc2->createElement("bldg:Building");
        $bldg = $cityObject2->appendChild($elem_bldg);
        foreach ($cityObject->childNodes as $attrib){ 
        if (get_class($attrib) == "DOMElement" ){
          if (preg_match('/:lod[\-0-9]/i',$attrib->getNodePath())==1){
            $geom = $xpath->query($attrib->nodeName.'//gml:posList' ,$cityObject);
            if (count($geom)>0){
              $i = 0;
              foreach ($geom as $g){
                $posList = preg_replace("/\s+/" ,' ' ,ksk3d_dataset_gml::poslist_xyz_yxz($g->nodeValue ,$reverse_xy));
                if ($i==0){
                  $i=1;
                  $base_node = $bldg;
                  $path_exterior2 = $path_exterior;
                  $node = ksk3d_dataset_gml::node_appendChild_path($doc2 ,$base_node ,$path_exterior2);
                  $node->nodeValue = $posList;
                } else {
                  if (preg_match('/gml\:interior/' ,$g->getNodePath())!=1){
                    $base_node = $node->parentNode->parentNode->parentNode->parentNode->parentNode;
                    $path_exterior2 = "gml:surfaceMember/gml:Polygon/gml:exterior/gml:LinearRing/gml:posList";
                    $node = ksk3d_dataset_gml::node_appendChild_path($doc2 ,$base_node ,$path_exterior2);
                    $node->nodeValue = $posList;
                  } else {
                    $node->nodeValue = ksk3d_dataset_gml::poslist_insert_hole($node->nodeValue ,$posList);
                  }
                }
              }
            }
          }
        }}
        foreach($set_attrib as $attrib){
          $attrib['tag_path'] = preg_replace("/\\\\+'/" ,"'" ,$attrib['tag_path']);
          if (preg_match('/geometry/i' ,$attrib['attrib_type'])!=1){

            ksk3d_console_log("attrib['tag_path']:".$attrib['tag_path']);
            if ((!empty($attrib['tag_path'])) and ($xpath->evaluate($attrib['tag_path'] ,$cityObject) != false)){
              $tag_path = preg_replace('/^.+?\//' ,'' ,$attrib['tag_path']);
              $query = $xpath->query($tag_path ,$cityObject);
              if ($query->length > 0){
                if (preg_match('{/gen:[^/]+attribute(\[.+?\])?/gen:value}i' ,$query->item(0)->getNodePath()) != 1){

                  $tag = $query->item(0)->nodeValue;

                  if (preg_match('/int/i',$attrib['attrib_type'])){
                    $tag2 = "gen:intAttribute";
                  } else if (preg_match('/double/i',$attrib['attrib_type'])){
                    $tag2 = "gen:doubleAttribute";
                  } else {
                    $tag2 = "gen:stringAttribute";
                  }

                  $element = $doc2->createElement($tag2);
                  $element->appendChild($doc2->createElement("gen:value" ,$tag));
                  
                  $node_class = get_class($query->item(0));
                  if ($node_class != "DOMAttr"){
                    foreach($query->item(0)->attributes as $attrib2){
                      $element->setAttribute($attrib2->name ,$attrib2->value);
                    }
                  }
                  $element->setAttribute('name' ,$attrib['attrib_name']);
                  $bldg->appendChild($element);
                }
              }
            }
          }
        }
        $query = $xpath->query("@gml:id" ,$cityObject);
        if ($query->length > 0){
          $gml_id = $query->item(0);
          $v_gml_id = $gml_id->value;
          $path_gml_id = $gml_id->parentNode->getNodePath();
          $path_gml_id = substr($path_gml_id ,strlen($path_cityObjectMember)+1);
          $path_gml_id = preg_replace('/^.+?\//' ,'' ,$path_gml_id."/");
          if (empty($path_gml_id)){
            $bldg->setAttribute("gml:id" ,$v_gml_id);
          } else {
            $attr = $xpath2->query($path_gml_id ,$cityObject2);
            $attr->item(0)->setAttribute("gml:id" ,$v_gml_id);
          }
        }
      }}
    }

    $doc2->save($file2);
    return true;
  }

  static function internal($file1 ,$new_id ,$set_attrib ,$dmy ,$sw_header){
    $user_id = ksk3d_get_current_user_id();  
    if ($sw_header==1){
      ksk3d_console_log("属性テーブル,図形テーブル作成");

      global $wpdb;

      $tbl_attrib = KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id;
      $tbl_geom = KSK3D_TABLE_GEOM .$user_id ."_" .$new_id;
      $sql = "DROP TABLE IF EXISTS {$tbl_attrib},{$tbl_geom};";
      ksk3d_log( "sql:" .$sql );
      $wpdb->query($sql);

      ksk3d_DB_create_attrib(
        KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
        $set_attrib
      );
      
      ksk3d_DB_create_geom(
        KSK3D_TABLE_GEOM .$user_id ."_" .$new_id
      );

      ksk3d_DB_insert_attrib(
        $user_id,
        $new_id,
        $set_attrib
      );
    }
    
    $result = static::citygml2DB(
      $file1,
      KSK3D_TABLE_ATTRIB .$user_id ."_" .$new_id,
      KSK3D_TABLE_GEOM .$user_id ."_" .$new_id,
      $set_attrib
    );
    
    return $result;
  }

  static function status($file1){
    ksk3d_log("fn:ksk3d_functions_citygml::status:({$file1})");

    $doc = new DOMDocument();
    $doc->load($file1);
    $xpath = new DOMXpath($doc);

    $result['srsName'] = $xpath->query("//gml:Envelope/@srsName")->item(0)->nodeValue;
    
    $cityObjectMember = $doc->getElementsByTagName('cityObjectMember')->item(0);
    $posListPath = $xpath->query(".//gml:posList" ,$cityObjectMember)->item(0)->getNodePath();
    $posListPath = substr($posListPath ,strlen($cityObjectMember->getNodePath())+1);
    preg_match('{^(.+?)/(.+)}' ,$posListPath ,$match);
    $result['FeatureType'] = $match[1];
    preg_match('{^(.+?)/(.+)}' ,$match[2] ,$match);
    $result['LOD'] = $match[1];
    preg_match('{^(.+?)/(.+)}' ,$match[2] ,$match);
    $result['Geometry'] = $match[1];

    return $result;
  }

  static function tagsort($file1 ,$file2){
    ksk3d_log("fn:ksk3d_citygml::tagsort:({$file1} ,{$file2})");

    $tagsorts = array(
      "gml:",
      "core:",
      "gen:",
      "bldg:",
      "uro:"
    );
    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    $doc->load($file1);
    $xpath = new DOMXpath($doc);

    $nodeList = $doc->getElementsByTagName('CityModel');
    $CityModel = $nodeList->item(0);

    $cityObjectMembers = $doc->getElementsByTagName('cityObjectMember');
    foreach ($cityObjectMembers as $cityObjectMember){
      foreach ($cityObjectMember->childNodes as $cityObject){
        if (get_class($cityObject) == "DOMElement" ){
          foreach($tagsorts as $tagsort){
            $objects = $cityObject->childNodes;
            $list_tag = array();
            for ($i=0; $i<$objects->length; $i++){
              if (preg_match('/^'.$tagsort.'/',$objects->item($i)->nodeName)==1){
                array_push($list_tag ,$i);
              }
            }
            $i=0;
            foreach($list_tag as $tag){
              $object = $objects->item($tag-$i);
              $cityObject->appendChild($cityObject->removeChild($object));
              $i++;
            }
          }
        }
      }
    }

    $doc->save($file2);
    return true;
  }

}