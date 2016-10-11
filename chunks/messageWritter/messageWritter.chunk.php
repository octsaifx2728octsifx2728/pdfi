<?php
    class messageWritter_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/messageWritter/";
    protected $_plantillas=array(
        "html/form.html"
    );
    protected $_scripts=array(
        "js/messageWritter.js"
    );
    protected $_styles=array(
        "css/messageWritter.css"
    );
    public function messageWritter_chunk($params){
        $this->_params=$params;
    }
    public function out($params = array()) {
        $plantilla=$this->loadPlantilla(0);
        $key=$this->_params["key"]?$this->_params["key"]:md5("messageWritter".rand());
        $p=array(
            "KEY"=>$key
        );
        return parent::out($plantilla, $p);
    }
}
