<?php
class searchbox_chunk extends chunk_base implements chunk{
	protected $_params=array();
	protected $_styles=array("css/searchbox.css");
        protected $_plantillas=array('html/searchplacesbox.html','html/searchplacesbox_1.html');
        protected $_selfpath='chunks/searchbox/';
	function searchbox_chunk($params=array()){
		$this->_params=$params;
	}
  function out($params=array()){
      if($this->_params["filters"]){
        $plantilla= $this->loadPlantilla(1);
          }
      else {
        $plantilla= $this->loadPlantilla(0);
      }
    $p=array(
      "QUERY"=>htmlentities($_GET["task"]),
      "AJAX"=>$this->_params["ajax"]==0?"0":"1"
      );
    return parent::out($plantilla, $p);
  }
}
