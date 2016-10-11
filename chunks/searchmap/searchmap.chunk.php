<?php
class searchmap_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/searchmap/";
    protected $_plantillas=array(
        "html/searchmap.html"
    );
    protected $_styles=array("/css/searchmap.css");
    protected $_scripts=array("/js/searchmap.js");
  function out($params=array()){
    $plantilla= $this->loadPlantilla(0);
    $p=array();
    return parent::out($plantilla, $p);
  }
}
