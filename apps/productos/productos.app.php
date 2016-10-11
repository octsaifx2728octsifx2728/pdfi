<?php
class productos_app{
	function getProductosByTipo($tipo){
		global $config,$core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$q="select `id` from `productos` where `tipo`='".mysql_real_escape_string($tipo)."' ";
		$r=mysql_query($q);
		$productos=array();
		$core->loadClass("producto");
		while($i=mysql_fetch_array($r, MYSQL_ASSOC)){
			$productos[]=new producto($i["id"]);
		}
		return $productos;
	}
}
