<?php
class favoritos_app{
	function change(inmueble $inmueble,$status){
		global $core,$user;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		if(!$user->id||!$inmueble->get("id_cliente")||!$inmueble->id){
			return false;
		}
		if($status){
			$this->del($inmueble);
			$this->add($inmueble);
		}
		else {
			$this->del($inmueble);
		}
		return true;
	}
	function del(inmueble $inmueble){
		global $core,$user;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		
		$q="delete from `favoritos`
				 where 
					`id_cliente`='".mysql_real_escape_string($user->id)."'
					and `id_clientefavo`='".mysql_real_escape_string($inmueble->get("id_cliente"))."'
					and `id_expedientefavo`='".mysql_real_escape_string($inmueble->id)."'
                                        and `tipo`='".$inmueble->get("tipoobjeto")."'";	
		mysql_query($q);
	}
	function add(inmueble $inmueble){
		global $core,$user;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
			$q="insert into`favoritos` (`id_cliente`,`id_clientefavo`,`id_expedientefavo`,`tipo`)
				values(
					'".mysql_real_escape_string($user->id)."',
					'".mysql_real_escape_string($inmueble->get("id_cliente"))."',
					'".mysql_real_escape_string($inmueble->id)."',
                                        '".$inmueble->get("tipoobjeto")."')";	
		mysql_query($q);
	}
}
