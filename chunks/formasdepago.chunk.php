<?php
class formasdepago_chunk{
	var $formas=array(
		"paypal"=>  array(
			"id"=>1,
			"titulo"=>'$$paypal$$',
			"activo"=>1,
			"default"=>1
			),
		"banorte"=>  array(
			"id"=>2, 
			"titulo"=>'$$tarjetadecredito$$',
			"activo"=>0
			)
		);
	function out($params){
		global $config,$document;
   		$plantilla= file_get_contents($config->paths["chunks/html"]."formasdepago.html");
   		$plantilla_item= file_get_contents($config->paths["chunks/html"]."formasdepago_item.html");
		$formas="";
		foreach($this->formas as $f){
			if($f["activo"]||$_GET["banorte"]){
				$p=array(
					"#ID#"=>$f["id"],
					"#TITULO#"=>$f["titulo"],
					"#CHECKED#"=>$f["default"]?" checked='checked'":""
					);
				$formas.=str_replace(array_keys($p),$p,$plantilla_item);
				}
		}
		$p=array(
			"#FORMAS#"=>$formas
		);
		$document->addStyle("css/formasdepago.css");
		$salida=str_replace(array_keys($p),$p,$plantilla);
	    return $salida;
	}
}
