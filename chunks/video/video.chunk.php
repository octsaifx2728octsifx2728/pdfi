<?php
class video_chunk extends chunk_base implements chunk{
	var $_params;
        protected $_plantillas=array("html/html5.html","html/flash.html");
        protected $_styles=array("css/video.css");
         protected $_selfpath="chunks/video/";
	function video_chunk($params){
		$this->_params=$params;
	}
	function out($params=array()){
		global $core;
                $browser=$core->getEnviromentVar("browser");
                
                
		$p=array(
		 "SRC"=>$this->_params["src"],
		 "WIDTH"=>$this->params["width"],
		 "HEIGHT"=>$this->params["height"]
		 );
                
                switch($browser->renderingengine_name){
                  case "WebKit":
                    $plantilla=$this->loadPlantilla(0);
                    $p["SRC"]=  $p["SRC"].'_$$_languajeKey$$.mp4';
                    $p["TYPE"]=  "video/mp4";
                    break;
                  case "Gecko":
                    $plantilla=$this->loadPlantilla(0);
                    $p["SRC"]=  $p["SRC"].'_$$_languajeKey$$.ogg';
                    $p["TYPE"]=  "video/ogg";
                    break;
                  default:
                    $plantilla=$this->loadPlantilla(1);
                    $p["SRC"]=  $p["SRC"].'_$$_languajeKey$$.flv';
                    $p["TYPE"]=  "";
                    break;
                }
                
                
                return parent::out($plantilla,$p);
	}
	
}
