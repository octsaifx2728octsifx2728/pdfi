<?php
define("MESSAGE_ERROR_LOGIN_REQUIRED",1);
define("MESSAGE_ERROR_CANNOT_SAVE_MESSAGE",2);
class mensajes_app {
	function store($email,$subject,$text,inmueble $inmueble=null){
		global $config, $user,$core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		if(!$user->id){
			return MESSAGE_ERROR_LOGIN_REQUIRED;
		}
		$q="insert into `mensajes`(`id_cliente`,`id_expediente`,`usuario`,`nombre`,`asunto`,`mensaje`,`fecha`)
			values(
				'".mysql_real_escape_string($user->id)."',
				'".mysql_real_escape_string($inmueble->id)."',
				'".mysql_real_escape_string($email)."',
				'".mysql_real_escape_string($user->get("nombre_pant"))."',
				'".mysql_real_escape_string($subject)."',
				'".mysql_real_escape_string($text)."',
				now()
				)";
		mysql_query($q);
		$id=mysql_insert_id();
		if(!$id){
			return MESSAGE_ERROR_CANNOT_SAVE_MESSAGE;
		}
		include_once $config->paths["core/class"]."mensaje.class.php";
		$mensaje=new mensaje($id);
		return $mensaje;
	}
        
        function store2($email,$subject,$text,inmueble $inmueble=null){
		global $config, $user,$core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		
                
                
		$q="insert into `mensajes`(`id_cliente`,`id_expediente`,`usuario`,`nombre`,`asunto`,`mensaje`,`fecha`)
			values(
				'".mysql_real_escape_string($user->id)."',
				'".mysql_real_escape_string($inmueble->id)."',
				'".mysql_real_escape_string($email)."',
				'".mysql_real_escape_string($user->get("nombre_pant"))."',
				'".mysql_real_escape_string($subject)."',
				'".mysql_real_escape_string($text)."',
				now()
				)";
		mysql_query($q);
		$id=mysql_insert_id();
		if(!$id){
			return MESSAGE_ERROR_CANNOT_SAVE_MESSAGE;
		}
		include_once $config->paths["core/class"]."mensaje.class.php";
		$mensaje=new mensaje($id);
		return $mensaje;
	}
        
	function send(){
		
	}
        function getHeadersForUser(user $user){
            global $core;
	    $db=&$core->getDB(0,2);
            $mensajes=array();
            $q="select distinct `id`
                from `conversaciones`
			where  `user2` = '".$db->real_escape_string($user->id)."'
                order by `last` DESC";
            if($r=$db->query($q)){
                $core->loadClass("conversacion");
                while($i=$r->fetch_assoc()){
                    
                    $mensajes[]=new conversacion($i["id"]);
                }
            }
            return $mensajes;
        }
	function retrieve(user $user){
		global $config,$core;
	    $db=$core->getDB();
	    $con=$db->getConexion();
		$q="select `id_mensaje` , `leido`
		from `buzon`
			where `id_cliente` = '".mysql_real_escape_string($user->id)."'
			order by `fecha` DESC
			limit 100";
		$r=mysql_query($q);
		include_once $config->paths["core/class"]."mensaje.class.php";
		$mensajes=array();
		while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
			$men=new mensaje($i["id_mensaje"]);
			$men->to=$user;
			$men->leido=intval($i["leido"]);
			$mensajes[]=$men;
		}
		return $mensajes;
	}
}
