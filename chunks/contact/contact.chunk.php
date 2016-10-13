<?php
class contact_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/contact/";
    protected $_plantillas=array(
        "html/contact.html",
        "html/contact2.html",
        "html/buttonModel.html"
    );
    protected $_scripts=array(
        "js/contact.js"
    );
    protected $_styles=array(
        "css/contact.css"
    );
    function out($params=array()){
    global $user , $core, $config, $document,$inmueble, $user_view;
	
	
        $Button=$this->loadPlantilla(2);
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
	
	
    $key=(md5("es".rand(0, 1000).($usuario?$usuario->id:"")));
    $telButton="";
    $messageButton="";
    $client=$usuario?$usuario->id:"";
    
    
        
       
              

	if(($usuario->id!=$user->id)&&$user->id){
            
                
            
   		$plantilla= $this->loadPlantilla(0);
                
                file_put_contents('/var/www/vhosts/e-spacios.com/httpdocs1/test.log',"hola",FILE_APPEND);
                
                $telParams=array(
                    "ID"=>"contact_tel_".$key,
                    "VALUE"=>'$$llamar$$',
                    "TYPE"=>'call',
                        "INITFORM"=>5,
                        "CONTACTUSER"=>json_encode($usuario?$usuario->getPhones():array()),
                    "IDI"=>$inmueble?$inmueble->getID():($params["id"]?$params["id"]:"0_".$usuario->id."_0"),
                    //"CLICK"=>'$.colorbox({"title":"",inline:true,href:"#displayPhone_'.$client.$key.'",rel:"nofollow"});'
                    );
                $messageParams=array(
                    "ID"=>"contact_button_".$key,
                        "VALUE"=>'$$mensaje$$',
                        "TYPE"=>'message',
                        "CONTACTUSER"=>json_encode($usuario?$usuario->getPhones():array()),
                        "INITFORM"=>3,
                    "IDI"=>$inmueble?$inmueble->getID():($params["id"]?$params["id"]:"0_".$usuario->id."_0"),
                       // "CLICK"=>'$.colorbox({"title":"",inline:true,href:"#messageWritter_'.$client.$key.'",rel:"nofollow"});'
                    );
		}	
	elseif($usuario->id==$user->id) {
   		$plantilla= "";
	}	
	else {
   		$plantilla= $this->loadPlantilla(1);
                $telParams=array(
                    "ID"=>"contact_tel_".$key,
                    "VALUE"=>'$$llamar$$',
                    "TYPE"=>'call',
                        "INITFORM"=>5,
                        "CONTACTUSER"=>  json_encode($usuario?$usuario->getPhones():array()),
                    "IDI"=>$inmueble?$inmueble->getID():($params["id"]?$params["id"]:"0_".$usuario->id."_0"),
                    "CLICK"=>'$($(".login").find(".entrar>.button")[0]).trigger("click")'
                    );
                $messageParams=array(
                    "ID"=>"contact_button_".$key,
                        "VALUE"=>'$$mensaje$$',
                        "TYPE"=>'message',
                        "INITFORM"=>3,
                        "CONTACTUSER"=>json_encode($usuario?$usuario->getPhones():array()),
                        "IDI"=>$inmueble?$inmueble->getID():($params["id"]?$params["id"]:"0_".$usuario->id."_0"),
                        "CLICK"=>'$($(".login").find(".entrar>.button")[0]).trigger("click")'
                    );
	}
        
    $telButton=$this->parse($Button, $telParams);
    $messageButton=$this->parse($Button, $messageParams);
    
    
    
    
    $p=array(
      "client"=>$usuario?$usuario->id:"",
      "email"=>$usuario?$user->get("usuario"):"",
      "TELEFONO"=>$usuario?$usuario->get("telefono"):"",
      "TELEFONOTAG"=>$usuario?$usuario->get("telefonotag"):"",
      "KEY"=>$key,
                    "IDI"=>$inmueble?$inmueble->getID():($params["id"]?$params["id"]:"0_".$usuario->id."_0"),
        "CALLBUTTON"=>$telButton,
        "MESSAGEBUTTON"=>$messageButton
	);
    
     
    
    
    $sfxres = parent::out($plantilla,$p); 
    
    return $sfxres;
  }
} 
