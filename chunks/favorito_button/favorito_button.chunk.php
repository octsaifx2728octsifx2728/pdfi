<?php
class favorito_button_chunk extends chunk_base implements chunk{
	public $params;
	protected $_plantillas=array('html/favorito_button.html','html/favorito_button1.html','html/notlogged.html','html/notlogged_1.html');
        protected $_selfpath="chunks/favorito_button/";
	function favorito_button_chunk($params){
		$this->params=$params;
	}
	function out($params=array()){
		global $user,$core,$config,$document,$inmueble;
		$params=count($this->params)?$this->params:$params;
		if(!$params["inmueble"])$params["inmueble"]=$inmueble;
		$p=array("#FAVORITO#"=>"");
		if($user->id&&is_a($params["inmueble"],"inmueble")){
                        $plantilla= $this->loadPlantilla($params["plantilla"]?$params["plantilla"]:0);
                                $q="select * 
                                        from `favoritos` 
                                        where `id_cliente`='".mysql_real_escape_string($user->id)."'"
                                                ."and `id_clientefavo`='".mysql_real_escape_string($params["inmueble"]->get("id_cliente"))."'"
                                                ."and `id_expedientefavo`='".mysql_real_escape_string($params["inmueble"]->id)."'"
                                                ."and `tipo`='".mysql_real_escape_string($params["inmueble"]->get("tipoobjeto"))."'";
                                
                                $r=mysql_query($q);
                                if(mysql_num_rows($r)){
                                $p["#FAVORITO#"]="fav";
                                }
                                $p["#CLIENTE#"]=$params["inmueble"]->get("id_cliente");
                                $p["#TIPO#"]=$params["inmueble"]->get("tipoobjeto");
                                $p["#ID#"]=$params["inmueble"]->id;
                                
			}
                        else {
                            $plantilla= $this->loadPlantilla($params["plantilla"]?2:3);
                        }
		if($document){
			$document->addScript("js/favoritos.js");
			}
                $p["#URL#"]=$inmueble?$inmueble->getURL():"#BASE#";
		return str_replace(array_keys($p),$p,$plantilla);
	}
}
