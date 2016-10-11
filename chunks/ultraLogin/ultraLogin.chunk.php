<?php
class ultraLogin_chunk extends chunk_base implements chunk{
    protected  $_selfpath="chunks/ultraLogin/";
    protected $_plantillas=array(
        "html/buttons.html"
    );
    protected $_scripts=array("js/ultraLogin.js");
    protected $_styles=array("css/ultraLogin.css");
    public function ultraLogin_chunk($params = array()){
        $this->_params=$params;
    }
    public function out($params = array()) {
        
        $plantilla=$this->loadPlantilla(0);
        $key=$this->_params["key"]?$this->_params["key"]:md5("ultralogin".rand());
        $p=array(
            "KEY"=>$key
        );
        return parent::out($plantilla, $p);
    }
}
