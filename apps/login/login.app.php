<?php
class login_app{
	function sendPasswordToEmail($email){
		global $core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$q="select `id_cliente` from `usuarios` where `usuario`='".mysql_real_escape_string($email)."' limit 1";
		$r=mysql_query($q);
		if(!mysql_num_rows($r))return false;
		$i=mysql_fetch_array($r,MYSQL_ASSOC);
		$usuario=new user($i["id_cliente"]);
		$usuario->sendPasswordtoEmail();
	}
}
