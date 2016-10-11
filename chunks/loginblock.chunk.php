<?php
class loginblock_chunk {
  function out(){
    global $user,$config,$document;
    if ($user->logged) {
      $plantilla= file_get_contents($config->paths["chunks/html"].'loginblock.1.html');
    }
    else {
      $plantilla= file_get_contents($config->paths["chunks/html"].'loginblock.2.html');
    }
    if($user->facebookLogged()){
      $fb_chunk=str_replace("#TOKEN#",$user->get("fb_token"),file_get_contents($config->paths["chunks/html"].'facebook_profile.html'));
      $document->addScript("js/facebookuser.js");
    }
    else {
      $fb=$user->logged?"": $fb_chunk=str_replace("#URL#",$user->getFB_login_link(),file_get_contents($config->paths["chunks/html"].'login_facebookLogin.html'));
    }
    $p=array(
      "#NOMBRE#"=>$user->get("nombre_pant"),
      "#FB_LOGIN#"=>$fb_chunk,
      "#LINK#"=>$user->getLink(),
      "#AVATAR#"=>$user->getAvatar()
      );
    $document->addStyle("css/loginblock.css");
//    $document->addStyle("css/homepage.css");
	
    $document->addScript("js/loginblock.js");
    return str_replace(array_keys($p),$p,$plantilla);
  }
}
