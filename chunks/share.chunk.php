<?php
class share_chunk{
  function out($params=array()){
    global $user , $core, $config, $document,$inmueble;
	$inm=$params["inmueble"]?$params["inmueble"]:$inmueble;
	
   $plantilla= file_get_contents($config->paths["chunks/html"].'share.html');
	
      if($imagen=$inm->getImage()){
		$imgpath=$imagen[0]->path;
		if(!$imagen||!file_exists($imgpath)){
		    $imgpath="apple-touch-icon.png";
		 	}
      	}
      else {
		$imgpath="apple-touch-icon.png";
	      }
	  
      $p=array(
	"#CLIENTE#"=>$inm->cliente,
	"#ID#"=>$inm->id,
	"#TITLE#"=>$inm->get("titulo"),
	"#URL#"=>$inm->getURL(),
	"#IMAGEN#"=>urldecode($imgpath)
	);

	$salida=str_replace(array_keys($p),$p,$plantilla);
    return $salida;
  }
}
