<?php
class inmuebleServicios_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/inmuebleServicios/";
    protected $_plantillas=array(
        'html/inmuebleServicios.html',
        'html/item2.html'
    );
    protected $_styles=array(
        "css/icons.css"
    );
	function out($params=array()){
		global $inmueble,$config;
                if(!$inmueble){
                    return false;
                }
		$plantilla= $this->loadPlantilla(0);
		$plantilla_item= $this->loadPlantilla(1);
                                
                $items=$inmueble->getItemsGeneral();
                $itemsp="";
                foreach($items as $k=>$v){
                   $p=array("K"=>$k,"V"=>($v->valor?"1":"0"),"N"=>$v->nombre);
                   $itemsp.=$this->parse($plantilla_item, $p);
                   }
                $p=array(
                    "ITEMS"=>$itemsp,
                    "TIPOOBJETO"=>$inmueble->get("tipoobjeto")
                        );
                return parent::out($plantilla,$p);
	}
}
