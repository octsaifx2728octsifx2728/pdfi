<?php
class userAvatar_chunk {
	function out(){
		global $user, $user_view,$config;
		
			if($user_view->id==$user->id){
   				$plantilla= file_get_contents($config->paths["chunks/html"].'userAvatar.html');
				}
			else {
   				$plantilla= file_get_contents($config->paths["chunks/html"].'userAvatar_off.html');
				}
		$p=array(
			"#AVATAR#"=>$user_view->getAvatar()
			);
		return str_replace(array_keys($p),$p,$plantilla);
	}
}
