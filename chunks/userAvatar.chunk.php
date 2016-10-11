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
			"#AVATAR#"=>str_replace(".jpg.jpg",".jpg","cache/0/180/".$user_view->getAvatar().".jpg")
			);
		return str_replace(array_keys($p),$p,$plantilla);
	}
}
