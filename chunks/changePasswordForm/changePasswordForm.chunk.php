<?php

class changePasswordForm_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/changePasswordForm/";
    protected $_plantillas=array(
        "html/formulario.html"
    );
    protected $_scripts=array(
        "js/changePasswordForm.js"
    );
    protected $_styles=array(
        "css/changePasswordForm.css"
    );
    function out($params=array()){
        $plantilla=$this->loadPlantilla(0);
        $p=array();
        
        return parent::out($plantilla, $p);
    }
}