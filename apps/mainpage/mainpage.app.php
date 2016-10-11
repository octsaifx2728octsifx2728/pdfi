<?php 
class mainpage_app{
  function defaultTask($params=array()){
    global $core,$document;
    if($document){
      if(is_array($params["estilos"])){
	foreach($params["estilos"] as $estilo){
	  $document->addStyle($estilo);
	}
      }

      if(is_array($params["scripts"])){
	foreach($params["scripts"] as $script){
	  $document->addScript($script);
	}
      }
    }
  }
}