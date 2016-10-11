<?php

class facebooksdk_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/facebooksdk/";
    protected $_plantillas=array("html/sdk.html");
    public function out($params = array()) {
        global $config,$user;
        $plantilla=$this->loadPlantilla(0);
        
        
        $token=$config->defaults["facebook"]->token;
        
        $p=array(
            "FB_ID"=>$config->defaults["facebook"]->app_id,
            "TOKEN"=>$token,
            "PAGE"=>'$$fb_page_ID$$'
        );
        return parent::out($plantilla,$p);
    }
}