<?php

class monedalist_chunk extends chunk_base implements chunk{
    protected $_params=array();
    protected $_selfpath="chunks/monedalist/";
    protected $_plantillas=array("html/select.html","html/option.html","html/select_1.html");
    function monedalist_chunk($params=array()){
        $this->_params=$params;
    }
    function out($params=array()){
        global $config,$core;
        
        switch($this->_params["type"]){
            case "1":
                $plantilla=$this->loadPlantilla(2);
                break;
            default:
                $plantilla=$this->loadPlantilla(0);
        }
        
        $plantilla_option=$this->loadPlantilla(1);
        $options=array();
        $default=$this->_params["moneda"]?$this->_params["moneda"]:$core->getEnviromentVar("currency");
        foreach($config->currencyconverter as $currency){
            
          $p=array(
            "KEY"=>$currency["key"],
            "DESCRIPTION"=>$currency["descripcion"],
            "SELECTED"=>$default==$currency["key"]?"selected='selected'":""
            );
          $options[]=parent::parse($plantilla_option,$p);
          }
        $p=array("OPTIONS"=>implode("",$options),"ID"=>$this->_params["idinmueble"],"TIPO"=>$this->_params["tipoinmueble"]);
        return parent::out($plantilla,$p);
    }
}