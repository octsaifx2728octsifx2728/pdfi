<?php

define('NORMAL_ACTIVITY'  , 1);
define('FACEBOOK_ACTIVITY', '2');
define('TWITTER_ACTIVITY' , '3');
define('LINKEDIN_ACTIVITY', '4');

class actividades_chunk extends chunk_base implements chunk{
	protected $_plantillas = array('html/actividades.html',
                                   'html/actividades_item.html',
                                   'html/actividades_register.html',
                                   'html/actividades_publish.html',
                                   'html/actividades_facebook.html',
                                   'html/actividades_twitter.html',
                                   'html/actividades_linkedin.html',
                                   'html/actividades_premium.html',
                                   'html/actividades_vendido.html',
                                   'html/actividades_oferta.html'),
              $_selfpath   = 'chunks/actividades/',
              $_scripts    = array('js/actividades.js');
	function out($params=array()){
            
	    global $config,$core,$document;
            $cache=$core->getApp("cache");
            /*
            if($respuesta=$cache->loadData("actividadesFeed_".$core->getEnviromentVar("languaje"))){
                return $respuesta;
            }
             * 
             */
            $app=&$core->getApp("actividades");
            $manager=&$core->getApp("inmueblesManager");
            $actividades=$app->get(0,"desc",$max=200);
            
	    $plantilla= $this->loadPlantilla(0);
	    $lastid=false;
	    $firstid=0;
            
                $x=0;
                $items1=array();
                $items2=array();
                
                $peso1=0;
                $peso2=0;
             
                //print_r($actividades);
            foreach($actividades as $act){
                $lastid=$lastid?$lastid:$act->id;
	    	$firstid=$act->id;
                
                
                
                $peso=1;
                $fecvenpremium=0;
		$usuario=new user($act->usuario);
		   switch($act->tipo){
			case 1: //Inmueble publicado
                            $in=$manager->getInmueble($act->objeto,$act->tipoobjeto);
                            
                            
                            
                            //echo $in->id;
                            if($in&&$in->isActive()){
				$foto=$in->getImage();
                                $foto=$foto[0]->path;
                                $fecvenpremium=strtotime($in->get("fecvenpremium"));
                                
                                $fecvenoferta=strtotime($in->get("fecvenoferta"));
                                
                                
                                
                                if(intval($in->get("vendido"))){
                                    $plantilla_items=$this->loadPlantilla(8);
                                    $foto="stamp/vendido/".$foto;
                                    
                                }else if($fecvenoferta>time()) {
                                    $plantilla_items=$this->loadPlantilla(9);
                                    
                                }elseif($fecvenpremium>time()) {
                                    $plantilla_items=$this->loadPlantilla(7);
                                }
                                else {
                                    $plantilla_items=$this->loadPlantilla(3);
                                }
				$description=$in->get("descripcion");
                                $titulo=$in->get("titulo");
                                $link=$in->getURL();
                                $fecvenpremium=strtotime($in->get("fecvenpremium"));
                                
                                
                                $linktext=$in->sublinktext[$in->get("subtipo")]?$in->sublinktext[$in->get("subtipo")]:$in->linktext;
                                $transactiontext=$in->ontransactiontext[$in->get("tipovr")];
                                
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
                                $act->actividad=str_replace(array("_s.jpg","_s.png"),array("_n.jpg","_n.png"),$act->actividad.($act->fb_data->picture?"<img class='fb_picture' alt='".htmlentities($titulo)."' src='".$act->fb_data->picture."'>":""));
                            break;
                        case "3":
                                $plantilla_items=$this->loadPlantilla(5);
                                $titulo='$$twitter_activity$$';
                                $link=$act->link;
                                //$peso=80;
                            break;
                        case "4":
                                $plantilla_items=$this->loadPlantilla(6);
                                $titulo='$$linkedin_activity$$';
                                $link=$act->link;
                                $peso=500;
                                $imagen=$act->linkedin_data->xpath("update-content/company-status-update/share/content/submitted-image-url");
                                $act->actividad=$act->actividad.($imagen?"<a href='".$link."' target='_blank'><img class='fb_picture' alt='".htmlentities($titulo)."' src='".((string)$imagen[0])."'></a>":"");
                            break;
                        case 5:
                            $plantilla_items=$this->loadPlantilla(7);
                            $description=$in->get("descripcion");
                            $titulo=$in->get("titulo");
                            $link=$in->getURL();
                            $fecvenpremium=strtotime($in->get("fecvenpremium"));


                            $linktext=$in->sublinktext[$in->get("subtipo")]?$in->sublinktext[$in->get("subtipo")]:$in->linktext;
                            $transactiontext=$in->ontransactiontext[$in->get("tipovr")];

                            $peso=500;
                        break;
                        case 15:
                            $plantilla_items=$this->loadPlantilla(9);
                            $description=$in->get("descripcion");
                            $titulo=$in->get("titulo");
                            $link=$in->getURL();
                            $fecvenpremium=strtotime($in->get("fecvenpremium"));


                            $linktext=$in->sublinktext[$in->get("subtipo")]?$in->sublinktext[$in->get("subtipo")]:$in->linktext;
                            $transactiontext=$in->ontransactiontext[$in->get("tipovr")];

                            $peso=500;
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
                       "PREMIUM"=>$fecvenpremium>time()?"1":"0",
	    		"nivel"=>$act->nivel,
	    		"nombre_pant"=>$usuario->get("nombre_pant"),
	    		"avatar"=>$usuario->getAvatar(),
	    		"userlink"=>$usuario->getLink(),
	    		"titulo"=>$titulo,
	    		"FOTO"=>$foto?$foto:$pathi."sinimagen.jpg",
	    		"FECHA"=>$core->parseDate(($act->fecha),"longtime"),
	    		"DESCRIPTION"=>$description
				);   
                    if($peso2>$peso1){
                      $items1[]=$this->parse($plantilla_items, $p);
                      $peso1+=$peso;
                    }
                    else{
                      $items2[]=$this->parse($plantilla_items, $p);
                      $peso2+=$peso;
                    }
                    $x=$x?0:1;
                }
            $p=array(
                "ITEMS1"=>implode("",$items1),
                "ITEMS2"=>implode("",$items2),
			"LASTID"=>$lastid,
			"FIRSTID"=>$firstid);
            $respuesta=parent::out($plantilla,$p);
            

     //$cache->storeData("actividadesFeed_".$core->getEnviromentVar("languaje"),$respuesta,time()+300);
            
            return $respuesta;
	}
}