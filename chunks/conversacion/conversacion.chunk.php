<?php

class conversacion_chunk extends chunk_base implements chunk{
    protected $_selfpath="chunks/conversacion/";
    protected $_plantillas=array(
        "html/container.html",
        "html/mensaje1.html"
    );
    public function conversacion_chunk($params=array()){
        $this->_params=(array)$params;
    }

    public function out($params=array()) {
        global $core,$user;
        $plantilla=$this->loadPlantilla(0);
        $pm1=$this->loadPlantilla(1);
        $pm2=$this->loadPlantilla(1);
        $core->loadClass("conversacion");
        $conversacion=new conversacion($this->_params["id"]);
        $user2=$conversacion->getPartner($user);
        $mensajes="";
        $mens=$conversacion->getMensajes($user);
        $lex=$core->getLexicon();
        $formato=$lex->traduce('$$phpdateformat_short$$');
        if($conversacion->get("user1")==$user->id){
            $conversacion->set("user1read",1);
        }
        else {
            $conversacion->set("user2read",1);
            }
        foreach($mens as $m){
            $p=array();
            
            if($m->owner){
                $pm=$pm1;
                $us=$user;
                $m->set("leido",1);
                $mo="1";
            }
            else {
                $pm=$pm2;
                $m->set("leido",1);
                $us=$user2;
                $mo="0";
            }
            $p["AVATAR"]=$us->getAvatar();
            $p["NAME"]=$us->get("nombre_pant");
            $p["ULINK"]=$us->getLink();
            $p["TEXTO"]=nl2br(htmlentities($m->get("mensaje"),NULL,"UTF-8"));
            $p["ID"]=$m->id;
            $p["OWNER"]=$mo;
            $fecha=$m->get("fecha");
            $fecha=$fecha?$core->parseDate(strtotime($fecha),"longtime"):"";
            $p["FECHA"]=$fecha;
            
            
            $mensajes.=$this->parse($pm, $p);
        }
        $inm=$conversacion->getInmueble();
        $p=array(
            "RESPONSEID"=>$inm?$inm->getID():"0_".($user2->id)."_0",
            "MENSAJES"=>$mensajes
        );
        return parent::out($plantilla, $p);
    }
}
