<?php
class freemiumform_chunk extends chunk_base implements chunk{
	var $params;
	var $plantillas=array("freemiumform.html","freemiumform1.html","freemiumform2.html");
	var $freemium=array("freemiumform_button_freemium.html","freemiumform_button_freemium.html");
	var $standar=array("freemiumform_button_standar.html","freemiumform_button_standar.html");
	var $bound=array("freemiumform_button_bound.html","freemiumform_button_bound.html");
	function freemiumform_chunk($params=array()){
		$this->params=$params;
	}
	function out($params=array()){
    	global $user , $core, $config, $document,$inmueble, $user_view;
		
		$facturacion=$core->getApp("facturacion");
		
			
	      $p=array(
			);
		if($user_view->id==$user->id){
			
			$plantillas=$this->plantillas;
			
			$freemium= $user_view->getFreemiumAvaliable();
			$bound= $user_view->getBoundAvaliable();
			
			
			if($bound>0){
				$botones=$this->bound;
				$cuenta=$bound;
				}
			elseif($freemium>0){
				$botones=$this->freemium;
				$cuenta=$freemium;
				}
			else {
				$botones=$this->standar;
				$precio="USD$ ".$facturacion->getPrecio(2);
				}
			
			
			
			$pagos=$core->getChunk("formasdepago");
			$search=$core->getapp("search");
			$model=$this->params["model"]?$plantillas[$this->params["model"]]:$plantillas[0];
			$boton=$this->params["boton"]?$botones[$this->params["boton"]]:$botones[0];
			
   			$boton= file_get_contents($config->paths["chunks/html"].$boton);
   			$plantilla= file_get_contents($config->paths["chunks/html"].$model);
   			
			
			$productos=$this->renderProducts($facturacion,$freemium,$bound);
			
			$p["FormasDePago"]=$pagos->out();
			$p["BOTON"]=str_replace(array("#CUENTA#","#PRECIO#"),array($cuenta,$precio),$boton);
			$p["PRODUCTOS"]=$productos;
		}
		else {
   			$plantilla= file_get_contents($config->paths["chunks/html"].'freemiumform_2.html');
		}
	$document->addStyle("css/freemiumform.css");
	switch($this->params["script"]){
		case "2":
			$document->addScript("js/freemium2.js");
			break;
		default:
			$document->addScript("js/freemium.js");
		}
    return parent::out($plantilla,$p);
	}
    function renderProducts(facturacion_app $facturacion, $freemium, $bound){
    	global $config;
		$plantilla=file_get_contents($config->paths["chunks/html"].'freemium_products_product.html');
    	$products=file_get_contents($config->paths["chunks/html"].'freemium_products.html');
		$checked=false;
		$checked2=false;
    	if($freemium>0){
    		$frem=file_get_contents($config->paths["chunks/html"].'freemium_products_freemium.html');
    		$fr=$facturacion->getProduct(1);
			$p=array(
				"#ID#"=>$fr["id"],
				"#CLASS#"=>"freemium",
				"#TITLE#"=>$fr["nombre"],
				"#DESCRIPCION#"=>$fr["descripcion"],
				"#PRECIO#"=>"",
				"#TIEMPO#"=>"",
				"#GRUPO#"=>"tiempo",
				"#SELECTED#"=>"checked='checked'",
				"#TYPE#"=>"radio"
				);
			$checked=true;
			$frem=str_replace("#products#",str_replace(array_keys($p),$p,$plantilla),$frem);
    	}
		if($bound>0){
    		$standar=file_get_contents($config->paths["chunks/html"].'freemium_products_bound.html');
			$st="";
			$productos=$facturacion->getProductsByBound(0);
			foreach($productos as $fr){
				if($fr["tipo"]==1){
					$p=array(
						"#ID#"=>$fr["id"],
						"#CLASS#"=>"standar",
						"#TITLE#"=>$fr["nombre"],
						"#DESCRIPCION#"=>$fr["descripcion"],
						"#PRECIO#"=>"",
						"#TIEMPO#"=>$fr["dias"].' $$dias$$',
					"#GRUPO#"=>"tiempo",
					"#SELECTED#"=>$checked?"":"checked='checked'",
					"#TYPE#"=>"radio"
						);
					$checked=true;
					$st.=str_replace(array_keys($p),$p,$plantilla);
					}
				}
			$standar=str_replace("#products#",$st,$standar);
		}
		elseif($freemium<1) {
    		$standar=file_get_contents($config->paths["chunks/html"].'freemium_products_standar.html');
			$productos=$facturacion->getProductsByBound(2);
			$st="";
			foreach($productos as $fr){
				$p=array(
					"#ID#"=>$fr["id"],
					"#CLASS#"=>"standar",
					"#TITLE#"=>$fr["nombre"],
					"#DESCRIPCION#"=>$fr["descripcion"],
					"#PRECIO#"=>"USD$ ".$fr["precio"],
					"#TIEMPO#"=> $fr["dias"].' $$dias$$',
				"#GRUPO#"=>"tiempo",
				"#SELECTED#"=>$checked?"":"checked='checked'",
				"#TYPE#"=>"radio"
					);
				$checked=true;
				$st.=str_replace(array_keys($p),$p,$plantilla);
			}
			$standar=str_replace("#products#",$st,$standar);
		}
		if($freemium<1){
			$premium_plant=file_get_contents($config->paths["chunks/html"].'freemium_products_premium.html');
			$premiums=array(5,7,8);
			$premium="";
			foreach($premiums as $pr){
			  $fr=$facturacion->getProduct($pr);
			  $p=array(
						"#ID#"=>$fr["id"],
						"#CLASS#"=>"standar",
						"#TITLE#"=>$fr["nombre"],
						"#DESCRIPCION#"=>$fr["descripcion"],
						"#PRECIO#"=>"USD$ ".$fr["precio"],
						"#TIEMPO#"=> $fr["dias"].' $$dias$$',
					"#GRUPO#"=>"premium",
					"#SELECTED#"=>"",
				"#TYPE#"=>"checkbox"
						);
			$producto=str_replace(array_keys($p),$p,$plantilla);
			$p=array(
				"#products#"=>$producto,
				"#ID#"=>$pr
				);
			$premium.=str_replace(array_keys($p),$p,$premium_plant);
			}
		}
		$p=array(
			"#freemium#"=>$frem,
			"#standar#"=>$standar,
			"#premium#"=>$premium
			);
		return str_replace(array_keys($p),$p,$products);
    }
}