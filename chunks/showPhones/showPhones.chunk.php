<?php
class showPhones_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/showPhones/";
    protected $_plantillas=array(
        "html/parrilla.html"
    );
    protected $_scripts=array(
        "js/showPhones.js"
    );
    protected $_styles=array(
        "css/showPhones.css"
    );
    function out($params = array()) {
        $plantilla=$this->loadPlantilla(0);
        $p=array();
        return parent::out($plantilla, $p);
    }
}