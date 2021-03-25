<?php
class ksk3d_dataset_gml{
  static function node_appendChild_path($doc ,$node ,$path){
    if (!empty($path)){
      foreach(explode('/' ,$path) as $nodeName){
        $elem = $doc->createElement($nodeName);
        $node = $node->appendChild($elem);
      }
    } else {
      ksk3d_console_log("node_appendChild_path:path is null");
    }
    return $node;
  }

  static function poslist_insert_hole($poslist ,$hole){
    preg_match('/([^\s]+)\s+([^\s]+)\s+([^\s]+)$/' ,trim($poslist) ,$pos_last);
    $poslist .= " " .$hole ." " .$pos_last[0];
    return $poslist;
  }
  
  static function poslist_verticesReverse($poslist){
    $poslist2 = implode(' ' ,array_reverse(preg_split("/\s+/" ,trim($poslist))));
    return preg_replace("/([^\s]+)\s+([^\s]+)\s+([^\s]+)/" ,'${3} ${2} ${1}' ,$poslist2);
  }

  static function poslist_xyz_yxz($poslist ,$bool=true ,$bool2=false){
    if ($bool){
      if ($bool2){
        $poslist2 = implode(' ' ,array_reverse(preg_split("/\s+/" ,trim($poslist))));
        return preg_replace("/([^\s]+)\s+([^\s]+)\s+([^\s]+)/" ,'${2} ${3} ${1}' ,$poslist2);
      } else {
        return preg_replace('/([^\s]+)\s+([^\s]+)\s+([^\s]+)/' ,'${2} ${1} ${3}' ,trim($poslist));
      }
    } else {
      return $poslist;
    }
  }
  
  static function remove_blanknode($node, $flg_del_parentNode=true){
    ksk3d_console_log("remove_blanknode:".$node->nodeName);
    if (is_null($node->parentNode)){return false;}

    $flg_del = true;
    if ($node->hasChildNodes()){
      foreach($node->childNodes as $c){
        if (preg_match('/DOMComment|DOMAttribute/' ,get_class($c))==0){
        if (!((get_class($c)=='DOMText') and ($c->nodeValue==""))){
          $flg_del = false;
          break;
        }}
      }
    }

    if ($flg_del){
      ksk3d_console_log("remove_blanknode.result:true");
      $node2 = $node->parentNode;
      $node2->removeChild($node);
      if ($flg_del_parentNode) {static::remove_blanknode($node2);}
    }
    
    return $flg_del;
  }

  static function remove_blanknodes($node){
    ksk3d_console_log("remove_blanknodes:".$node->nodeName);
    if ($node->hasChildNodes()){
      foreach($node->childNodes as $c){
        if (get_class($c)=='DOMElement'){
          static::remove_blanknodes($c);
        }
      }
    }
    ksk3d_console_log("value:".$node->nodeValue);
    $flg_del = true;
    if ($node->hasChildNodes()){
      foreach($node->childNodes as $c){
        ksk3d_console_log("child:".get_class($c));
        ksk3d_console_log("nodeValue:".$c->nodeValue);
        if (preg_match('/DOMComment|DOMAttribute/' ,get_class($c))==0){
        if (!((get_class($c)=='DOMText') and ($c->nodeValue==""))){
          $flg_del = false;
          break;
        }}
      }
    }

    if ($flg_del){
      ksk3d_console_log("remove_blanknodes.result:true");
      ksk3d_console_log("node:".$node->nodeName);
      ksk3d_console_log("getNodePath:".$node->getNodePath());
      ksk3d_console_log("get_class:".get_class($node));
      $node->parentNode->removeChild($node);

      if ($node->parentNode->hasChildNodes()){
        foreach($node->parentNode->childNodes as $c){
          ksk3d_console_log("child:".get_class($c));
          ksk3d_console_log("nodeValue:".$c->nodeValue);
        }
      }
      
    }
    return $flg_del;
  }

}