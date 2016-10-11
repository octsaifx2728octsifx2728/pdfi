<?php
class bloquebanners_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/bloquebanners/";
    protected $_plantillas=array(
        "html/texto.html",
        "html/imagen.html"
    );
    protected $_styles=array(
        "css/bloquebanners.css"
    );
    public function out($params = array()) {
        $plantilla=$this->loadPlantilla(0);
        $p=array();
        return parent::out($plantilla, $p);
    }
}
