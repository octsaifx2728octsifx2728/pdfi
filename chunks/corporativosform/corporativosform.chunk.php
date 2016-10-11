<?php
class corporativosform_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/corporativosform/";
    protected $_plantillas=array('html/corporativosform.html','html/corporativosform_item.html');
    protected $_scripts=array("js/corporativosform.js");
    protected $_styles=array("css/corporativosform.css");
	function out($params=array()){
		global $config, $user_view,$user,$core,$document;
		if($user_view->id==$user->id){
                    if($user_view->getBoundAvaliable()){
			return "";
                    }
                    else {
			$plantilla= $this->loadPlantilla(0);
			$plantilla_item= $this->loadPlantilla(1);
                        
                    }
			$app=$core->getApp("productos");
			
			$productos=$app->getProductosByTipo(4);
			
			$prods="";
			
			foreach($productos as $pr){
				$p=array(
					"TITULO"=>$pr->get("nombre"),
					"DESCRIPCION"=>$pr->get("descripcion"),
					"PRECIO"=>$pr->get("precio"),
					"DIAS"=>$pr->get("dias"),
					"ID"=>$pr->id
					);
				$prods.= $this->parse($plantilla_item,$p);
			}
			
			
			$p=array(
				"PAQUETES"=>$prods,
                                "KEY"=>md5("dfsd".rand())
				);
		return parent::out($plantilla,$p);
			
		}
	}
	
}
