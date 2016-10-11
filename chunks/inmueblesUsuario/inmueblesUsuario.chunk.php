<?php
class inmueblesUsuario_chunk extends chunk_base implements chunk{
    protected $_params          = array(),
              $_scripts         = array("js/metricaconverter.js",
                                        "js/freemium.js",
                                        "js/inmuebleManager.js"),
              $_styles          = array(),
              $_selfpath        = "chunks/inmueblesUsuario/",
              $_plantillasAdmin = array('html/inmueblesUsuario.html',
                                        'html/inmueblesUsuario_item.html',
                                        'html/item.html',
                                        'html/residencialAdmin.html',
                                        'html/comercialAdmin.html',
                                        'html/oficinaAdmin.html',
                                        'html/industrialAdmin.html',
                                        'html/terrenosAdmin.html',
                                        'html/funerariosAdmin.html'),
              $_plantillas      = array('html/inmueblesUsuario2.html',
                                        'html/inmueblesUsuario_item2.html',
                                        'html/item2.html',
                                        'html/inmueblesUsuario_item2.html',
                                        'html/inmueblesUsuario_item2.html',
                                        'html/inmueblesUsuario_item2.html',
                                        'html/inmueblesUsuario_item2.html',
                                        'html/inmueblesUsuario_item2_terreno.html',
                                        'html/inmueblesUsuario_item2_funerarios.html');

    private $_vrtitles   = array("", '$$sale$$', '$$rent$$'        , '$$share$$'),
            $_tipotitles = array("", '$$casa$$', '$$departamento$$', '$$cuarto$$');

    function inmueblesUsuario_chunk($params = array()){
        $this->_params = $params;
    }

