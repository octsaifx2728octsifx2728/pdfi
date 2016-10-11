<?php
include_once 'config/authorize.config.php';
class formularioAuthorize_chunk extends chunk_base implements chunk{
    protected $_plantillas=array("html/formularioAuthorize.html",'html/formularioAuthorize_items.html');
    protected $_selfpath="chunks/formularioAuthorize/";
	function out($params=array()){
    	global $user , $config, $document,$factura;
   		$plantilla= $this->loadPlantilla(0);
   		$plantilla_items= $this->loadPlantilla(1);
		
		$items=$factura->getComandas();
		$parsedItems="";
                //echo $factura->id;
		foreach($items as $i)
			{
				$p=array(
					"ID"=>$i["id"],
					"NOMBRE"=>$i["nombre"],
					"CANTIDAD"=>$i["cantidad"],
					"TOTAL"=>number_format($i["monto"]*$i["cantidad"],2)
					);
				$parsedItems.=$this->parse($plantilla_items, $p);
			}
		
		$p=array(
			"PRODUCTOS"=>$parsedItems,
			"TOTAL"=>$factura->getTotal(),
			"NOMBRE"=>$user->get("firstName")?$user->get("firstName"):'$$nombre$$',
			"APELLIDOS"=>$user->get("lastName")?$user->get("lastName"):'$$apellidos$$',
			"COMPANIA"=>$user->get("businessName")?$user->get("businessName"):'$$compania$$',
			"TELEFONO"=>$user->get("telefono")?$user->get("telefono"):'$$telefono$$',
			"FAX"=>$user->get("fax")?$user->get("fax"):'$$fax$$',
			"DIRECCION1"=>$user->get("street")?$user->get("street"):'$$direccion$$',
			"DIRECCION2"=>$user->get("street2")?$user->get("street2"):'$$direccion$$',
			"PAIS"=>$user->get("pais"),
			"ESTADO"=>$user->get("estado"),
			"CIUDAD"=>$user->get("city"),
			"NACIMIENTO"=>$user->get("nacimiento"),
			"EMAIL"=>$user->get("usuario"),
			"RFC"=>$user->get("rfc"),
			"FACTURA"=>$factura->id,

            "LOGINID"=>$config->authorize["loginId"],
            "TRANKEY"=>$config->authorize["tranKey"],
            "VERSION"=>$config->authorize["version"],
            "DELIMDATA"=>$config->authorize["delimData"],
            "DELIMCHAR"=>$config->authorize["delimChar"],
            "RELAYRESPONSE"=>$config->authorize["relayResponse"],
            "TYPE"=>$config->authorize["type"],
            "METHOD"=>$config->authorize["method"],

            "PROCESSURL"=>$config->paths["surlbase"]."/app/authorize/process",
            //"ForwardPath"=>$config->paths["surlbase"]."/app/procesarpago/3dSecure",
            //"Cert3D"=>"03",

                        /*
                        "MERCHANTID"=>$config->banorte["MerchantID"],
                        "MERCHANTNAME"=>$config->banorte["MerchantName"],
                        "MERCHANTCITY"=>$config->banorte["MerchantCity"],
                        "ForwardPath"=>$config->paths["surlbase"]."/app/procesarpago/3dSecure",
                        "Cert3D"=>"03",
                        */
            "Token"=>$factura->getToken()
			);
		$salida=$this->parse($plantilla, $p);
	    return $salida;
	}
}
