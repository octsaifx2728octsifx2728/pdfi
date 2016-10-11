<?php
class metricaconverter_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/metricaconverter/";
    protected $_plantillas=array(
        "html/main.html"
    );
    protected $_styles=array(
        "css/metricaconverter.css"
    );
    protected $_scripts=array(
        "js/metricaconverter.js"
    );
  function out($params=array()){
    global $config,$document,$core;
    //include_once 'config/currencyconverter.config.php';
    
    $metrica=$core->getEnviromentVar("metrica");
    switch($metrica){
        case "metros":
            break;
        case "pies":
            break;
        case "acres":
            break;
        default:
            $core->setEnviromentVar("metrica","metros");
            $metrica=$core->getEnviromentVar("metrica");
    }
    $plantilla= $this->loadPlantilla(0);
    $p=array(
        "METROS"=>$metrica=="metros"?"ui-selected":"ui-selectee",
        "PIES"=>$metrica=="pies"?"ui-selected":"ui-selectee",
        "ACRES"=>$metrica=="acres"?"ui-selected":"ui-selectee"
    );
    return parent::out($plantilla,$p);
    
  }
}
