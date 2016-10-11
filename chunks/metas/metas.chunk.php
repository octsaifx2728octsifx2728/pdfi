<?php

class metas_chunk extends chunk_base implements chunk{
    protected $_plantillas=array("html/meta.html","html/og.html");
    protected $_selfpath="chunks/metas/";
    function out($params = array()) {
        global $core,$inmueble,$config,$user_view;
        $plantilla=  $this->loadPlantilla(0);
        $app=$_POST["app"]?$_POST["app"]:$_GET["app"];
        
        $title='$$pagetitle$$';
        $description='$$pagedescription$$';
        $creator="E-spacios.com"; 
        $identifier=$config->paths["urlbase"].strip_tags($_SERVER["REQUEST_URI"]);
        $relation=$config->paths["urlbase"];
        $date=date("Y-m-d");
        $location="19.431643;-99.185706";
        
        switch($app){
            case "inmueble":
                $query=explode("/",$_GET["task"]);
                $title=str_replace("-"," ",htmlentities($query[1],false,'UTF-8'))." [E-spacios.com] ";
                if($inmueble){
                    $description=$inmueble->get("descripcion")." [E-spacios.com] ".$description;
                    $autor=new user($inmueble->get("id_cliente"));
                    $creator=$autor->get("nombre_pant");
                    $date=date("Y-m-d",  strtotime($inmueble->get("fecha_alta")));
                    $relation=$config->paths["urlbase"].$autor->getLink();
                    $location=$inmueble->get("coordenaday").",".$inmueble->get("coordenadax");
                    $ops=
                        array(
                            array("name"=>"title","val"=>  htmlentities($inmueble->get("titulo"))),
                            array("name"=>"type","val"=>  "com_e-spacios:property"),
                            array("name"=>"place:location:latitude","val"=>  $inmueble->get("coordenaday")),
                            array("name"=>"place:location:longitude","val"=>  $inmueble->get("coordenadax")),
                            array("name"=>"site_name","val"=>  "E-spacios.com"),
                            array("name"=>"description","val"=>str_replace("\"","",$inmueble->get("descripcion"))),
                             array("name"=>"url","val"=>$inmueble->getURL())
                        );
                $images=$inmueble->getImages();
                if(!count($images)){
                    $ops[]=array("name"=>"image","val"=>"#BASE#cache/300/0/galeria/imagenes/sinimagen.jpg"); 
                }
                else {
                    foreach($images as $img){
                       $ops[]=array("name"=>"image","val"=>"#BASE#cache/300/0/".$img->path); 
                    }
                }
                
                $videos=$inmueble->getVideos();
                foreach($videos as $v){
                    $ops[]=array("name"=>"video","val"=>"http://www.youtube.com/".$v);
                }
                $og=$this->_generarOG($ops);
                }
                break;
            case "searchbounds":
                $query=explode(":",$_GET["task"]);
                $title=str_replace("-"," ",htmlentities($query[4],false,'UTF-8'));
                $description='$$propiedades_en$$ '.$title;
                break;
            case "user":
                if($user_view){
                    $ops=array();
                    $ops[]=array("name"=>"url","val"=>"#BASE#".ltrim($user_view->getLink(),"/"));
                    $ops[]=array("name"=>"description","val"=>"Buy, Rent, Share houses, Apartments and More");
                    $ops[]=array("name"=>"title","val"=>  $user_view->get("nombre_pant")." on E-spacios.com" );
                    $ops[]=array("name"=>"image","val"=>"#BASE#cache/220/0/".$user_view->getAvatar().".jpg");
                    $ops[]=array("name"=>"site_name","val"=>"E-spacios.com");
                    $og=$this->_generarOG($ops);
                    $title=$user_view->get("nombre_pant")." properties on E-spacios.com";
                    $description=$user_view->get("nombre_pant")." properties on E-spacios.com";
                }
                break;
           default:
                    $ops=array();
                    $ops[]=array("name"=>"url","val"=>"#BASE#");
                    $ops[]=array("name"=>"description","val"=>'$$pagedescription$$');
                    $ops[]=array("name"=>"type","val"=>  "article");
                    $ops[]=array("name"=>"title","val"=>  $title );
                    $ops[]=array("name"=>"image","val"=>"#BASE#cache/221/221/images/e.jpg");
                    $ops[]=array("name"=>"site_name","val"=>"E-spacios.com");
                    $og=$this->_generarOG($ops);
               break;
        }
        $valores=array(
            "OG"=>$og,
            "TITLE"=>$title,
            "DESCRIPTION"=> str_replace("\"","",substr($description,0,159)),
            "CREATOR"=>$creator,
            "IDENTIFIER"=>$identifier,
            "DATE"=>$date,
            "RELATION"=>$relation,
            "LOCATION"=>$location
        );
        return parent::out($plantilla, $valores);
    }
    private function _generarOG($params){
        $plantilla=$this->loadPlantilla(1);
        $og="";
        foreach($params as $k=>$v){
            $og.=$this->parse($plantilla, array(
                "NAME"=>$v["name"],
                "VAL"=>$v["val"]
            ));
        }
        return $og;
    }
}