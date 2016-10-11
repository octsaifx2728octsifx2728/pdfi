<?php
class facebookuser_chunk {
  function out(){
    global $user,$config,$document;
    $token=$user->fb_token?$user->fb_token:$user->get("fb_token");
    if($token){
      $plantilla= file_get_contents($config->paths["chunks/html"].'facebookuser.html');
      $p=array(
	"#TOKEN#"=>$token
	);

      $document->addScript("js/facebookuser.js");
      return str_replace(array_keys($p),$p,$plantilla);
    }
    else
    {
      return "";
    }
  }
}
 
