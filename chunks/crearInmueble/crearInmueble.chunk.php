<?php
class crearInmueble_chunk extends chunk_base implements chunk{
    protected $_plantillas=array("html/principal.html");
    protected $_selfpath="chunks/crearInmueble/";
    protected $_styles=array("css/freemiumform.css","css/crearInmueble.css");
    protected $_scripts=array("js/crearInmueble.js");
    public function out($params = array()) {
        global $core,$user;
        $im=$core->getApp("inmueblesManager");
        
        
        
        if($user->id){
            if($_SESSION["PreInmueble"]){
                
               $id=explode("_",$_SESSION["PreInmueble"]);
               $incompleto=$im->getInmueble($id[0],$id[2]);
               if($incompleto){
                $incompleto->set("id_cliente",$user->id);
                if($anuncio=$incompleto->getAnuncio()){
                    $anuncio->set("huerfano","0");
                }
                
                }
               unset($_SESSION["PreInmueble"]);
            }
            else {
                $incompleto=$im->getIncomplete($user);
                
               
               
                
            }
        }
        else {
            
               $id=explode("_",$_SESSION["PreInmueble"]);
               $incompleto=$im->getInmueble($id[0],$id[2],$user);
               
               
               
        }
        
        $incompletos=array();
        if($incompleto){
            $incompletos[]=array(
                "id"=>$incompleto->id,
                "tipoobjeto"=>$incompleto->get("tipoobjeto"),
                "titulo"=>$incompleto->get("titulo")
            );
            
            
        }
        else{
           $prot=$im->getPrototypes();
           $incompleto=  array_shift($prot);
           $incompleto->build($user);
            $incompletos[]=array(
                "id"=>$incompleto->id,
                "tipoobjeto"=>$incompleto->get("tipoobjeto"),
                "titulo"=>$incompleto->get("titulo")
            );
        }
        $plantilla=$this->loadPlantilla(0);
        
        
         
                
        $p=array("INCOMPLETOS"=>json_encode($incompletos));
        
        
        
        return parent::out($plantilla, $p);
    }
}
