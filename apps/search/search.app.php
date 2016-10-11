<?php
class search_app{
	var $searchCache=array();
        function getTop($field="precio",$limit=1,$inverso=false){
            global $core;
            $db=&$core->getDB();
            $con=&$db->getConexion();
            
            
            switch ($field){
                case "precio":
                    
                    
                    $order="(`i`.`".  mysql_real_escape_string($field)."`*`cc`.`valor`)";
                    break;
                default:
                    $order=mysql_real_escape_string($field);
            }
            $q="select distinct `a`.`tipoinmueble` as 'tipoinmueble',
                `a`.`idinmueble` as 'idinmueble'  
               from `anuncios` as `a`
             where `activo`='1'
             and `fecvennormal`>now()
             order by `a`.`fecvenpremium` DESC, ".  mysql_real_escape_string($order)." "."
             limit 0,".intval($limit)."";
            $r=mysql_query($q);
            $inms=array();
            while($i= mysql_fetch_array($r,MYSQL_ASSOC)){
                $manager=$core->getApp("inmueblesManager");
                $i=$manager->getInmueble($i["idinmueble"],$i["tipoinmueble"]);
                $inms[]=$i;
            }
            return $inms;
        }
	function searchByUser(user $cliente,$all=false){
    	global $core,$config, $document,$searchResults;
    	$manager=&$core->getApp("inmueblesManager");
        $q="select `id` from `anuncios` as `a` left join `bounds_user_inmueble` as `bui` on `bui`.`anuncio`=`a`.`id` where `a`.`incompleto`<1 and `a`.`user`='".intval($cliente->id)."'".($all?"":" 
            and `a`.`fecvennormal`>=now() and `a`.`activo`='1'")."  order by `a`.`vendido`, `a`.`fecvenpremium` desc, `a`.`fecvennormal` desc,`bui`.`bound`";
        
        
        
        //echo $q;
        if(!$this->searchCache[md5($q)]){
            $db=&$core->getDB(0,2);
            $core->loadClass("anuncio");
           $results=array();
           if($r=$db->query($q)){
               while($i= $r->fetch_assoc()){
                   $anuncio=new anuncio($i["id"]);
                  if($inmueble=$anuncio->getInmueble()){
                      $results[]=$inmueble;
                  } 
               }
           }
           $this->searchCache[md5($q)]=$results;
        }
    	return $this->searchCache[md5($q)];
	}
  function search($query,$index=0,$max=25,$api=false){
    global $core,$config, $document,$searchResults;
    include_once $config->paths["core/class"].'inmueble.class.php';
    
    $db=&$core->getDB();
    $con=&$db->getConexion();
    $filters=$this->parseFilters();
    $q="select distinct `i`.`id_expediente` as 'id_expediente',
    	 `i`.`id_cliente` as 'id_cliente' 
    	from `inmuebles` as `i`,
    		`bounds_user_inmueble` as `bui`, 
    		`bounds_stock` as `bs`
      where 
      	`bui`.`inmueble` = `i`.`id_expediente`
      	and `bui`.`user` = `i`.`id_cliente`
      	and `bui`.`user` = `bs`.`user`
      	and `bui`.`bound` = `bs`.`bound`
      	and `bs`.`expire`>=now()
      	and	(
      		`i`.`titulo`LIKE'%".mysql_real_escape_string($query)."%'
			or `i`.`descripcion`LIKE'%".mysql_real_escape_string($query)."%'
			)"
		.(count($filters)?" and (".implode(" and ",$filters).")":"").
		"and `i`.`fecvennormal`>=now()
	order by  `i`.`fecha_alta` DESC "
	."limit ".(intval($index)?intval($index):"0").",".(intval($max)?intval($max):"25");
    $r=mysql_query($q);
    $results=array();
    if($r&& mysql_num_rows($r)){
      while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
	$results[]=new inmueble($i["id_expediente"],$i["id_cliente"]);
      }
    }
    $searchResults=$results;
    if(!$api){
      $document=$core->getDocument("search.html");
      $document->addVariable("#QUERY#",htmlentities($query,null,"utf-8"));
      $document->addVariable("#SEARCHTYPE#","string");
      $document->addVariable("#NE#",implode(",",$ne));
      $document->addVariable("#SW#",implode(",",$sw));
      $document->addStyle("/css/search.css");
      $document->addScript("/js/searcher.js");
      
      
      }
    return $results;
  }
  function parseFilters(){
  	global $core;
        //print_r($_SESSION);
        $currencyconverter=&$core->getApp("currencyconverter");
        $manager=&$core->getApp("inmueblesManager");
        $db=&$core->getDB(0,2);
    $query=array();
    $filters=$core->getFilterValues();
    $prototypes=$manager->getPrototypes();
    $proto=$prototypes[$core->getFilter("tipoobjeto")];
    if($proto){
        $fields=$proto->getNewForm();
        }
    else {
        $fields=$prototypes["residencial"]->getNewForm();
    }
    //print_r($fields[2]["fields"][1]["ranges"][$core->getFilter("tipovr")]);
    foreach($filters as $k=>$v){
        if($v){
            switch($k){
                case "tipovr":
		     $v=$v?$v:"tipovrventa";
                     $query[]="`a`.`tipotransaccion`='".  $db->real_escape_string($v)."'";
                    break;
                case "estacionamientos1":
                    $estacionamiento=explode("-",$v);
                    if(intval($estacionamiento[0]))$query[]="`i`.`estacionamientos`>='".intval($estacionamiento[0])."' ";
                    if(intval($estacionamiento[1]))$query[]="`i`.`estacionamientos`<='".intval($estacionamiento[1])."' ";
                     break;
                case "estacionamientos21":
                    $estacionamiento=explode("-",$v);
                    if(intval($estacionamiento[0]))$query[]="`i`.`estacionamientos2`>='".intval($estacionamiento[0])."' ";
                    if(intval($estacionamiento[1]))$query[]="`i`.`estacionamientos2`<='".intval($estacionamiento[1])."' ";
                     break;
                case "anio1":
                    $anyo=explode("-",$v);
                    
                    break;
                case "m2s1":
                    $rango=$fields[3]["fields"][2]["ranges"][$core->getFilter("tipovr")];
                    
                    $superficie=explode("-",$v);
                    //print_r(intval($superficie[1])&&$superficie[1]<$rango[1]);
                    if(intval($superficie[0]))$query[]="`i`.`m2s`>='".intval($superficie[0])."' ";
                    if(intval($superficie[1])&&$superficie[1]<$rango[1])$query[]="`i`.`m2s`<='".intval($superficie[1])."' ";
                    break;
                case "preciom21":
                    $precio_m2=explode("-",$v);
                    $precio_m2[0]=$currencyconverter->directConvert($precio_m2[0],$core->getEnviromentVar("currency"));
                    $precio_m2[1]=$currencyconverter->directConvert($precio_m2[1],$core->getEnviromentVar("currency"));
                  //  if(intval($precio_m2[0]))$query[]="`i`.`preciom2`>='".$precio_m2[0]."' ";
                  //  if(intval($precio_m2[1]))$query[]="`i`.`preciom2`<='".$precio_m2[1]."' ";
                     break;
                case "m21":
                    
                    $m2=explode("-",$v);
                    $rango=$fields[3]["fields"][1]["ranges"][$core->getFilter("tipovr")];
                    
                    if(intval($m2[0]))$query[]="`i`.`m2`>='".intval($m2[0])."' ";
                    if(intval($m2[1])&&$m2[1]<$rango[1])$query[]="`i`.`m2`<='".intval($m2[1])."' ";
                    break;
                case "banos1":
                    $banos=explode("-",$v);
		    $rang=($fields[3]["fields"][5]);
                    if(intval($banos[0]))$query[]="`i`.`banos`>='".intval($banos[0])."' ";
                    if(intval($banos[1]))$query[]="`i`.`banos`<='".intval($banos[1])."' ";
                    break;
                case "habitaciones1":
                    $habitaciones=explode("-",$v);
                    if(intval($habitaciones[0]))$query[]="`i`.`habitaciones`>='".intval($habitaciones[0])."' ";
                    if(intval($habitaciones[1]))$query[]="`i`.`habitaciones`<='".intval($habitaciones[1])."' ";
                    break;
                case "precio1":
                    $precio=explode("-",$v);
                    $rango=$fields[2]["fields"][1]["ranges"][$core->getFilter("tipovr")];
                    
                    $precio[0]=$currencyconverter->directConvert($precio[0],$core->getEnviromentVar("currency"));
                    $precio[1]=$currencyconverter->directConvert($precio[1],$core->getEnviromentVar("currency"));
                    if(intval($precio[0]))$query[]="`i`.`precio`>='".$precio[0]."' ";
                    if(intval($precio[1]))$query[]="`i`.`precio`<='".$precio[1]."' ";
                    
                    
                    break;
                case "tipoobjeto":
                    $v=array_search($v,$manager->_tipos);
                    $v++;
                    $query[]="`i`.`tipoobjeto`='".  intval($v)."'";  
                    break;
                case "subtipo":
                    $v=explode("_",$v);
                    $qs=array();
                    foreach($v as $valor){
                        $qs[]="`subtipo`='".$valor."'";
                    }
                    $query[]="(".implode(" or ",$qs).")";
                    break;
                default:
                    if($v==="true")$v=1;
                      $query[]="`i`.`".$k."`>='".  intval($v)."'";  
                    }
        }
    }
    
    return $query;
  }
  private function _createView(){
      global $core;
      
        $db=&$core->getDB(0,2);
      $manager=&$core->getApp("inmueblesManager");
      $protos=$manager->getPrototypes();
      
      $prots="";
      $props=array();
      foreach($protos as $k=>$p){
          $props=array_merge($props,$p->getFields());
      }
      $q=array();
      foreach($protos as $k=>$p){
          $f1=$p->getFields();
          $select=array();
          foreach($props as $k1=>$f){
              
              if(array_key_exists($k1,$f1)){
                  $select[$f]="`".$p->getTabla()."`.`".$f."`as '".$f."'";
              }
              else {
                  $select[$f]="0 as '".$f."'";
              }
            };
         $select["precio"]="`".$p->getTabla()."`.`precio`/`cc`.`valor` as 'precio'";
         if(array_key_exists("m2", $f1)){$select["m2"]="`".$p->getTabla()."`.`m2`*`ms`.`valor` as 'm2'";};
         if(array_key_exists("m2s", $f1)){$select["m2s"]="`".$p->getTabla()."`.`m2s`*`ms`.`valor` as 'm2s'";};
          $q[]="select ".
                  (array_key_exists("m2", $f1)?'(`precio`/`m2`)*`cc`.`valor` as \'preciom2\',':'0 as  \'preciom2\',').
                 // (array_key_exists("m2", $f1)?'`m2`*`ms`.`valor` as \'m2\',':'0 as  \'m2\',').
                 // (array_key_exists("m2s", $f1)?'`m2s`*`ms`.`valor` as \'m2s\',':'0 as  \'m2s\',').
                  implode(",",$select)." from `".$p->getTabla()."`
            left join `currencyconverter` as `cc`
                on `cc`.`moneda`=`".$p->getTabla()."`.`precio_moneda`
                    left join `metricas` as `ms` on `".$p->getTabla()."`.`metrica`=`ms`.`metrica`";
          
      }
      $q="create or replace  ALGORITHM=TEMPTABLE  view `inms` (`preciom2`,`".implode("`,`",$props)."`)as(".implode(")UNION (",$q).")";
      $db->query($q);
     // echo $q;
  }
  
