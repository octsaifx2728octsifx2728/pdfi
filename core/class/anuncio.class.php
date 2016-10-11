<?php
class anuncio extends objeto{
    public $id;
    protected $_tabla="anuncios";
    protected $_idField="id";
    function anuncio($id){
        $this->id=$id;
    }
    function set($nombre,$valor){
        global $core;
        switch($nombre){
            case "activoForced":
                    return parent::set("activo",$valor);
                break;
            case "activo":
                $inmueble=&$this->getInmueble();
                $expire=$inmueble->getVencimiento();
                if($expire<time()&&$valor==1){
                    return "paymentRequired";
                }
                else {
                    return parent::set($nombre,$valor);
                }
                break;
            default:
                return parent::set($nombre,$valor);
        }
    }
    function getInmueble(){
        global $core;
        $manager=&$core->getApp("inmueblesManager");
        $inmueble=$manager->getInmueble($this->get("idinmueble"),$this->get("tipoinmueble"));
        if(!$inmueble){
           $this->borrar();
           return null;
        }
        if(!$inmueble->get("id_expediente")){
           $this->borrar();
           return null;
        }
        return $inmueble;
    }
    function getBound(){
        global $core;
        $q="select `bound` from `bounds_user_inmueble` where `anuncio`='".intval($this->id)."' limit 1";
        $db=&$core->getDB(0,2);
        if($r=$db->query($q)){
            $i=$r->fetch_assoc();
            $core->loadClass("bound");
            return new bound($i["bound"]);
        }
    }
    
  public function get($nombre){
      switch($nombre){
          case "activo":
              return parent::get($nombre);
              break;
          case "bound":
              $bound=$this->getBound();
              return $bound->id;
              break;
          default:
              return parent::get($nombre);
      }
  }
  public function borrar(){
      return parent::borrar();
  }
}