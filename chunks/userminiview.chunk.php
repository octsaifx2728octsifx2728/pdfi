<?php
class userminiview_chunk{
	function out(){
    	global $user , $core, $config, $document,$inmueble, $user_view;
   		$plantilla= file_get_contents($config->paths["chunks/html"].'userminiview.html');
		$p=array(
			);
		$salida=str_replace(array_keys($p),$p,$plantilla);
	    return $salida;
	}
}
