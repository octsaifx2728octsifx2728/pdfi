<?php
class jsonResults_chunk{
  private $_tipos_objeto=array("",'$$casa$$','$$departamento$$');
  function out($params=array()){
    global $searchResults, $core;
    $plantilla=array();

    $mc=$core->getApp("metricaconverter");
    $ranking=$core->getChunk("ranking");
    $share=$core->getChunk("share");
    $contact=$core->getChunk("contact");
    $favorito=$core->getChunk("favorito_button");
	$lexicon=$core->getLexicon();
	if($lexicon){
                foreach($this->_tipos_objeto as $k=>$v){
                    $this->_tipos_objeto[$k]=$lexicon->traduce($v);
                    
                }
		$amueblado_1=$lexicon->traduce('$$amueblado$$');
		$amueblado_2=$lexicon->traduce('$$amueblado_no$$');
	    //$ranking=$lexicon->traduce($ranking);
	    //$share=$lexicon->traduce($share);
	    //$contact=$lexicon->traduce($contact);
	}
    foreach($searchResults as $inmueble)
      {

      if(count($imagen=$inmueble->getImage(0,1))){
	$imgpath=$imagen[0]->path;
	if(!$imagen[0]->path||!file_exists($imgpath)){
	  $imgpath="galeria/imagenes/".$inmueble->cliente."_".$inmueble->id."_1.jpg";
	  if(!file_exists($imgpath)){
	    $imgpath="galeria/imagenes/sinimagen.jpg";
	    }
	  }
      }
      else {
	$imgpath="galeria/imagenes/sinimagen.jpg";
      }


    $ranking1=$ranking->out(array("id"=>$inmueble->id,"cliente"=>$inmueble->cliente));
    $share1=$share->out(array("inmueble"=>$inmueble));
    $contact1=$contact->out(array("id"=>$inmueble->getID(),"cliente"=>$inmueble->get("id_cliente"),"title"=>$inmueble->get("titulo")));
	$favorito1=$favorito->out(array("inmueble"=>$inmueble));
	
	if($lexicon){
		   $ranking1=$lexicon->traduce($ranking1);
	    $share1=$lexicon->traduce($share1);
	    $contact1=$lexicon->traduce($contact1);
	    $favorito1=$lexicon->traduce($favorito1);
	}
	
	$espremium=strtotime($inmueble->get("fecvenpremium"))>=time();	
	
      $plantilla[]=array(
      "destacado"=>$espremium,
	"id"=>$inmueble->get("id_expediente")."_".$inmueble->get("id_cliente")."_".$inmueble->get("tipoobjeto"),
	
        "imagen"=>$imgpath,
	"casa"=>$inmueble->get("tipoobjeto"),
          
	"amueblado"=>$inmueble->get("amueblado"),
	"vendido"=>intval($inmueble->get("vendido")),
	"title"=>utf8_encode($inmueble->get("titulo")),
	"descripcion"=>utf8_encode($inmueble->get("descripcion")),
	"casa_title"=>$this->_tipos_objeto[$inmueble->get("tipoobjeto")],
	"amueblado_title"=>$inmueble->get("amueblado")==1?$amueblado_1:$amueblado_2,

	"ranking"=>$ranking1,
	"share"=>$share1,
	"contact"=>$contact1,
	"favorito"=>$favorito1,

	"url"=>$inmueble->getURL(),

	"moneda"=>$core->getEnviromentVar("currency"),
	"precio"=>number_format($inmueble->getPrecio($core->getEnviromentVar("currency")),0),
	"precio-raw"=>$inmueble->getPrecio($core->getEnviromentVar("currency")),
	"preciom2"=>number_format($inmueble->get("preciom2"),0),
	"preciom2metros"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency"),"metros"),0),
	"preciom2pies"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency"),"pies"),0),
	"monedaorig"=>$inmueble->get("precio_moneda"),
	"precioorig"=>number_format($inmueble->get("precio"),0),
	"habitaciones"=>intval($inmueble->get("habitaciones")),
	"m2"=>number_format($inmueble->get("m2"),0),
	"m2-raw"=>floatval($inmueble->get("m2-raw")),
	"m2-metros"=>number_format($mc->convert($inmueble->get("m2-raw"),$inmueble->get("metrica"),"metros"),0),
	"m2s-pies"=>number_format($mc->convert($inmueble->get("m2s-raw"),$inmueble->get("metrica"),"pies"),0  ),
	"m2s-metros"=>number_format($mc->convert($inmueble->get("m2s-raw"),$inmueble->get("metrica"),"metros"),0),
	"m2-pies"=>number_format($mc->convert($inmueble->get("m2-raw"),$inmueble->get("metrica"),"pies"),0  ),
	"superficie"=>number_format($inmueble->get("m2s"),0),
	"banos"=>intval($inmueble->get("banos")),
	"estacionamientos"=>intval($inmueble->get("estacionamientos")),
	"m2a"=>intval($inmueble->get("anio")),

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

      }
    if($params["api"]){
      return $plantilla;
    }
    else {
      return json_encode($plantilla);
      }
  }
}