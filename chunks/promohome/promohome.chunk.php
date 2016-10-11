<?php
class promohome_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/promohome/";
    protected $_plantillas=array(
        "html/promo.html",
        "html/promo_1.html",
        "html/promo_2.html",
        "html/promo_3.html"
    );
    protected $_styles=array(
        "css/promohome.css"
    );
    public function promohome_chunk($params=array()){
        $this->_params=$params;
    }
    public function out($params=array()) {
        $plantilla=$this->loadPlantilla(intval($this->_params["plantilla"])?intval($this->_params["plantilla"]):0);
        $p=array();
        return parent::out($plantilla, $p);
    }
}