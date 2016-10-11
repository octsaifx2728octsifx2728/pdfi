<?php
class inmuebleJson_chunk extends chunk_base implements chunk{
	function out($params=array()){
		global $inmueble,$core;
		if($inmueble){
                    $mc=$core->getApp("metricaconverter");
			$plantilla=array(
	"id"=>$inmueble->get("id_expediente")."_".$inmueble->get("id_cliente")."_".$inmueble->get("tipoobjeto"),
	"imagen"=>$inmueble->getImage(),
	"casa"=>$inmueble->get("casa"),
	"tipoobjeto"=>$inmueble->get("tipoobjeto"),
	"amueblado"=>$inmueble->get("amueblado"),
	"title"=>utf8_encode($inmueble->get("titulo")),
	"descripcion"=>utf8_encode($inmueble->get("descripcion")),
          "vendido"=>intval($inmueble->get("vendido")),   
          "destacado"=>strtotime($inmueble->get("fecvenpremium"))>=time(),            

	"url"=>$inmueble->getURL(),

	"moneda"=>$core->getEnviromentVar("currency"),
	"precio"=>number_format($inmueble->getPrecio($core->getEnviromentVar("currency")),2),
	"precio-raw"=>($inmueble->getPrecio($core->getEnviromentVar("currency"))),
	"preciom2"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency")),2),
	"preciom2metros"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency"),"metros"),2),
	"preciom2pies"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency"),"pies"),2),
	"habitaciones"=>intval($inmueble->get("habitaciones")),
	"m2"=>number_format($inmueble->get("m2"),2),
	"m2-raw"=>($inmueble->get("m2-raw")),
	"m2-metros"=>number_format($mc->convert($inmueble->get("m2-raw"),$inmueble->get("metrica"),"metros"),2),
	"m2s-pies"=>number_format($mc->convert($inmueble->get("m2s-raw"),$inmueble->get("metrica"),"pies"),2  ),
	"m2s-metros"=>number_format($mc->convert($inmueble->get("m2s-raw"),$inmueble->get("metrica"),"metros"),2),
	"m2-pies"=>number_format($mc->convert($inmueble->get("m2-raw"),$inmueble->get("metrica"),"pies"),2  ),
	"superficie"=>number_format($inmueble->get("m2s"),2),
	"banos"=>intval($inmueble->get("banos")),
	"estacionamiento"=>intval($inmueble->get("estacionamiento")),
	"m2a"=>intval($inmueble->get("m2a")),

	"jardin"=>intval($inmueble->get("jardin")),
	"lavado"=>intval($inmueble->get("lavado")),
	"servicio"=>intval($inmueble->get("cuarto_servicio")),
	"vestidor"=>intval($inmueble->get("vestidor")),
	"estudio"=>intval($inmueble->get("estudio")),
	"tv"=>intval($inmueble->get("tv")),
	"cocina"=>intval($inmueble->get("cocina")),
	"chimenea"=>intval($inmueble->get("chimenea")),
	"terraza"=>intval($inmueble->get("terraza")),
	"jacuzzi"=>intval($inmueble->get("jacuzzi")),
	"alberca"=>intval($inmueble->get("alberca")),
	"vista"=>intval($inmueble->get("vista")),
	"aire"=>intval($inmueble->get("aire")),
	"calefaccion"=>intval($inmueble->get("calefaccion")),
	"bodega"=>intval($inmueble->get("bodega")),
	"elevador"=>intval($inmueble->get("elevador")),
	"elevadors"=>intval($inmueble->get("elevadors")),
	"portero"=>intval($inmueble->get("portero")),
	"sistema_seguridad"=>intval($inmueble->get("sistema_seguridad")),
	"circuito"=>intval($inmueble->get("circuito")),
	"red"=>intval($inmueble->get("red")),
	"gimnasio"=>intval($inmueble->get("gimnasio")),
	"spa"=>intval($inmueble->get("spa")),
	"golf"=>intval($inmueble->get("golf")),

	"coordenaday"=>strval($inmueble->get("coordenaday")),
	"coordenadax"=>strval($inmueble->get("coordenadax"))
	);
			return json_encode($plantilla);
		}
	}
}
