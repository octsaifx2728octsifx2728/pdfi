<?php

class lexicon {
  var $diccionario;
  var $keys;
  var $vars;
  function lexicon($dic){
    $this->diccionario=$dic;
    $vars=get_object_vars ($this->diccionario);
    $keys=array();
    foreach(array_keys($vars) as $v){
      $keys[]='$$'.$v.'$$';
    }
    $this->keys=$keys;
    $this->vars=$vars;
  }
  public function traduce($doc){
    return str_replace($this->keys,$this->vars,$doc);
  }
}