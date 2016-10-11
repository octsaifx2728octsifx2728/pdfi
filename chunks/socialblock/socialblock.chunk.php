<?php
class socialblock_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/main.html");
  protected $_selfpath="chunks/socialblock/";
  public function out($params=array()){
    $plantilla=$this->loadPlantilla(0);
    $p=array();
    return parent::out($plantilla,$p);
  }
}