    function out($params=array()){
        global $user , $core, $config, $document, $inmueble, $user_view;
        
         
        

        if($user_view->id == $user->id){
            $this->_adminMode = true;

        }else{
            $this->_scripts = array();
        }

        $plantilla           = $this->loadPlantilla(0);
        $plantilla_item_item = $this->loadPlantilla(2);

        $search           =& $core->getApp("search");
        $inmuebles        =  $search->searchByUser($user_view,($user_view->id==$user->id));
        $inms             =  array();
        $inmuebles_parsed =  "";
        $x                = 1;
        $mc               = $core->getApp("metricaconverter");

        foreach($inmuebles as $inmueble){
            
            
            
            $plantilla_item = $this->loadPlantilla($inmueble->get("tipoobjeto") + 2);
            $anuncio        = $inmueble->getAnuncio();
            $share          = $core->getChunk("share");
            $contact        = $core->getChunk("contact");

            if(count($imagen = $inmueble->getImage())){
                $imgpath = $imagen[0]->path;
                if(!$imagen[0]->path or !file_exists($imgpath)){
                    $imgpath = "galeria/imagenes/sinimagen.jpg";
                }

            }else {
                $imgpath = "galeria/imagenes/sinimagen.jpg";
            }

            $fecven = strtotime($inmueble->get("fecvennormal"));

            if($fecven > time()){
                $fecven  = date("d/m/Y",$fecven);
                $vencido = "";

            }else {
                $fecven  = '$$vencido$$: '.date("d/m/Y",$fecven);
                $vencido = "vencido";
            }

            $fecvenpremium = strtotime($inmueble->get("fecvenpremium"));
            $fecvenoferta = strtotime($inmueble->get("fecvenoferta"));

            if($fecvenpremium > time()){
                $fecvenpremium  = date("d/m/Y",$fecvenpremium);
                $vencidopremium = "";

            }else{
                $fecvenpremium  = '$$vencido$$';
                $vencidopremium = "vencidopremium";
            }

            if($fecvenoferta > time()){
                $fecvenoferta  = date("d/m/Y",$fecvenoferta);
                $vencidooferta = "";

            }else{
                $fecvenoferta  = '$$vencido$$';
                $vencidooferta = "vencidooferta";
            }

            if($fecvenpremium > time() && $fecvenoferta > time() ){
                if( $fecvenoferta > $fecvenpremium ){
                    $fecvenoferta  = date("d/m/Y",$fecvenoferta);
                    $vencidooferta = "";
                    $fecvenpremium = "";
                }else{
                    $fecvenpremium  = date("d/m/Y",$fecvenpremium);
                    $vencidopremium = "";
                    $fecvenoferta = "";
                }
            }

            $esoferta = (strtotime($inmueble->get("fecvenoferta")) > time()) and (strtotime($inmueble->get("fecvennormal")) > time()) ? "1" : "0";
            $espremium = (strtotime($inmueble->get("fecvenpremium")) > time()) and (strtotime($inmueble->get("fecvennormal")) > time()) ? "1" : "0";
            // resuelve 2 tipos de productos en una sola propiedad (muestra la más nueva)
            
            
          
            
            
            
            if( $espremium == "1" && $esoferta == "1" ){
                if( strtotime($inmueble->get("fecvenoferta")) > strtotime($inmueble->get("fecvenpremium")) ){
                    $esoferta = "1";
                    $espremium = "0";
                    $fecvenpremium = "";
                }else{
                    $espremium = "1";
                    $esoferta = "0";
                    $fecvenoferta = "";
                }
            }
            
            
            
           
            

            $videospermitidos = $inmueble->getAllowedVideos();
            $items            = $inmueble->getItemsGeneral();
            $itemsp           = "";

            
            foreach($items as $k=>$v){
                $p       = array("K" => $k,
                                 "V" => ($v->valor ? "1" : "0"),
                                 "N" => $v->nombre);
                $itemsp .= $this->parse($plantilla_item_item, $p);
            }

            $fields    = $inmueble->getNewForm();
            $adicional = "";

            foreach($fields as $seccion){
                switch($seccion["name"]){
                    case "transaccion":
                        $vr     = "<select onchange= \"this.parentNode.className='tipovr tipovr_'+this.value;inmueble_manager.update('".$inmueble->id."','tipovr',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                        $extras = "";

                        foreach($seccion["fields"][0]["options"] as $sf){
                            if(($inmueble->get("tipovr") == "tipovrcomparto") and ($sf["name"] == "tipovrcomparto")){
                                $vr .= "<option value='".$sf["name"]."' ".($inmueble->get("tipovr") == $sf["name"] ? " selected='selected'" : "").">".$sf["label"]."</option>";

                                foreach($sf["options"] as $sfo){
                                    $extras .= "<div><input ".($inmueble->get($sfo["name"]) ? "checked='checked'" : "")." type='checkbox' name='".$sfo["name"]."'  onchange= \"inmueble_manager.update('".$inmueble->id."','".$sfo["name"]."',(this.checked?1:0),'".$inmueble->get("tipoobjeto")."')\">".$sfo["label"]."</div>";
                                }

                            }elseif($inmueble->get("tipovr") != "tipovrcomparto" and $sf["name"] != "tipovrcomparto"){
                                $vr .= "<option value='".$sf["name"]."' ".($inmueble->get("tipovr")==$sf["name"]?" selected='selected'":"").">".$sf["label"]."</option>";

                                foreach($sf["options"] as $sfo){
                                    $extras .= "<div><input ".($inmueble->get($sfo["name"])?"checked='checked'":"")." type='checkbox' name='".$sfo["name"]."'  id='atributo_".$inmueble->id."_". $sfo["label"] ."' onchange= \"inmueble_manager.update('".$inmueble->id."','".$sfo["name"]."',(this.checked?1:0),'".$inmueble->get("tipoobjeto")."')\"><label for='atributo_".$inmueble->id."_". $sfo["label"] ."'>".$sfo["label"]."</label></div>";
                                    //$extras .= "<div><input ".($inmueble->get($sfo["name"])?"checked='checked'":"")." type='checkbox' name='".$sfo["name"]."' onchange= \"inmueble_manager.update('".$inmueble->id."','".$sfo["name"]."',(this.checked?1:0),'".$inmueble->get("tipoobjeto")."')\">".$sfo["label"]."</div>";
                                }
                            }
                        }

                        $vr .= "</select><div class='extras'>".$extras."</div>";
                        break;

                    case "adicional":
                        foreach($seccion["fields"] as $f){
                            $fi = "";
                            switch($f["type"]){
                                case "number":
                                    switch($f["name"]){
                                        case "m2s":
                                            $fi = "<input type='text' value='".floatval($inmueble->get($user_view->id==$user->id?"m2s-raw":"m2s"))."'  onchange=\"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                                            //$fi = "<input type='number' value='".floatval($inmueble->get($user_view->id==$user->id?"m2s-raw":"m2s"))."'  onchange=\"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                                            break;
                                        case "m2":
                                            $fi = "<input type='text' value='".floatval($inmueble->get($user_view->id==$user->id?"m2-raw":"m2"))."'  onchange=\"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                                            //$fi = "<input type='number' value='".floatval($inmueble->get($user_view->id==$user->id?"m2-raw":"m2"))."'  onchange=\"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                                            break;
                                        default:
                                            $fi = "<input type='number' value='".intval($inmueble->get($f["name"]))."'  onchange=\"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                                    }
                                    break;
                                case "select":
                                    $fi = "<select onchange=\"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',this.value,'".$inmueble->get("tipoobjeto")."')\">";
                                    foreach($f["options"] as $k=>$o){
                                        $fi .= "<option value='".$k."' ".($k == $inmueble->get($f["name"]) ? "selected='selected'" : "").">".$o."</option>";
                                    }
                                    $fi .= "</select>";
                                    break;
                               case "checkbox":
                                    $fi = "<input ".($inmueble->get($f["name"])?"checked='checked'":"")." type='checkbox' name='".$sfo["name"]."'  onchange= \"inmueble_manager.update('".$inmueble->id."','".$f["name"]."',(this.checked?1:0),'".$inmueble->get("tipoobjeto")."')\">".$f["label"];
                                   break;
                            }
                            $adicional .= "<div class='".$f["name"]." dataS ".$f["params"]."' title='".$f["label"]."'><div class='icon'></div>".$fi."</div>";
                        }
                        break;
                    default:
                        break;
                }
            }
            
            
           
            

            $p = array("ACTIVO"              => $inmueble->isActive() ? 1 : 0,
                       "TIPOOBJETO"          => $inmueble->get("tipoobjeto"),
                       "TIPONOMBRE"          => $inmueble->nombreS,
                       "BOUND"               => 2,
                       "ADICIONAL"           => $adicional,
                       "ITEMS"               => $itemsp,
                       "DUPLEX"              => $x,
                       "FECVEN"              => $fecven,
                       "VENCIDO"             => $vencido,
                       "PREMIUM"             => $espremium,
                       //"PREMIUM"             => (strtotime($inmueble->get("fecvenpremium")) > time()) and (strtotime($inmueble->get("fecvennormal")) > time()) ? "1" : "0",
                       "VENDIDO"             => (intval($inmueble   ->get("vendido")))                                                                         ? "1" : "0",
                       "OFERTA"              => $esoferta,
                       //"OFERTA"             => (strtotime($inmueble->get("fecvenoferta")) > time()) and (strtotime($inmueble->get("fecvennormal")) > time()) ? "1" : "0",
                       "FECVENPremium"       => $fecvenpremium,
                       "VENCIDOPremium"      => $vencidopremium,
                       "FECVENOferta"       => $fecvenoferta,
                       "VENCIDOOferta"      => $vencidooferta,
                       "TIPOVR"              => $inmueble->get("tipovr"),
                       "VR"                  => $vr,
                       "METRICA"             => $core->getEnviromentVar("metrica"),
                       "METRICA2"            => $inmueble->get("metrica"),
                       "VRTITLE"             => $this->_vrtitles[$anuncio ? $anuncio->get("tipotransaccion") : 0],
                       "ID"                  => $inmueble->id,
                       "id"                  => $inmueble->getID(),
                       "USER"                => $inmueble->get("id_cliente"),
                       "IMAGEN"              => $imgpath,
                       "SUBTIPO"             => $inmueble->get("subtipo"),
                       "SUBTIPONOMBRE"       => $inmueble->get("subtiponombre"),
                       "CASA"                => $inmueble->get("tipoobjeto"),
                       "CASA_TITLE"          => $this->_tipotitles[$inmueble->get("casa")],
                       "AMUEBLADO"           => $inmueble->get("amueblado"),
                       "NOAMUEBLADO"         => $inmueble->get("noamueblado"),
                       "NOAMUEBLADO_TITLE"   => $inmueble->get("noamueblado") == 1 ? '$$noamueblado$$'    : '$$noamueblado$$',
                       "AMUEBLADO_TITLE"     => $inmueble->get("amueblado")   == 1 ? '$$amueblado$$'      : '$$amueblado$$',
                       "NOAMUEBLADO_CHECKED" => $inmueble->get("noamueblado") == 1 ? ' checked="checked"' : '',
                       "AMUEBLADO_CHECKED"   => $inmueble->get("amueblado")   == 1 ? ' checked="checked"' : '',
                       "TITLE"               => $inmueble->get("titulo"),
                       "DESCRIPCION"         => ($inmueble->get("descripcion")),
                       "LAT"                 => $inmueble->get("coordenaday"),
                       "LON"                 => $inmueble->get("coordenadax"),
                       "URL"                 => $inmueble->getURL(),
                       "MONEDAORIG"          => $inmueble->get("precio_moneda"),
                       "DIRECCION"           => htmlentities($inmueble->get("direccion"), null, "UTF-8"),
                       "PRECIOORIG"          => intval($inmueble->get("precio")),
                       "PRECIOM2ORIG"        => number_format($inmueble->getPreciom2($inmueble->get("precio_moneda"), $inmueble->get("metrica")), 0),
                       "MONEDA"              => $core->getEnviromentVar("currency"),
                       "PRECIO"              => number_format($inmueble->getPrecio($core->getEnviromentVar("currency"))  , 0),
                       "PRECIOM2"            => number_format($inmueble->getPreciom2($core->getEnviromentVar("currency")), 0),
                       "preciom2metros"      => number_format($inmueble->getPreciom2($core->getEnviromentVar("currency") , "metros"), 0),
                       "preciom2pies"        => number_format($inmueble->getPreciom2($core->getEnviromentVar("currency") , "pies")  , 0),
                       "HABITACIONES"        => intval($inmueble->get("habitaciones")),
                       "M2"                  => number_format($inmueble->get("m2"),0),
                       "m2-raw"              => ($inmueble->get("m2-raw")),
                       "m2-metros"           => number_format($mc->convert($inmueble->get("m2-raw"),$inmueble->get("metrica"),"metros"),0),
                       "m2s-pies"            => number_format($mc->convert($inmueble->get("m2s-raw"),$inmueble->get("metrica"),"pies"),0  ),
                       "m2s-metros"          => number_format($mc->convert($inmueble->get("m2s-raw"),$inmueble->get("metrica"),"metros"),0),
                       "m2-pies"             => number_format($mc->convert($inmueble->get("m2-raw"),$inmueble->get("metrica"),"pies"),0  ),
                       "SUPERFICIE"          => number_format($inmueble->get("m2s"),0),
                       "BANOS"               => intval($inmueble->get("banos")),
                       "ESTACIONAMIENTOS"    => intval($inmueble->get("estacionamientos")),
                       "ESTACIONAMIENTOS2"   => intval($inmueble->get("estacionamientos2")),
                       "CONSTRUCCION"        => intval($inmueble->get("anio")),
                       "PROHIBIDO1"          => $videospermitidos>1?"":"prohibido",
                       "PROHIBIDO2"          => $videospermitidos>2?"":"prohibido",
                       "PROHIBIDO3"          => $videospermitidos>3?"":"prohibido",
                       "PROHIBIDO4"          => $videospermitidos>4?"":"prohibido",
                       "PROHIBIDO5"          => $videospermitidos>5?"":"prohibido",
                       "SHARE"               => $share->out(array("inmueble" => $inmueble)),
                       "CONTACT"             => $contact->out(array("id" => $inmueble->getID(),"cliente"=>$inmueble->get("id_cliente"),"title"=>$inmueble->get("titulo"))));

            $inmuebles_parsed  .= $this->parse($plantilla_item,$p);
            $inms[]             = $p;
            $x                  = $x==1?2:1;
        }




        $p=array(
            "INMUEBLES"=>$inmuebles_parsed,
                        "RESULTADOS"=>  json_encode($inms)
            );

        if($user_view->id==$user->id){
            $freemium= $user_view->getFreemiumAvaliable();
            $bound= $user_view->getBoundAvaliable();
            $facturacion=$core->getApp("facturacion");

            $pagos=$core->getChunk("formasdepago");
            $productos=$this->renderProducts($facturacion,$freemium,$bound);
            $p["PRODUCTOS"]=$productos;
            $p["FormasDePago"]=$pagos->out();
        }
        return parent::out($plantilla,$p);
    }

    function renderProducts(facturacion_app $facturacion, $freemium, $bound){
        global $config;
        $plantilla=file_get_contents($config->paths["chunks/html"].'freemium_products_product_renovar.html');
        $products=file_get_contents($config->paths["chunks/html"].'freemium_products_renovar.html');
        $checked=false;
        $checked2=false;
        if($freemium>0){
            $frem=file_get_contents($config->paths["chunks/html"].'freemium_products_freemium_renovar.html');
            $fr=$facturacion->getProduct(1);
            $p=array(
                "#ID#"=>$fr["id"],
                "#CLASS#"=>"freemium",
                "#TITLE#"=>$fr["nombre"],
                "#DESCRIPCION#"=>$fr["descripcion"],
                "#PRECIO#"=>"",
                "#TIEMPO#"=>"",
                "#GRUPO#"=>"tiempo",
                "#SELECTED#"=>"checked='checked'",
                "#TYPE#"=>"radio"
                );
            $checked=true;
            $frem=str_replace("#products#",str_replace(array_keys($p),$p,$plantilla),$frem);
        }
        if($bound>0){
            $standar=file_get_contents($config->paths["chunks/html"].'freemium_products_bound.html');
            $st="";
            $productos=$facturacion->getProductsByBound(0);
            foreach($productos as $fr){
                if($fr["tipo"]==1){
                    $p=array(
                        "#ID#"=>$fr["id"],
                        "#CLASS#"=>"standar",
                        "#TITLE#"=>$fr["nombre"],
                        "#DESCRIPCION#"=>$fr["descripcion"],
                        "#PRECIO#"=>"",
                        "#TIEMPO#"=>$fr["dias"].' $$dias$$',
                    "#GRUPO#"=>"tiempo",
                    "#SELECTED#"=>$checked?"":"checked='checked'",
                    "#TYPE#"=>"radio"
                        );
                    $checked=true;
                    $st.=str_replace(array_keys($p),$p,$plantilla);
                    }
                }
            $standar=str_replace("#products#",$st,$standar);
        }
        elseif($freemium<1) {
            $standar=file_get_contents($config->paths["chunks/html"].'freemium_products_standar_renovar.html');
            $productos=$facturacion->getProductsByBound(2);
            $st="";
            foreach($productos as $fr){
                $p=array(
                    "#ID#"=>$fr["id"]."_renovar",
                    "#CLASS#"=>"standar",
                    "#TITLE#"=>$fr["nombre"],
                    "#DESCRIPCION#"=>$fr["descripcion"],
                    "#PRECIO#"=>"USD$ ".$fr["precio"],
                    "#TIEMPO#"=> $fr["dias"].' $$dias$$',
                "#GRUPO#"=>"tiempo",
                "#SELECTED#"=>$checked?"":"checked='checked'",
                "#TYPE#"=>"radio"
                    );
                $checked=true;
                $st.=str_replace(array_keys($p),$p,$plantilla);
            }
            $standar=str_replace("#products#",$st,$standar);
        }
        if($freemium<1){
            $premium_plant=file_get_contents($config->paths["chunks/html"].'freemium_products_premium_renovar.html');
            $premiums=array(5,7,8);
            $premium="";
            foreach($premiums as $pr){
                $fr=$facturacion->getProduct($pr);
                $p=array(
                    #ID#"=>$fr["id"]."_renovar",
                    "#CLASS#"=>"standar",
                    "#TITLE#"=>$fr["nombre"],
                    "#DESCRIPCION#"=>$fr["descripcion"],
                    "#PRECIO#"=>"USD$ ".$fr["precio"],
                    "#TIEMPO#"=> $fr["dias"].' $$dias$$',
                    "#GRUPO#"=>"premium",
                    "#SELECTED#"=>"",
                    "#TYPE#"=>"checkbox"
                );
                $producto=str_replace(array_keys($p),$p,$plantilla);
                $p=array(
                    "#products#"=>$producto,
                    "#ID#"=>$pr
                    );
                $premium.=str_replace(array_keys($p),$p,$premium_plant);
            }
            /*
            //oferta
            $oferta_plant=file_get_contents($config->paths["chunks/html"].'freemium_products_oferta_renovar.html');
            $ofertas=array(15,16,17);
            $oferta="";
            foreach($ofertas as $pr){
                $fr=$facturacion->getProduct($pr);
                $p=array(
                    "#ID#"=>$fr["id"]."_renovar",
                    "#CLASS#"=>"standar",
                    "#TITLE#"=>$fr["nombre"],
                    "#DESCRIPCION#"=>$fr["descripcion"],
                    "#PRECIO#"=>"USD$ ".$fr["precio"],
                    "#TIEMPO#"=> $fr["dias"].' $$dias$$',
                    "#GRUPO#"=>"oferta",
                    "#SELECTED#"=>"",
                    "#TYPE#"=>"checkbox"
                );
                $producto=str_replace(array_keys($p),$p,$plantilla);
                $p=array(
                    "#products#"=>$producto,
                    "#ID#"=>$pr
                );
                $oferta.=str_replace(array_keys($p),$p,$oferta_plant);
            }
            */
        }
        $p=array(
            "#freemium#"=>$frem,
            "#standar#"=>$standar,
            "#premium#"=>$premium,
            "#oferta#"=>$oferta,
        );
        return str_replace(array_keys($p),$p,$products);
    }
}
