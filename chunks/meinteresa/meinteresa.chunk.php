<?php
class meinteresa_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/meinteresa/";
    protected $_plantillas=array(
        "html/contact.html",
        "html/contact2.html"
    );
    protected $_styles=array(
        "css/meinteresa.css"
    );
    function out($params=array()){
    global $user , $core, $config, $document,$inmueble, $user_view;
	
	
	if($params["id"]&&!$params["cliente"]){
		$usuario=new user($params["id"]);
	}
	elseif($params["cliente"]){
		$usuario=new user($params["cliente"]);
	}
	elseif(!$params["id"]||!$params["cliente"]&&$user_view->id){
		$usuario=&$user_view;
	}
	elseif(!$params["id"]||!$params["cliente"]&&$inmueble){
		$usuario=new user($inmueble->cliente);
	}
	else{
		$usuario=&$user;
	}
	
	
	
	if(($usuario->id!=$user->id)&&$user->id){
   		$plantilla= $this->loadPlantilla(0);
		}	
	elseif($usuario->id==$user->id) {
   		$plantilla= "";
	}	
	else {
   		$plantilla= $this->loadPlantilla(1);
	}
    $p=array(
      "client"=>$usuario?$usuario->id:"",
      "email"=>$usuario?$user->get("usuario"):"",
      "TELEFONO"=>$usuario?$usuario->get("telefono"):"",
        "meinteresa"=>$inmueble->get("meinteresa"),
      "KEY"=>(md5("es".rand(0, 1000).($usuario?$usuario->id:"")))
	);
    return parent::out($plantilla,$p);
  }
} 
