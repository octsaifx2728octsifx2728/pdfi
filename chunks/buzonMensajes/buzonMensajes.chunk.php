<?php
class buzonMensajes_chunk extends chunk_base implements chunk{
    
    protected $_selfpath="chunks/buzonMensajes/";
    protected $_styles=array("css/contact.css");
    protected $_scripts=array("js/contact.js","js/conversacion.js");
    protected $_plantillas=array(
        'html/buzonMensajes.html',
        'html/buzonMensajes_item.html',
        'html/conversaciones.html',
        'html/conversacion.html',
        'html/conversacion2.html'
    );  
	function out($params=array()){
		global $user_view,$user,$core,$config,$document;
		if($user_view->id==$user->id){
			$plantilla= $this->loadPlantilla(0);
			$plantilla_item= $this->loadPlantilla(1);
                        $pconvs=$this->loadPlantilla(2);
                        $pconvs2=$this->loadPlantilla(3);
                        $pconvs22=$this->loadPlantilla(4);
			$app=$core->getApp("mensajes");
			$conversaciones=$app->getHeadersForUser($user);
                        $pconversaciones="";
                        $renderizados=array();
                        foreach ($conversaciones as $c){
                            $inm=$c->getInmueble();
                            $us=$c->getPartner($user);
                            
                            $k=$us->id."_".($inm?$inm->getID():"0");
                            if($renderizados[$k]){
                                continue;
                            }
                            else {
                                $renderizados[$k]=true;
                            }
                            $pl=$pconvs2;
                            
                            $p=array("MENSAJE"=>"","FECHA"=>"");
                            if($inm){
                                $pl=$pconvs22;
                                $img=$inm->getImage();
                                
                                $p["INMUEBLEFOTO"]=$img[0]->path?$img[0]->path:"galeria/imagenes/sinimagen.jpg";
                                $p["INMUEBLETITLE"]=$inm->get("titulo");
                                $p["INMUEBLELINK"]=$inm->getURL();
                            }
                            if($us){
                                $p["SENDERAVATAR"]=$us->getAvatar();
                                $p["SENDERNAME"]=$us->get("nombre_pant");
                                $p["SENDERLINK"]=$us->getLink();
                            }
                            $p["ID"]=$c->id;
                            $last=$c->getLast();
                            if($last){
                                $lex=$core->getLexicon();
                                $p["MENSAJE"]=nl2br(htmlentities($last->get("mensaje"),NULL,"UTF-8"));
                                $fecha=$last->get("fecha");
                                //$fecha=$fecha?date($lex->traduce('$$phpdateformat_short$$'),strtotime($fecha)):"";
                                $p["FECHA"]=$core->parseDate(strtotime($fecha),"longtime");
                            }
                            if($c->get("user2")==$user->id){
                                $p["VISTO"]=$c->get("user2read")?"1":"0";
                            }
                            
                           $pconversaciones.=$this->parse($pl, $p); 
                        }
                        
                        $p=array(
                            "CONVERSACIONES"=>$pconversaciones
                        );
                        
                        return parent::out($pconvs,$p);
                        
			$mensajes=$app->retrieve($user);
                        
			$mens="";
			foreach($mensajes as $m){
				$u=new user($m->get("id_cliente"));
				$p=array(
					"ID"=>$m->get("id"),
					"DID"=>$u->id,
					"DAVATAR"=>$u->getAvatar(),
					"DNOMBRE"=>$u->get("nombre_pant"),
					"TITULO"=>$m->get("asunto"),
					"NUEVO"=>$m->get("leido")?"":"nuevo",
					"MENSAJE"=>htmlentities($m->get("mensaje"),false,"utf-8"),
					"FECHA"=>date("H:i d/m/Y",strtotime($m->get("fecha")))
					);
				$mens.=$this->parse($plantilla_item, $p);
			}
			$p=array(
			"MENSAJES"=>$mens
			);
		return parent::out($plantilla, $p);
		}
	}
}
