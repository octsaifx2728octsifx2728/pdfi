<?php

class resetPasswordForm_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/resetPasswordForm/";
    protected $_plantillas=array(
        "html/formulario.html"
    );
    protected $_scripts=array(
        "js/resetPasswordForm.js"
    );
    protected $_styles=array(
        "css/resetPasswordForm.css"
    );
    function out($params=array()){
        $plantilla=$this->loadPlantilla(0);
        $p=array();
        
        return parent::out($plantilla, $p);
    }
}