  function searchbounds($query,$index=0,$max=50,$api=false){
    global $core,$config, $document,$searchResults;

    $db=&$core->getDB(0,2);

    $query2=explode(":",$query);
    $ne=array(($query2[0]),($query2[1]));
    $sw=array(($query2[2]),($query2[3]));
    //print_r(($ne[1])>0);
    if(($sw[1])>0&&($ne[1])<0){
        
        $posicion="
            `i`.`coordenaday`<='".$db->real_escape_string($ne[0])."'
            and `i`.`coordenaday`>='".$db->real_escape_string($sw[0])."'
                
            and ((`i`.`coordenadax`>='".$db->real_escape_string($sw[1])."'
                  and `i`.`coordenadax`<=180
                 )
                 or(`i`.`coordenadax`<='".$db->real_escape_string($ne[1])."'
                    and `i`.`coordenadax`>=-180
                ))";
        //echo $posicion;
    }
    else{
        $posicion="`i`.`coordenaday`<='".$db->real_escape_string($ne[0])."'
				and `i`.`coordenaday`>='".$db->real_escape_string($sw[0])."'
				and `i`.`coordenadax`<='".$db->real_escape_string($ne[1])."'
				and `i`.`coordenadax`>='".$db->real_escape_string($sw[1])."'";
    }
    $filters=$this->parseFilters();
    $this->_createView();
    
   $q="select ".
          // " `a`.`idinmueble` ".
       "distinct `i`.`id_expediente` as 'id_expediente',
    	 `i`.`id_cliente` as 'id_cliente',
    	 `i`.`tipoobjeto` as 'tipoobjeto',
    	 `a`.`tipotransaccion` as 'tipovr'
         ".
         
"from `inms` as `i`
  left join `anuncios` as `a` on (`a`.`idinmueble`= `i`.`id_expediente` and `a`.`tipoinmueble`= `i`.`tipoobjeto` and `a`.`user`=`i`.`id_cliente` )
  
where DATE_ADD(`a`.`fecvennormal`, INTERVAL 3 YEAR)>=now()
    and ((
            `a`.`vendido`=1
            and now()< date_add(`a`.`vendido_date`, interval +1 DAY )
            )
          or
            (
            `a`.`vendido`=0
            )
        )
    and `a`.`idinmueble`= `i`.`id_expediente` and `a`.`tipoinmueble`= `i`.`tipoobjeto` and `a`.`user`=`i`.`id_cliente`
".
          " and  (".$posicion.")".
           
           (count($filters)?" and (".implode(" and ",$filters).")":"").
      "
order by  ".
    //"`a`.`vendido`,".
        "`i`.`fecvenpremium` DESC,`i`.`fecha_alta` DESC limit ".(intval($index)?intval($index):"0").",".(intval($max)?intval($max):"50");
    // echo $q;
   
    
    $r=$db->query($q);
    
    $myfile = fopen("/var/www/vhosts/e-spacios.com/httpdocs1/test.log", "w");               
                fwrite($myfile, $q);
                fclose($myfile);
    
    
    $results=array();
    
    if($r){
        $manager=&$core->getApp("inmueblesManager");
        while ($i= $r->fetch_assoc()) {
            $u=new user($i["id_cliente"]);
            $i=$manager->getInmueble($i["id_expediente"],$i["tipoobjeto"],$u);
            if($i){
                $results[]=$i;
            }
        }
    }
    
    $searchResults=$results;
    if(!$api){//19.426, -99.123
      $document=$core->getDocument("search.html");
      $document->addVariable("#QUERY#",htmlentities($query,null,"utf-8"));
      $document->addVariable("#SEARCHTYPE#","bounds");
      $document->addVariable("#NE#",implode(",",$ne));
      $document->addVariable("#SW#",implode(",",$sw));
      $document->addVariable("#CENTER#",((($sw[0]-$ne[0])/2)+$sw[0]).",".((($sw[0]-$ne[0])/2)+$sw[0]));
      $document->addStyle("css/search.css");
      $document->addScript("js/searcher.js");
      $document->addScript("/js/jquery.classyloader.min.js");
      }
    return $results;
  }
  public function internalSearch($campos){
      global $core;
      $q="select distinct `i`.`id_expediente` as 'id_expediente',
    	 `i`.`id_cliente` as 'id_cliente',
    	 `i`.`tipoobjeto` as 'tipoobjeto',
    	 `a`.`tipotransaccion` as 'tipovr'
            from `inms` as `i`
            left join `anuncios` as `a` on (`a`.`idinmueble`= `i`.`id_expediente` and `a`.`tipoinmueble`= `i`.`tipoobjeto`)
          ";
      if(count( $campos)){
          $where=array();
          foreach($campos as $c){
              $where[]=implode(" ",$c);
          }
          $q.="where ".implode(" and ",$where);
      }
      //echo $q;
      $results=array();
      $db=&$core->getDB(0,2);
      if($r=$db->query($q)){
          $manager=$core->getApp("inmueblesManager");
          while ($i= $r->fetch_assoc()) {
            $u=new user($i["id_cliente"]);
            $i=$manager->getInmueble($i["id_expediente"],$i["tipoobjeto"],$u);
            if($i){
                $results[]=$i;
            }
        }
      }
    return $results;
  }
}
