<?php
class languaje_chunk extends chunk_base implements chunk{
  
  protected $_plantillas=array("/html/languaje.html","/html/item.html","/html/select.html","/html/option.html");
  protected $_selfpath="chunks/languaje/";
        protected $_scripts=array("js/languaje.js");
        protected $_styles=array("css/languaje.css");
  public function languaje_chunk($params){
    $this->params=$params;
  }
  function out($params=array()){
      global $core;
      
      $app=&$core->getApp("languaje");
      $langs=&$app->getLanguajesList();
      
      switch($this->params["plantilla"]){
          case 2:
            $pitem=$this->loadPlantilla(3);
            $plantilla=$this->loadPlantilla(2);
            $this->_scripts=array();
              break;
          default:
            $pitem=$this->loadPlantilla(1);
            $plantilla=$this->loadPlantilla(0);
              
      }
      
      
      $plangs="";
      
      $lact=$core->getEnviromentVar("languaje");
      
      
      
      foreach($langs as $l){
        $p=array(
            "NAME"=>$l->name,
            "KEY"=>$l->key,
            "SELECTED"=>$lact==$l->key?"ui-selected".($this->params["skipcookie"]?" skip":""):($this->params["skip"]?" skip":"ui-selectee"),
            "SELECT"=>$lact==$l->key?" selected='selected'".($this->params["skipcookie"]?" skip":""):($this->params["skip"]?" skip":""));
        $plangs.=$this->parse($pitem, $p);
      }
      $p=array(
          "LANGUAJES"=>$plangs,
          "ACTUAL"=>$lact
          );
      return parent::out($plantilla,$p);
  }
}
