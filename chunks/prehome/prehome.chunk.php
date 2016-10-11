<?php
class prehome_chunk extends chunk_base implements  chunk{
  protected $_plantillas=array("/html/prehome.html");
  protected $_selfpath="chunks/prehome/";
  protected $_styles=array("css/prehome.css");
  protected $_scripts=array("js/prehome.js");
   public function out($params=array()){
    global $core;
    if(!$core->getEnviromentVar("lang_user_defined")){
      $plantilla=$this->loadPlantilla(0);
      $p=array();
      return parent::out($plantilla,$p);
      }
   }
  
  }