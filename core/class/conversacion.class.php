<?php

class conversacion extends objeto{
    public $id;
    public $_tabla="conversaciones";
    public function conversacion($id){
        $this->id=$id;
    }
    public function getInmueble(){
        global $core;
        $core->loadClass("anuncio");
        $anuncio=new anuncio($this->get("inmueble"));
        return $anuncio->getInmueble();
    }
    public function getSender(){
        global $core;
        $core->loadClass("user");
        $usuario=new user($this->get("user1"));
        return $usuario;
    }
    public function getReceiver(){
        global $core;
        $core->loadClass("user");
        $usuario=new user($this->get("user2"));
        return $usuario;
    }
    public function getPartner(user $usuario){
        
        if($usuario->id==$this->get("user2")){
            return $this->getSender();
        }
        else{
            return $this->getReceiver();
        }
            
    }
    public function getLast(){
        global $core;
        $db=$core->getDB(0,2);
        $q="select `m`.`id` as 'id'
            from `mensajes` as `m` 
                left join `buzon` as `b` on `m`.`id`=`b`.`id_mensaje` 
            where ((`b`.`id_cliente`='".$this->get("user2")."' 
                    and `m`.`id_cliente`='".$this->get("user1")."') or 
                  (`b`.`id_cliente`='".$this->get("user1")."' 
                    and `m`.`id_cliente`='".$this->get("user2")."'))
                        and `b`.`anuncio`='".$this->get("inmueble")."'
            order by `b`.`fecha` DESC limit 1";
        if($r=$db->query($q)){
            $core->loadClass("mensaje");
            if($i=$r->fetch_Assoc()){
                return new mensaje($i["id"]);
            }
        }
    }
    public function getMensajes(user $owner){
        
        global $core;
        $db=$core->getDB(0,2);
        $q="select `m`.`id` as 'id'
            from `mensajes` as `m` 
                left join `buzon` as `b` on `m`.`id`=`b`.`id_mensaje` 
            where ((`b`.`id_cliente`='".$this->get("user2")."' 
                    and `m`.`id_cliente`='".$this->get("user1")."') or 
                  (`b`.`id_cliente`='".$this->get("user1")."' 
                    and `m`.`id_cliente`='".$this->get("user2")."'))
                        and `b`.`anuncio`='".$this->get("inmueble")."'
            order by `b`.`fecha` ASC";
        
        $mens=array();
        if($r=$db->query($q)){
            $core->loadClass("mensaje");
            while($i=$r->fetch_Assoc()){
                $m= new mensaje($i["id"]);
                $m->owner=($m->get("id_cliente")==$owner->id);
                $mens[]=$m;
            }
        }
        if($owner->id==$this->get("user1")){
            $this->set("user1read","1");
        }
        else {
            $this->set("user2read","1");
        }
        return $mens;
    }
}