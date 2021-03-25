<?php
function ksk3d_logic_check($ck_item ,$dataset ,$dataset_unit ,$takeover=""){
  ksk3d_console_log("ksk3d_logic_check($ck_item ,$dataset ,$dataset_unit)");
  $ct = 0;
  $memo = "";
  $ret = array('未検査','','');

  if (preg_match('/C01/i' ,$ck_item)){
      $id = [];
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);
    $xpath = new DOMXpath($doc);
    $query = $xpath->query('//@gml:id');
    foreach($query as $attrib){
      $gml_id = $attrib->value;
      if (isset($id["{$gml_id}"])){
        $id["{$gml_id}"]++;
      } else {
        $id["{$gml_id}"]=1;
      }
      if ($id["{$gml_id}"]>1){
        $ct++;
        $memo .= "行=".$attrib->getLineNo()."、"
          ."タグの場所=".$attrib->getNodePath()."、"
          ."対象のgml:id=".$gml_id
          ."<br>\n";
      }
    }
    if ($query->length > 0){
      if ($ct>0){$memo="gml:idの重複が見つかりました。gml:idに重複がないように修正してください。<br>\n".$memo;}
      $ret = array(
        $ct,
        $memo,
        "",
        $id
      );
    } else {
      $ret = array(
        0,
        "",
        1
      );
    }
    
  } else if (preg_match('/C02/i' ,$ck_item)){
    foreach(glob($dataset) as $file){
      $doc = new DOMDocument();
      $doc->load($file, LIBXML_BIGLINES);
      $cityObjectMember = $doc->getElementsByTagName('cityObjectMember');
      $ct += $cityObjectMember->length;
    }
    if ($ct==0){
      $ret = array(
        "1",
        "インスタンスは見つかりませんでした。",
        ""
      );
    } else {
      $ret = array(
        "OK",
        $ct,
        ""
      );
    }
  
  } else if (preg_match('/L04/i' ,$ck_item)){
    $upload_dir = ksk3d_upload_dir();

    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);
    $xpath = new DOMXpath($doc);
    $query = $xpath->query('//@codeSpace');
    foreach($query as $attrib){
      $codeSpace = $attrib->value;
      if (!isset($codelist[$codeSpace])){
        if (preg_match('/^http(s?):\/\//' ,$codeSpace)==1){
          $cd_file = $codeSpace;
        } else {
          $cd_file = ksk3d_realpath($dataset."/../".$codeSpace);
        }
        
        if (is_file($cd_file)){
          $doc_cd = new DOMDocument();
          $doc_cd->load($cd_file);
          $xpath_cd = new DOMXpath($doc_cd);
          $query_cd = $xpath_cd->query('//gml:name');
          foreach($query_cd as $node_cd){
            $codelist[$codeSpace][$node_cd->nodeValue]=1;
            ksk3d_console_log("codelist:codelist[$codeSpace][".$node_cd->nodeValue."]");
          }
        } else {
          $ct++;
          $memo .= "行=".$attrib->getLineNo()."、"
            ."タグの場所=".$attrib->getNodePath()."、"
            ."対象のコードリスト=".$codeSpace
            ."<br>\n";
          $codelist[$codeSpace] = 1;
        }
      }
    }
    if (!empty($memo)){$memo="コードリストが見つかりません。コードリストのパス、ファイル名等を見直してください。<br>\n".$memo;}
    
    $memo1="";
    foreach($query as $attrib){
      $codeSpace = $attrib->value;
      $v = $attrib->ownerElement->nodeValue;
      ksk3d_console_log("ck_codelist:codelist[$codeSpace][$v]");
      if (!isset($codelist[$codeSpace][$v])){
        $ct++;
        $memo1 .= "行=".$attrib->getLineNo()."、"
          ."タグの場所=".$attrib->getNodePath()."、"
          ."地物属性の値=".$v
          ."<br>\n";
      }
    }
    if (!empty($memo1)){$memo.="地物属性の値と合致するコードが見つかりません。値、またはコードを見直してください。<br>\n".$memo1;}
    
    if ($ct==0){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {
      $ret = array(
        $ct,
        $memo,
        ""
      );
    }

  } else if (preg_match('/L05/i' ,$ck_item)){
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);
    $xpath = new DOMXpath($doc);
    $query = $xpath->query("//gml:Envelope");
    $srsName = $query->item(0)->getAttribute("srsName");
    $srsName2 = preg_replace('/^.+\//' ,'' ,$srsName);
    if (preg_match('/^http\:\/\/www.opengis.net\/def\/crs\/EPSG\/0\/(6697|6668)$/' ,$srsName)==1){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {
      $memo .= "行=".$query->item(0)->getLineNo()."、"
        ."タグの場所=".$query->item(0)->getNodePath()."、"
        ."空間座標参照系=".$srsName
        ."<br>\n";
      $ret = array(
        "1",
        $memo,
        ""
      );
    }

  } else if (preg_match('/L06/i' ,$ck_item)){
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);
    $xpath = new DOMXpath($doc);
    $query = $xpath->query("//gml:Envelope/gml:lowerCorner");
    $coode_min = explode(" " ,$query->item(0)->nodeValue);
    $query = $xpath->query("//gml:Envelope/gml:upperCorner");
    $coode_max = explode(" " ,$query->item(0)->nodeValue);
    $geom1 = $xpath->query("//gml:posList");
    $geom2 = $xpath->query("//gml:pos");
    foreach (array($geom1,$geom2) as $geom){
      if (count($geom)>0){
        foreach ($geom as $g){
          for ($i=0; $i<3; $i++){
            $xyz = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+/' ,'${'.($i+1).'},' ,trim($g->nodeValue)." ");
            $xyz_array = explode(',' ,substr($xyz,0,-1));
            $v = min($xyz_array);
            if ($v < $coode_min[$i]){
              $ct++;
              $memo .= "行=".$g->getLineNo()."、"
                ."タグの場所=".$g->getNodePath()."、"
                ."座標値の比較=".$v." はboundedByの下限値 ".$coode_min[$i]." より大きくなければいけません。"
                ."<br>\n";
              break;
            }
            $v = max($xyz_array);
            if ($v > $coode_max[$i]){
              $ct++;
              $memo .= "行=".$g->getLineNo()."、"
                ."タグの場所=".$g->getNodePath()."、"
                ."座標値の比較=".$v." はboundedByの上限値 ".$coode_max[$i]." より小さくなければいけません。"
                ."<br>\n";
              break;
            }
          }
        }
      }
    }
    if ($ct==0){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {
      $ret = array(
        $ct,
        $memo,
        ""
      );
    }

  } else if (preg_match('/L20/i' ,$ck_item)){
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);

    $uri = $doc->documentElement->lookupnamespaceURI('bldg');
    if (empty($uri)){
      return array(
        "OK",
        "",
        [1,0,0]
      );
    }

    $uri = $doc->documentElement->lookupnamespaceURI('uro');
    if (empty($uri)){
      return array(
        "OK",
        "",
        [0,1,0]
      );
    }

    $xpath = new DOMXpath($doc);
    $feature1 = $xpath->query('//bldg:Building');
    $feature2 = $xpath->query('//bldg:BuildingPart');
    if ($feature1->length+$feature2->length==0){
      return array(
        "OK",
        "",
        [0,0,1]
      );
    }
    foreach(array($feature1,$feature2) as $featurelist){
      foreach($featurelist as $feature){
        $keys = $xpath->query('.//uro:extendedAttribute/uro:KeyValuePair/uro:key',$feature);
        if ($keys->length>0){
          foreach($keys as $key){
            if ($key->nodeValue==2){
              $v = $xpath->query('./../uro:codeValue',$key);
              ksk3d_console_log($v->item(0)->getNodePath());
              ksk3d_console_log($v->item(0)->nodeValue);
              if ($v->item(0)->nodeValue==8){
                $z_max = null;
                $z_min = null;
                $geom = $xpath->query('.//bldg:lod1Solid//gml:posList',$feature);
                foreach($geom as $g){
                  $z = preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)/' ,'${3},' ,trim($g->nodeValue));
                  $z_array = explode(',' ,substr($z,0,-1));
                  $z_min1 = min($z_array);
                  if ((is_null($z_min)) or ($z_min1<$z_min)){
                    $z_min = $z_min1;
                  }
                  $z_max1 = max($z_array);
                  if ((is_null($z_max)) or ($z_max1>$z_max)){
                    $z_max = $z_max1;
                  }
                }
                $nodelist_h = $xpath->query('.//bldg:measuredHeight',$feature);
                if ($nodelist_h->length==0){
                  $ct++;
                  $memo .= "行=".$feature->getLineNo()."、"
                    ."タグの場所=".$feature->getNodePath()."、"
                    ."確認事項=拡張属性「建築物の高さ」の値は8ですが、bldg::計測高さ（bldg:measuredHeight）が見つかりません。bldg::計測高さを設定してください。"
                    ."<br>\n";
                } else {
                  $h = $nodelist_h->item(0)->nodeValue;
                  
                  if ($h!=$z_max-$z_min){
                    $ct++;
                    $memo .= "行=".$nodelist_h->item(0)->getLineNo()."、"
                      ."タグの場所=".$nodelist_h->item(0)->getNodePath()."、"
                      ."確認事項=高さの最高値と最低値の差分が、bldg::計測高さの値と一致しません。高さの最高値と最低値の差分がbldg::計測高さの値と一致するように見直してください。"
                      ."<br>\n";
                  }
                }
              }
              break;
            }
          }

        }
      }
    }
    if ($ct==0){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {
      $ret = array(
        $ct,
        $memo,
        ""
      );
    }
    
  } else if (preg_match('/T05/i' ,$ck_item)){
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);

    $xpath = new DOMXpath($doc);
    $attribList = $xpath->query('//@xlink:href');
    if ($attribList->length==0){
      return array(
        "OK",
        "",
        1
      );
    }
    foreach($attribList as $attrib){
      $gml_id = preg_replace('/^#/' ,'' ,$attrib->value);
      $nodeName = $attrib->ownerElement->nodeName;
      $refAttribList = $xpath->query("//@gml:id[.='".$gml_id."']");
      if ($refAttribList->length==0){
        $ct++;
        $memo .= "行=".$attrib->getLineNo()."、"
          ."タグの場所=".$attrib->getNodePath()."、"
          ."確認事項=ID参照されたgml:id({$gml_id})をもつインスタンスが見つかりません。ID参照されたgml:idを持つインスタンスを設定してください。"
          ."<br>\n";
      } else if ($refAttribList->length>1){
        $ct++;
        $memo .= "行=".$attrib->getLineNo()."、"
          ."タグの場所=".$attrib->getNodePath()."、"
          ."確認事項=ID参照されたgml:id({$gml_id})をもつインスタンスが複数見つかりました。ID参照されたgml:idを持つインスタンスを1つにしてください。"
          ."<br>\n";
      } else {
        $refAttrib = $refAttribList->item(0);
        $refNode = $refAttrib->ownerElement->nodeName;
        if (!isset(ksk3d_geometryPrimitives::$content["{$nodeName}"])){
          $memo .= "行=".$attrib->getLineNo()."、"
            ."タグの場所=".$attrib->getNodePath()."、"
            ."確認事項=インスタンスの型（{$nodeName}）は扱えません。インスタンスの型を見直してください。"
            ."<br>\n";
        }
        if (!isset(ksk3d_geometryPrimitives::$content["{$nodeName}"]["{$refNode}"])){
          $ct++;
          $memo .= "行=".$attrib->getLineNo()."、"
            ."タグの場所=".$attrib->getNodePath()."、"
            ."確認事項=インスタンスの型（{$nodeName}）から（{$refNode}）は参照できません。インスタンスの型を見直してください。"
            ."<br>\n";
        }
      }
    }
    if ($ct==0){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {
      $ret = array(
        $ct,
        $memo,
        ""
      );
    }

  } else if (preg_match('/T06/i' ,$ck_item)){
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);

    $uri = $doc->documentElement->lookupnamespaceURI('bldg');
    if (empty($uri)){
      return array(
        "OK",
        "",
        [1,0]
      );
    }

    $xpath = new DOMXpath($doc);
    $feature = $xpath->query('//bldg:BuildingInstallation');
    if ($feature->length==0){
      return array(
        "OK",
        "",
        [0,1]
      );
    }

    foreach($feature as $f){
      $geom1 = $xpath->query('.//bldg:lod2Geometry',$f);
      foreach($geom1 as $g1){
        $geom2 = $g1->childNodes;
        foreach($geom2 as $g2){
          if (get_class($g2) == "DOMElement" ){
            $nodeName = $g2->nodeName;
            if (preg_match('/^(gml:MultiSurface|gml:Solid|gml:CompositeSolid)$/',$nodeName)==0){
              $ct++;
              $memo .= "行=".$g2->getLineNo()."、"
                ."タグの場所=".$g2->getNodePath()."、"
                ."確認事項=bldg:lod2Geometryにより保持する幾何オブジェクトの型が指定値ではありません。幾何オブジェクトの型を見直してください。"
                ."<br>\n";
            }
          }
        }
        $attribList = $xpath->query('./@xlink:href',$g1);
        if ($attribList->length>0){
          foreach($attribList as $attrib){
            $gml_id = preg_replace('/^#/' ,'' ,$attrib->value);
            $nodeName = $attrib->ownerElement->nodeName;
            $refAttribList = $xpath->query("//@gml:id[.='".$gml_id."']");

            $refAttrib = $refAttribList->item(0);
            $refNode = $refAttrib->ownerElement->nodeName;
            if (preg_match('/^(gml:MultiSurface|gml:Solid|gml:CompositeSolid)$/',$refNode)==0){
              $ct++;
              $memo .= "行=".$attrib->getLineNo()."、"
                ."タグの場所=".$attrib->getNodePath()."、"
                ."確認事項=bldg:lod2Geometryにより参照する幾何オブジェクトの型が指定値ではありません。幾何オブジェクトの型を見直してください。"
                ."<br>\n";
            }
          }
        }
      }
    }

    if ($ct==0){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {
      $ret = array(
        $ct,
        $memo,
        ""
      );
    }

  } else if (preg_match('/T07/i' ,$ck_item)){
    $doc = new DOMDocument();
    $doc->load($dataset, LIBXML_BIGLINES);

    $uri = $doc->documentElement->lookupnamespaceURI('gen');
    if (empty($uri)){
      return array(
        "OK",
        "",
        [1,0]
      );
    }

    $xpath = new DOMXpath($doc);
    $feature = $xpath->query('//gen:GenericCityObject');
    if ($feature->length==0){
      return array(
        "OK",
        "",
        [0,1]
      );
    }

    foreach($feature as $f){
      $class = $xpath->query('.//gen:class',$f);
      if ($class->length>0){
        if ($class->item(0)->nodeValue==1){
          $geom1 = $xpath->query('.//gen:lod0Geometry',$f);
          foreach($geom1 as $g1){
            $geom2 = $g1->childNodes;
            foreach($geom2 as $g2){
              if (get_class($g2) == "DOMElement" ){
                $nodeName = $g2->nodeName;
                ksk3d_console_log("nodeName:".$nodeName);
                if (preg_match('/^(gml:MultiSurface)$/',$nodeName)==0){
                  $ct++;
                  $memo .= "行=".$g2->getLineNo()."、"
                    ."タグの場所=".$g2->getNodePath()."、"
                    ."確認事項=gen:lod0Geometryにより保持する幾何オブジェクトの型が指定値ではありません。幾何オブジェクトの型を見直してください。"
                    ."<br>\n";
                }
              }
            }
            $attribList = $xpath->query('./@xlink:href',$g1);
            if ($attribList->length>0){
              foreach($attribList as $attrib){
                $gml_id = preg_replace('/^#/' ,'' ,$attrib->value);
                $nodeName = $attrib->ownerElement->nodeName;
                $refAttribList = $xpath->query("//@gml:id[.='".$gml_id."']");

                $refAttrib = $refAttribList->item(0);
                $refNode = $refAttrib->ownerElement->nodeName;
                if (preg_match('/^(gml:MultiSurface)$/',$refNode)==0){
                  $ct++;
                  $memo .= "行=".$g2->getLineNo()."、"
                    ."タグの場所=".$g2->getNodePath()."、"
                    ."確認事項=gen:lod0Geometryにより参照する幾何オブジェクトの型が指定値ではありません。幾何オブジェクトの型を見直してください。"
                    ."<br>\n";
                }
              }
            }
          }
        }
      }
    }

    if ($ct==0){
      $ret = array(
        "OK",
        "",
        ""
      );
    } else {

      $ret = array(
        $ct,
        $memo,
        ""
      );
    }

  }

  return $ret;
}