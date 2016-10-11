<?php

class connect_chunk extends chunk_base implements chunk{
    protected $_plantillas=array(

  		"html/botones.html"
  );
  protected $_selfpath="chunks/connect/";
  public function connect_chunk($params){
  	$this->_params=(array)$params;
  }
  public function out($params=array()){
      $plantilla=$this->loadPlantilla(0);
    $p=array(
        "RETURN"=>$this->_params["return"]?urlencode($this->_params["return"]):"/"
    );
    return parent::out($plantilla,$p);
  }
}
