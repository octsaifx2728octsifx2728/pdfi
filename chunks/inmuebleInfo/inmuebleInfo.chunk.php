<?php
class inmuebleInfo_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/inmuebleInfo/";
    protected $_plantillas=array(
        "html/inmuebleInfo.html",
        "html/inmuebleInfo_terreno.html",
        "html/inmuebleInfo_funerario.html"
    );
	function out($params=array()){
		global $inmueble,$core;
                switch($inmueble->get("tipoobjeto")){
                    case "5":
                    $plantilla= $this->loadPlantilla(1);
                        break;
                    case "6":
                    $plantilla= $this->loadPlantilla(2);
                        break;
                    default:
                    $plantilla= $this->loadPlantilla(0);
                }
		$p=array(
                    "CASA"=>$inmueble->get("casa"),
                    "CASA_TITLE"=>$inmueble->get("casa")==1?'$$casa$$':'$$departamento$$',
                    "AMUEBLADO"=>$inmueble->get("amueblado"),
                    "TIPOOBJETO"=>$inmueble->get("tipoobjeto"),
                    "AMUEBLADO_TITLE"=>$inmueble->get("amueblado")==1?'$$amueblado$$':'$$amueblado_no$$',

                    "METRICA"=>$core->getEnviromentVar("metrica"),
                    "MONEDA"=>$core->getEnviromentVar("currency"),
                    "PRECIO"=>number_format($inmueble->getPrecio($core->getEnviromentVar("currency")),0),
                    "MONEDAORIG"=>$inmueble->get("precio_moneda"),
                    "PRECIOORIG"=>number_format($inmueble->get("precio"),2),
                    "PRECIOM2"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency")),0),
                    "HABITACIONES"=>intval($inmueble->get("habitaciones")),
                    "M2"=>intval($inmueble->get("m2")),
                    "SUPERFICIE"=>intval($inmueble->get("m2s")),
                    "BANOS"=>intval($inmueble->get("banos")),
                    "ESTACIONAMIENTOS"=>intval($inmueble->get("estacionamientos")),
                    "ESTACIONAMIENTOS2"=>intval($inmueble->get("estacionamientos2")),
                    "CONSTRUCCION"=>intval($inmueble->get("anio"))
                    );
		return parent::out($plantilla, $p);
	}
}
