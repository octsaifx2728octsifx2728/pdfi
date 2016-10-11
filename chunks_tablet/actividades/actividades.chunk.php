<?php
class actividades_chunk extends chunk_base implements chunk{
	protected $_plantillas=array("html/actividades.html",
            "html/actividades_item.html",
            "html/actividades_register.html",
            "html/actividades_publish.html",
            "html/actividades_facebook.html",
            "html/actividades_twitter.html");
        protected $_selfpath="chunks_tablet/actividades/";
        protected $_scripts=array("js/actividades.js");
	function out($params=array()){
	    global $config,$core,$document;
            $cache=$core->getApp("cache");
            if($respuesta=$cache->loadData("actividadesFeed_tablet_".$core->getEnviromentVar("languaje"))){
                //return $respuesta;
            }
            $app=&$core->getApp("actividades");
            $manager=&$core->getApp("inmueblesManager");
            $actividades=$app->get(0,"desc",$max=100);
            
	    $plantilla= $this->loadPlantilla(0);
	    $lastid=false;
	    $firstid=0;
            
                $x=0;
                $items=array();
                
                $peso1=0;
                $peso2=0;
            foreach($actividades as $act){
                $lastid=$lastid?$lastid:$act->id;
	    	$firstid=$act->id;
                $peso=1;
		$usuario=new user($act->usuario);
		   switch($act->tipo){
			case 1: //Inmueble publicado
                            $in=$manager->getInmueble($act->objeto,$act->tipoobjeto);
                            //echo $in->id;
                            if($in&&$in->isActive()){
                                
				$foto=$in->getImage();
                                $foto=$foto[0]->path;
				$description=$in->get("descripcion");
                                $titulo=$in->get("titulo");
                                $link=$in->getURL();
                                
                                $linktext=$in->sublinktext[$in->get("subtipo")]?$in->sublinktext[$in->get("subtipo")]:$in->linktext;
                                $transactiontext=$in->ontransactiontext[$in->get("tipovr")];
                                $plantilla_items=$this->loadPlantilla(3);
                                $peso=500;
				}
			    else {
				$plantilla_items="";
                                $titulo="";
				}
			break;
                        case "2":
                                $plantilla_items=$this->loadPlantilla(4);
                                $titulo=$act->fb_data->story?$act->fb_data->story:'$$facebook_activity$$';
                                $peso=$act->fb_data->story?20:500;
                                $link=$act->link;
                                $act->actividad=str_replace(array("_s.jpg","_s.png"),array("_n.jpg","_n.png"),$act->actividad.($act->fb_data->picture?"<img class='fb_picture' title='".htmlentities($titulo)."' src='".$act->fb_data->picture."'>":""));
                            break;
                        case "3":
                                $plantilla_items=$this->loadPlantilla(5);
                                $titulo='$$twitter_activity$$';
                                $link=$act->link;
                                //$peso=80;
                            break;
			default:
                           $plantilla_items=$this->loadPlantilla(2);
                          //  $peso=60;
			break;
                    
                        }
	    	$p=array(
	    		"ID"=>$act->id,
	    		"fecha"=>$act->fecha,
	    		"actividad"=>$act->actividad,
	    		"link"=>$link,
                        "LINKTEXT"=>$linktext,
                        "TRANSACTIONTEXT"=>$transactiontext,
	    		"usuario"=>$act->usuario,
                       
	    		"nivel"=>$act->nivel,
	    		"nombre_pant"=>$usuario->get("nombre_pant"),
	    		"avatar"=>$usuario->getAvatar(),
	    		"userlink"=>$usuario->getLink(),
	    		"titulo"=>$titulo,
	    		"FOTO"=>$foto?$foto:"galeria/imagenes/sinimagen.jpg",
	    		"FECHA"=>$core->parseDate(strtotime($act->fecha),"longtime"),
	    		"DESCRIPTION"=>$description
				);   
                      $items[]=$this->parse($plantilla_items, $p);
                    $x=$x?0:1;
                }
            $p=array(
                "ITEMS"=>implode("",$items),
			"LASTID"=>$lastid,
			"FIRSTID"=>$firstid);
            $respuesta=parent::out($plantilla,$p);
            $cache->storeData("actividadesFeed_tablet_".$core->getEnviromentVar("languaje"),$respuesta,time()+300);
            return $respuesta;
	}
}