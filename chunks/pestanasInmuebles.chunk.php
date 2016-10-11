<?php
class pestanasInmuebles_chunk{
	function out(){
   		global $user , $config, $document, $user_view;
		
		if($user_view->id==$user->id){
   			$plantilla= file_get_contents($config->paths["chunks/html"].'pestanasInmuebles.html');
			$p=array();
			$salida=str_replace(array_keys($p),$p,$plantilla);
			if($document){
				$document->addScript("js/pestanasInmuebles.js");
			}
		    return $salida;
		}	
	}
}
