<?php 
class parsedResults_chunk extends chunk_base implements chunk{
  private $_tipos_objeto=array("",'$$casa$$','$$departamento$$');
  protected $_selfpath="chunks/parsedResults/";
  protected $_plantillas=array(
      'html/search_result.html',
        'html/item2.html',
        'html/imagen.html',
        'html/container.html',
      'html/search_result_terreno.html',
      'html/search_result_funerario.html'
  );
    protected $_styles=array();
    protected $_scripts=array(
        "js/contact.js",
        "js/parsedResults.js"
    );
  function out($inmuebles=false){
    global $core, $config, $searchResults,$document;
    $plantilla= $this->loadPlantilla(0);
    $plantilla_container= $this->loadPlantilla(3);
    $plantilla_item= $this->loadPlantilla(1);
    $plantilla_5= $this->loadPlantilla(4);
    $plantilla_6= $this->loadPlantilla(5);
    $plantilla_imagen= $this->loadPlantilla(2);
    $ranking=$core->getChunk("ranking");
    $share=$core->getChunk("share");
    $favorito=$core->getChunk("favorito_button");
    $contact=$core->getChunk("contact");
    $task=explode(":",$_GET["task"]);
    $title=  str_replace("-"," ",htmlentities($task[4],null,"UTF-8"));
    $results=array();
	$x=1;
	$inms=$inmuebles?$inmuebles:$searchResults;
        $imagenesW=0;
     /*
    foreach($inms as $inmueble)
      {
        $items=$inmueble->getItemsGeneral();
        $itemsp="";
        foreach($items as $k=>$v){
            $p=array("K"=>$k,"V"=>($v->valor?"1":"0"),"N"=>$v->nombre);
            $itemsp.=$this->parse($plantilla_item, $p);
            }
                   $imagenes="";
                   $url=$inmueble->getURL();
      if(count($imagen=$inmueble->getImage(0,5))){
          //print_r($imagen);
          foreach($imagen as $img){
              $pi=array(
                  "IMAGEN"=>$img->path,
                  "URL"=>$url
              );
		if(!$pi["IMAGEN"]||!file_exists($pi["IMAGEN"])){
		    $pi["IMAGEN"]="galeria/imagenes/sinimagen.jpg";
		 	}
                        else{
                           $pi["IMAGEN"]=
                                   //<(intval($inmueble->get("vendido"))?"stamp/vendido/":"").
                                   $pi["IMAGEN"]; 
                        }
                $imagenes.=$this->parse($plantilla_imagen,$pi);
          }
          $imagenesW=count($imagen)*250;
      	}
      else {
              $pi=array(
                  "IMAGEN"=>"galeria/imagenes/sinimagen.jpg",
                  "URL"=>$url
              );
                $imagenes.=$this->parse($plantilla_imagen,$pi);
            $imagenesW=250;
	      }
	$espremium=strtotime($inmueble->get("fecvenpremium"))>=time()?"destacado_1":"";	
      $p=array(
          "ITEMS"=>$itemsp,
      "DESTACADO"=>$espremium,
          "USERID"=>$inmueble->get("id_cliente"),
      "DUPLEX"=>$x,
	"ID"=>$inmueble->get("id_expediente")."_".$inmueble->get("id_cliente")."_".$inmueble->get("tipoobjeto"),
          "IMGWRAPWIDTH"=>$imagenesW,
          "VENDIDO"=>intval($inmueble->get("vendido"))?"1":"0",
	"IMAGENES"=>$imagenes,
          "METRICA"=>$core->getEnviromentVar("metrica"),
	"CASA"=>$inmueble->get("tipoobjeto"),
	"SUBTIPO"=>$inmueble->get("subtipo"),
	"SUBTIPONOMBRE"=>$inmueble->get("subtiponombre")." ".$inmueble->ontransactiontext[$inmueble->get("tipovr")],
	"CASA_TITLE"=>$this->_tipos_objeto[$inmueble->get("tipoobjeto")],
	"AMUEBLADO"=>$inmueble->get("amueblado"),
	"NOAMUEBLADO"=>$inmueble->get("noamueblado"),
	"AMUEBLADO_TITLE"=>$inmueble->get("amueblado")==1?'/$$amueblado$$':'',
	"NOAMUEBLADO_TITLE"=>$inmueble->get("noamueblado")==1?'/$$noamueblado$$':'',
	"TITLE"=>$inmueble->get("titulo"),
	"DESCRIPCION"=>($inmueble->get("descripcion")),
          "VIEWTEXT"=>$inmueble->sublinktext[$inmueble->get("subtipo")]?$inmueble->sublinktext[$inmueble->get("subtipo")]:$inmueble->linktext,
	"RANKING"=>$ranking->out(array("id"=>$inmueble->id,"cliente"=>$inmueble->cliente)),
	"SHARE"=>$share->out(array("inmueble"=>$inmueble)),
	"FAVORITO"=>$favorito->out(array("inmueble"=>$inmueble)),
	"CONTACT"=>$contact->out(array("id"=>$inmueble->getID(),"cliente"=>$inmueble->get("id_cliente"),"title"=>$inmueble->get("titulo"))),

	"URL"=>$url,

	"MONEDA"=>$core->getEnviromentVar("currency"),
	"PRECIO"=>number_format($inmueble->getPrecio($core->getEnviromentVar("currency")),0),
	"MONEDAORIG"=>$inmueble->get("precio_moneda"),
	"PRECIOORIG"=>number_format($inmueble->get("precio"),0),
	"PRECIOM2"=>number_format($inmueble->getPreciom2($core->getEnviromentVar("currency")),0),
	"HABITACIONES"=>intval($inmueble->get("habitaciones")),
	"M2"=>intval($inmueble->get("m2")),
	"SUPERFICIE"=>intval($inmueble->get("m2s")),
	"BANOS"=>intval($inmueble->get("banos")),
	"ESTACIONAMIENTOS"=>intval($inmueble->get("estacionamientos")),
	"ESTACIONAMIENTOS2"=>intval($inmueble->get("estacionamientos2")),
	"CONSTRUCCION"=>intval($inmueble->get("anio")),

	);
      switch($inmueble->get("tipoobjeto")){
          case "5":
                $plant_item=$plantilla_5;
              break;
          case "6":
                $plant_item=$plantilla_6;
              break;
          default:
                $plant_item=$plantilla;
      }
      
      $sd = $this->parse($plant_item,$p);    
      $sd=preg_replace('/#[A-Za-z0-9]*#/i', "", $sd);
         
      $results[]=$sd;
	  $x=$x==1?2:1;
      }
      */
        if($document){
            return parent::out($plantilla_container,array("TITULO"=>$title,"RESULTS"=>implode("",$results)));
        }
        else {
            return $results;
        }
  }
}