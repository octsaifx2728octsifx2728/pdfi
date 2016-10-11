<?php
class userMailbox_chunk{
	
  function out($params=array()){
    global $user , $core, $user_view,$config;
	
	if($user_view->id!=$user->id){
				return "";
				}
   $plantilla= file_get_contents($config->paths["chunks/html"].'userMailbox.html');
	$app=$core->getApp("mensajes");
	$mensajes=$app->retrieve($user_view);
	
      $p=array(
		);

	$salida=str_replace(array_keys($p),$p,$plantilla);
    return $salida;
  }
}
