<?php
class premium_chunk extends chunk_base implements chunk{
    
        protected $_plantillas=array("html/premium.html","html/premium_result.html");
        protected $_scripts=array("js/premium.js");
        protected $_styles=array("css/premium.css");
        protected $_selfpath="chunks/premium/";
        
	function out($vars=array()){
	    global $config,$core,$document,$searchResults;
            $premium=&$core->getApp("premium");
            $items=$premium->getItems();
            $p=array();
            $plantilla=$this->loadPlantilla(0);
	    $plantilla_items= $this->loadPlantilla(1);
             $itemsp="";
            foreach($items as $i){
		if(count($imagen=$i->getImage(0,1))){
                    $imgpath=$imagen[0]->path;
                    if(!$imagen[0]->path||!file_exists($imgpath)){
			$imgpath="galeria/imagenes/sinimagen.jpg";
		      	}
                }
		else {
                   $imgpath="galeria/imagenes/sinimagen.jpg";
                }
                
		$p=array(
		  "ID"=>$i->get("id_expediente")."_".$i->get("id_cliente")."_".$i->get("tipoobjeto"),
                    "TITLE"=>$i->get("titulo"),
                    "IMAGEN"=>$imgpath,
                    "URL"=>$i->getURL(),
                    "MONEDA"=>$core->getEnviromentVar("currency"),
                    "PRECIO"=>number_format($i->getPrecio($core->getEnviromentVar("currency")),2)
                    );
                $itemsp.=$this->parse($plantilla_items, $p);
            }
            
	    $p=array(
	      "ITEMS"=>$itemsp
	      );
            return parent::out($plantilla, $p); 
	}
}
