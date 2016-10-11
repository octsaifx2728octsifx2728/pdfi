<?php
class contact_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks_mobile/contact/";
    protected $_plantillas=array(
        "html/contact.html",
        "html/contact2.html",
        "html/telbutton.html"
    );
    protected $_scripts=array("js/contact.js");
    protected $_styles=array("css/contact.css");
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
   		$plantilla= $this->loadPlantilla(1);
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
      "TELEFONO"=>$usuario&&$usuario->get("telefono")?$this->parse($this->loadPlantilla(2),array("TELEFONO"=>$usuario->get("telefono"))):"",
      "KEY"=>(md5("es".rand(0, 1000).($usuario?$usuario->id:"")))
	);
	$salida=parent::out($plantilla,$p);
    return $salida;
  }
} 
