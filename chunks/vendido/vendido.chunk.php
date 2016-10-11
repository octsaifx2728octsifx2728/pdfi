<?php
class vendido_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/vendido/";
    protected $_plantillas=array(
        "html/pequenyo.html"
    );
    public function out($params=array()) {
        $plantilla=$this->loadPlantilla(0);
        $p=array();
        return parent::out($plantilla, $p);
    }
}
