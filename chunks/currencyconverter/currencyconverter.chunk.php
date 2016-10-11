<?php
class currencyconverter_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/currencyconverter/";
    protected $_plantillas=array(
        "html/currencyconverter.html",
        "html/currencyconverter_option.html"
    );
    protected $_scripts=array(
        "js/currencyconverter.js"
    );
    protected $_styles=array(
        "css/currencyconverter.css"
    );
            function out($params=array()){
    global $config,$document,$core;
    include_once 'config/currencyconverter.config.php';
    $plantilla= $this->loadPlantilla(0);
    $plantillaMoneda= $this->loadPlantilla(1);
    $options=array();
    foreach($config->currencyconverter as $currency){
      $p=array(
	"KEY"=>$currency["key"],
	"DESCRIPTION"=>$currency["descripcion"],
	"SELECTED"=>$core->getEnviromentVar("currency")==$currency["key"]?"ui-selected":"ui-selectee"
	);
      $options[]=$this->parse($plantillaMoneda, $p);
      }

      $p=array(
	"OPTIONS"=>implode("",$options),
	"CURRENCY"=>$core->getEnviromentVar("currency"),
	"CURRENCY_DEFAULT"=>$core->getEnviromentVar("currency")
	);
    return parent::out($plantilla, $p);
  }
}
