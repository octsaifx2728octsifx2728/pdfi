<?php
class mensaje extends objeto{
	var $id;
        protected $_tabla="mensajes";
        public $owner=false;
	function mensaje($id){
		$this->id=$id;
	}
        
	function send(user $destinatario,$anuncio=null){
		global $core,$user;
		if($destinatario->get("id_cliente")){
			$q="insert into `buzon`(`id_cliente`,`id_mensaje`,`fecha`,`anuncio`)
				values(
				'".mysql_real_escape_string($destinatario->id)."',
				'".mysql_real_escape_string($this->id)."',
				now(),
                                '".mysql_real_escape_string($anuncio->id)."'
				)";
				mysql_query($q);
			$q="insert into `conversaciones`(`user1`,`user2`,`inmueble`,`inicio`,`user1read`,`user2read`) 
				values('".$user->id."','".mysql_real_escape_string($destinatario->id)."','".mysql_real_escape_string($anuncio->id)."',now(),1,0)";
			mysql_query($q);
			$q="insert into `conversaciones`(`user2`,`user1`,`inmueble`,`inicio`,`user1read`,`user2read`) 
				values('".$user->id."','".mysql_real_escape_string($destinatario->id)."','".mysql_real_escape_string($anuncio->id)."',now(),0,1)";
			mysql_query($q);
                        
			$q="update `conversaciones` set `last`=now(),`user2read`=0 
				where `user1`='".$user->id."'
					and `user2`='".mysql_real_escape_string($destinatario->id)."'
					and `inmueble`='".mysql_real_escape_string($anuncio->id)."'";
			mysql_query($q);
			$q="update `conversaciones` set `last`=now(),`user1read`=0
				where `user2`='".$user->id."'
					and `user1`='".mysql_real_escape_string($destinatario->id)."'
					and `inmueble`='".mysql_real_escape_string($anuncio->id)."'";
			mysql_query($q);
                        $q="select `id` from `conversaciones` where `user1`='".$user->id."'
					and `user2`='".mysql_real_escape_string($destinatario->id)."'
					and `inmueble`='".intval($anuncio->id)."' limit 1";
                        $r=mysql_query($q);
                        $i=  mysql_fetch_array($r);
                        $convID=$i["id"];
			$mailer=$core->getApp("mailer");
			$mailer->send(array(
				"destinatario"=>$destinatario->get("usuario"),
				"asunto"=>'$$nuevoMensajeRecibido$$',
				"plantilla"=>"html/notificacion.html",
				"variables"=>array(
					"NOMBRE_DESTINATARIO"=>$destinatario->get("nombre_pant"),
					"NOMBRE_REMITENTE"=>$user->get("nombre_pant"),
                                        "AVATAR"=>$user->getAvatar(),
					"LINKRESPONDER"=>$destinatario->getLink()."#conversacion:".$convID,
					"MENSAJE"=>$this->get("mensaje")
					)
				));
			return true;
		}
		else {
			return false;
		}
	}
        function set($campo,$valor){
            switch($campo){
                case "leido":
                    $this->setLeido();
                    break;
                default:
                return parent::set($campo, $valor);
            }
        }
	function get($campo){
		$q="select `mensajes`.`".mysql_real_escape_string($campo)."` 
				from `mensajes` 
					left join `buzon` on `buzon`.`id_mensaje`=`mensajes`.`id`
				where `id`='".mysql_real_escape_string($this->id)."' limit 1";
		$r=mysql_query($q);
                if($r){
                    $i=mysql_fetch_array($r,MYSQL_ASSOC);
                    return $i[$campo];
                    }
	}
	function setLeido(){
	  	global $config,$core,$user;
		    $db=&$core->getDB();
		    $con=&$db->getConexion();
		$q="update `buzon` set `leido`=1 where `id_mensaje`='".mysql_real_escape_string($this->id)."' and `id_cliente`='".mysql_real_escape_string($user->id)."'";
		//echo $q;
                mysql_query($q);
	}
	function borrar(){
	  	global $config,$core,$user;
		    $db=&$core->getDB();
		    $con=&$db->getConexion();
		$q="delete from `buzon` where  `id_mensaje`='".mysql_real_escape_string($this->id)."' and `id_cliente`='".mysql_real_escape_string($user->id)."'";
		mysql_query($q);
	}
}
