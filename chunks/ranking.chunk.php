<?php
class ranking_chunk{
  function out($params=array()){
    global $user , $core, $config, $document, $inmueble;
   $plantilla= file_get_contents($config->paths["chunks/html"].'favoritos.html');
    $estrella="";
	if(!$params["id"]||!$params["cliente"]&&$inmueble){
		$params["id"]=$inmueble->id;
		$params["cliente"]=$inmueble->cliente;
	}
    if($params["id"]&&$params["cliente"]){
      $favorito=false;
      if($user->logged){
	$plantilla_estrella= file_get_contents($config->paths["chunks/html"].'favoritos_estrella.html');
	$db=&$core->getDB();
	$con=&$db->getConexion();
	$q="select `id_cliente`
	      from `favoritos`
	      where `id_clientefavo`='".mysql_real_escape_string($params["cliente"])."'
	      and `id_expedientefavo`='".mysql_real_escape_string($params["id"])."'
	      and `id_cliente`='".mysql_real_escape_string($user->id)."'
	      limit 1";
	$r=mysql_query($q);
	if(mysql_num_rows($r)){
	  $favorito=true;
	  }

      $p=array(
	"#FAVORITO#"=>$favorito?"favorito":"nofavorito",
	"#TITLE#"=>$favorito?'$$favorites_delete$$':'$$favorites_add$$',
	"#CLIENTE#"=>$params["cliente"],
	"#ID#"=>$params["id"]
	);

	$estrella=str_replace(array_keys($p),$p,$plantilla_estrella);
	}
      }
      $p=array(
	"#CLIENTE#"=>$params["cliente"],
	"#ID#"=>$params["id"],
	"#ESTRELLA#"=>$estrella
	);

    //$document->addScript("https://apis.google.com/js/plusone.js");
	$salida=str_replace(array_keys($p),$p,$plantilla);
    return $salida;
  }
}
