<?php
class header_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/header.html");
  protected $_selfpath="chunks/header/";
  protected $_styles=array("css/header.css");
  public function out($params=array()){
    $plantilla=$this->loadPlantilla(0);
    $p=array(
        "TITLE"=>''
    );
    return parent::out($plantilla,$p);
  }
}
