<?php
class footer_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/footer.html");
  protected $_selfpath="chunks/footer/";
  protected $_styles=array("css/footer.css");
  public function out($params=array()){
    $plantilla=$this->loadPlantilla(0);
    $p=array();
    return parent::out($plantilla,$p);
  }
}
