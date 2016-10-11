<?php

class pay_core{
	private $_acceptedLanguajes;
	private $_languajeDefault=array("primitive"=>"es",	"variant"=>"");
        private $hook_launched=array();
        public $filters=array(
                    "amueblado"=>0,
                    "precio"=>0,
                    "habitaciones"=>0,
                    "banos"=>0,
                    "m2"=>0,
                    "preciom2"=>0,
                    "superficie"=>0,
                    "anio"=>0,
                    "estacionamientos"=>0,
                    "cocina"=>0,
                    "biblioteca"=>0,
                    "estudio"=>0,
                    "tv"=>0,
                    "chimenea"=>0,
                    "cava"=>0,
                    "vestidor"=>0,
                    "bodega"=>0,
                    "circuito"=>0,
                    "lavado"=>0,
                    "elevador"=>0,
                    "elevadors"=>0,
                    "tintoreria"=>0,
                    "aire"=>0,
                    "calefaccion"=>0,
                    "portero"=>0,
                    "red"=>0,
                    "sistema_seguridad"=>0,
                    "jardin"=>0,
                    "terraza"=>0,
                    "vista"=>0,
                    "parque"=>0,
                    "playa"=>0,
                    "muelle"=>0,
                    "alberca"=>0,
                    "jacuzzi"=>0,
                    "gimnasio"=>0,
                    "spa"=>0,
                    "tenis"=>0,
                    "golf"=>0,
                    "club"=>0,
                    "sjuegos"=>0,
                    "fiestas"=>0,
                    "juegos"=>0,
                    "mascotas"=>0,
                    "condominios"=>0,
                    "ecologico"=>0,
                    "discapacitados"=>0,
                    "helipuerto"=>0,
                    "tipovr"=>"tipovrventa",
                    "precio1"=>"0-50000000",
                    "habitaciones1"=>"0-10",
                    "banos1"=>"0-10",
                    "m21"=>"0-10000",
                    "preciom21"=>"0-100000",
                    "m2s1"=>"0-10000",
                    "anio1"=>"1900-2013",
                    "estacionamientos1"=>"0-10",
                    "estacionamientos21"=>"0-10",
                    "tipoobjeto"=>"residencial",
                    "subtipo"=>""
                );
	var $lang="es";
	var $langVariant="";
	var $lang_user_defined=false;
	var $browser;
	var $user;
	var $dbs=array();
	var $apps=array();
	protected $_enviroment=array(
                        "currency"=>"MXN",
                        "metrica"=>"metros",
                        "languaje"=>"es",
                        "languaje_variant"=>"",
                        "platform"=>"desktop");
public function parseDate($time,$format="long",$languaje=false){
  $time=intval($time)?intval($time):time();
  
  $languaje=$languaje?$languaje:$this->getEnviromentVar("languaje");
  
  $format='$$dateformat_'.$format.'$$';
  
  $lexicon=&$this->getLexicon();
  
  $format=$lexicon->traduce($format);
  
  $p=array(
      "%d"=>date("d",$time),
      "%j"=>date("j",$time),
      "%Y"=>date("Y",$time),
      "%g"=>date("g",$time),
      "%i"=>date("i",$time),
      "%a"=>date("a",$time),
      "%F"=>'$$'.date("F",$time).'$$'
      );
  return str_replace(array_keys($p),$p,$format);
}
public function fireEvent($event){
    global $config;
    include_once $config->paths["core/interfaces"]."/hook.php";
    $this->loadClass("hook_base");
    if(file_exists($config->paths["hooks"].$event)&&  is_dir($config->paths["hooks"]."/".$event)){
        $dir=scandir($config->paths["hooks"].$event);
        array_shift($dir);
        array_shift($dir);
        $hooks=array();
        $orders=array();
        foreach($dir as $file){
            $class=str_replace(".hook.php","",$file)."_hook";
            include_once($config->paths["hooks"].$event."/".$file);
            if(class_exists($class)){
                $eventObj=new $class();
                $hooks[$event."/".$class]=$eventObj;
                $orders[]=$class;
            }
        }
$keys=array_keys($hooks);
        while($k=key($hooks)){
            $hook=  $hooks[$k];
            $stop=false;
            $invalid=false;
            if(is_array($hook->depends)){
                foreach($hook->depends as $d){
                    if(!in_array($d,$this->hook_launched)&&array_key_exists ( $d , $hooks )){
                        $stop=true;
                    }
                    elseif(!in_array ( $d , $keys )){
                        $invalid=true;
                    }
                }
            }
            if(!$stop&&!$invalid){
                $hook->fire();
                $this->hook_launched[]=$k;
                unset($hooks[$k]);
            }
            elseif(!$invalid){
                unset($hooks[$k]);
                $hooks[$k]=$hook;
            }
            else{
                unset($hooks[$k]);
            }
        }
    }
}  
public function setEnviroment(){
  $this->setEnviromentLanguaje();
  $this->setEnviromentCurrency();
  $this->browser=get_browser_local();
  $this->setEnviromentVar("browser", $this->browser);
  $this->setLocalDomain();
  $this->setFilters();
}
public function setFilter($filter,$value){
    $this->filters[$filter]=$value;
  $_SESSION["filter_".$filter]=$value;
}
public function setFilters(){
  /*foreach(array_keys($this->filters) as $f){
      if($this->getFilter($f)===NULL){
          $this->setFilter($f,$this->filters[$f]);
      }
      else {
          $this->filters[$f]=$this->getFilter($f);
      }
  }*/
}
public function getFilter($filter){
   return array_key_exists("filter_".$filter, $_SESSION)?$_SESSION["filter_".$filter]:NULL;
}
public function getFilters($vals=false){
    
  return $vals?$this->filters:array_keys($this->filters);
}
public function getFilterValues(){
    $filters=$this->getFilters();
    $fs=array();
    foreach($filters as $f){
        $fs[$f]=$this->getFilter($f);
    }
  return $fs;
}
public function setLocalDomain(){
    $domain=(object)array(
        "domain"=>$_SERVER["HTTP_HOST"],
        "protocol"=>array_key_exists("HTTPS",$_SERVER)?"https":"http",
        "path"=>$_SERVER["REQUEST_URI"]
    );
    $domain->subdomain=array_reverse(explode(".",$domain->domain));
    $domain->subdomain=array_key_exists(2,$domain->subdomain)?$domain->subdomain[2]:null;
    $domain->full=$domain->protocol."://".$domain->domain.$domain->path;
    $domain->base=$domain->protocol."://".$domain->domain."/";
    $this->setEnviromentVar("domain", $domain);
}
public function setEnviromentCurrency(){
  global $user;
  $lan=$user->get("currency");
  if($lan){
    $this->setCurrency($lan);
  }
  elseif($_SESSION["currency"]){
    $this->setCurrency($_SESSION["currency"]);
  }
  elseif($_COOKIE["currency"]){
    $this->setCurrency($_COOKIE["currency"]);
  }
  else{
    $lan=$this->getEnviromentVar("languaje");
    switch($lan){
      case "en":
        $this->setCurrency("USD");
        break;
      default:
        $this->setCurrency("MXN");
        break;
    }
  }
}
public function setEnviromentLanguaje(){
  global $user;
  $lan=$user->get("languaje");
  if($lan&&$this->languajeExists($lan)){
    $this->setLanguaje($lan);
    $this->setEnviromentVar("lang_user_defined", true);
  }
  elseif($_SESSION["languaje"]&&$this->languajeExists($_SESSION["languaje"])){
    $this->setLanguaje($_SESSION["languaje"]);
    $this->setEnviromentVar("lang_user_defined", true);
  }
  elseif($_COOKIE["languaje"]&&$this->languajeExists($_COOKIE["languaje"])){
    $this->setLanguaje($_COOKIE["languaje"]);
    $this->setEnviromentVar("lang_user_defined", true);
  }
  else{
    $languajes=explode(";",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    $lang=false;
    foreach ($languajes as $l){
      $l=explode(",",$l);
      if($this->languajeExists($l[1])){
        $lang=$l[1];
        break;
      }
    }
    $lang=$lang?$lang:"en";
    $this->setLanguaje($lang);
    $this->setEnviromentVar("lang_user_defined", false);
  }
}
public function setCurrency($lan){
  global $user,$config;
  $user->set("currency",$lan);
  $_SESSION["currency"]=$lan;
  setcookie("currency",$lan, time()+31536000,"/");
  $this->setEnviromentVar("currency", $lan);
  $config->defaults["currency"];
  $this->currency=$lan;
}
public function setLanguaje($lan){
  global $user,$config;
  $user->set("languaje",$lan);
  $_SESSION["languaje"]=$lan;
  setcookie("languaje",$lan, time()+31536000,"/");
  $this->setEnviromentVar("languaje", $lan);
  $config->defaults["languaje"]=$lan;
  $this->lang=$lan;
}
public function languajeExists($lan){
  global $config;
  return (file_exists($config->paths["core/languajes"]."/$lan/$lan.lang.php"));
}
public function setEnviromentVar($var,$val,$persistent=false){
    if($persistent){
        $_SESSION["env_".$var]=$val;
    }
    $this->_enviroment[$var]=$val;
    }
public function getEnviromentVar($var){
    
    if(array_key_exists("env_".$var,$_SESSION)){
        
        return($_SESSION["env_".$var]);
    }
    return $this->_enviroment[$var];
    }
	public function setUserLang($lang="es",$variant=""){
		setcookie("languaje","",time()-1);
		unset($_COOKIE["languaje"]);
		$this->defLanguaje($lang,$variant);
		setcookie("languaje",$this->lang."_".$this->langVariant,null,"/");
		$this->lang_user_defined=true;
	}
	public function loadClass($name){
		global $config;
		if(file_exists($config->paths["core/class"].$name.'.class.php')){
			include_once $config->paths["core/class"].$name.'.class.php';
			if(class_exists($name)){
				return $name;
			}
		}
		return false;
	}

/**
 * Detecta y define el lenguaje a utilizar por el nucleo.
 * @param STRING (optional) $defaultPrimitive = "es"
 * @param STRING (optional) $defaultVariant = ""
 */
	public function defLanguaje($defaultPrimitive="es",$defaultVariant=""){
		global $config;
		if(array_key_exists("languaje", $_COOKIE)){
		  $lang=explode("_",$_COOKIE["languaje"]);

		  $this->lang=$lang[0];
		  $this->langVariant=$lang[1];
		  $this->lang_user_defined=true;
                  $this->setEnviromentVar("languaje",$this->lang);
		}
		else {
		    $lan=$this->_adivinaLenguaje();
		}
	}
	private function _adivinaLenguaje(){

		global $config;
		$languajes=explode(";",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
		$this->_acceptedLanguajes=array();
		foreach($languajes as $l){
			$l=explode(",",$l);
			$this->_acceptedLanguajes[$l[count($l)-1]]=array("primitive"=>$l[count($l)-1],
										"variant"=>$l[0]);
		}
		foreach($this->_acceptedLanguajes as $l){
			  if(file_exists($config->paths["core/languajes"].$l["primitive"])){
				  $this->_languajeDefault=$l;
				  $languaje_Found=true;
				  break;
			  }
		  }
               if($languaje_Found){
		  $this->lang=$this->_languajeDefault["primitive"];
		  $this->langVariant=$this->_languajeDefault["variant"];
		  $this->lang_user_defined=false;
                  $this->setEnviromentVar("languaje",$this->lang);
                  return $this->lang;
                    
                  }else{
		  $this->lang="es";
		  $this->langVariant="";
		  $this->lang_user_defined=false;
                  $this->setEnviromentVar("languaje",$this->lang);
                  return $this->lang;
                    
                  }

	}
	public function getLexicon(){
		global $config;
                
		 $dp=$config->paths["core/languajes"]."/#DP#/#DP#.lang.php";
		  $dp=str_replace("#DP#",$this->getEnviromentVar("languaje"),$dp);
		 $dv=$config->paths["core/languajes"]."/#DP#/#DP#_#DV#.lang.php";
		  $dv=str_replace(array("#DP#","#DV#"),array($this->lang,$this->langVariant),$dv);
                  $this->loadClass("lang");
                  
		    if(file_exists($dv)){
			$langpath=$dv;
			include_once $langpath;
		      }
		    elseif(file_exists($dp)){
			$langpath=$dp;
			include_once $langpath;
		    }
		    $langClass=$this->getEnviromentVar("languaje")."_languaje";
	    if(class_exists($langClass)){
	      include_once $config->paths["core/class"].'lexicon.class.php';
	      $d=new $langClass();
	      $l=new lexicon($d);
	      return $l;
	    }
		else{
	      include_once $config->paths["core/languajes"]."/es/es.lang.php";
	      include_once $config->paths["core/class"].'lexicon.class.php';
	      $d=new languaje();
	      $l=new lexicon($d);
	      return $l;
		}
	}
	/**
	 * 
	 * Detecta y almacena las características del navegador
	 */
	public function defBrowser(){
		$this->browser=get_browser();
	}
	/**
	 * 
	 * Detecta y autentifica al usuario
	 * TODO: Agregar métodos de autentificación
	 */
	public function defUser(){
		global $config,$user;
		if(!session_id()){
			session_start();
			}
		include_once $config->paths["core/class"].'user.class.php';
		$user=new user();
		$this->user=$user;
	}
	public function defDB(){
		global $config;
		include_once $config->paths["core/class"].'db.class.php';
		$dbs=array();
		foreach($config->db as $db){
                        $con=  mysql_connect($db->host,$db->user,$db->password);
                        mysql_select_db($db->database,$con);
                        
                        $dbase=  mysqli_connect("p:".$db->host,$db->user, $db->password, $db->database);
                        
			$dbs=new db($db);
			$dbs2=&$con;
			$dbs3=&$dbase;
                        $this->dbs[]=array(&$dbs,&$dbs2,&$dbs3);
		}
	}
	public function getDB($index=0,$type=0){
	  return $this->dbs[$index][$type];
	}

	public function getDocument($doc){
	  global $config;
          $path=$config->paths["html"].$doc;
          
          if($this->getEnviromentVar("mobile")){
              $path=str_replace("html/","htmlmobile/",$config->paths["html"].$doc);
          }
          if($this->getEnviromentVar("tablet")){
              $path=str_replace("html/","html_tablet/",$config->paths["html"].$doc);
          }
          
	  if(file_exists($path)){
	    $this->loadClass("document");
	    $document=new document($path);
	    return $document;
	  }
          else {
              $path=$config->paths["html"].$doc;
                if(file_exists($path)){
                  $this->loadClass("document");
                  $document=new document($path);
                  return $document;
                }
          }
          
	}
    public function getChunk($key,$params=array()){
          global $config;
          
        include_once $config->paths["core/interfaces"]."/chunk.php";
        $this->loadClass("chunk_base");
    $keys=trim($key,"[]");
        $keys=explode("?",$keys);
        $key=$keys[0];
        if($keys[1]){
                $params=is_array($params)?$params:array();
                $keys=explode("&",$keys[1]);
                foreach($keys as $k){
                        $k=explode("=",$k);
                        $params[$k[0]]=trim($k[1],"`");
                }
        }
    $loaded=false;
    if($this->getEnviromentVar("mobile")){
        if(file_exists($config->paths["chunks_mobile"].$key.'/'.$key.'.chunk.php')){
          include_once $config->paths["chunks_mobile"].$key.'/'.$key.'.chunk.php';
          $k=$key."_chunk";
          $loaded=true;
          }
          elseif(file_exists($config->paths["chunks_mobile"].$key.'.chunk.php')){
          include_once $config->paths["chunks_mobile"].$key.'.chunk.php';
          $k=$key."_chunk";
          $loaded=true;
          }
        
    }
    if(!$loaded&&$this->getEnviromentVar("tablet")){
        if(file_exists($config->paths["chunks_tablet"].$key.'/'.$key.'.chunk.php')){
          include_once $config->paths["chunks_tablet"].$key.'/'.$key.'.chunk.php';
          $k=$key."_chunk";
          $loaded=true;
          }
          elseif(file_exists($config->paths["chunks_tablet"].$key.'.chunk.php')){
          include_once $config->paths["chunks_tablet"].$key.'.chunk.php';
          $k=$key."_chunk";
          $loaded=true;
          }
        
    }
        if(!$loaded&&file_exists($config->paths["chunks"].$key.'/'.$key.'.chunk.php')){
          include_once $config->paths["chunks"].$key.'/'.$key.'.chunk.php';
          $k=$key."_chunk";
          }
          elseif(!$loaded&&file_exists($config->paths["chunks"].$key.'.chunk.php')){
          include_once $config->paths["chunks"].$key.'.chunk.php';
          $k=$key."_chunk";
          }
          if(class_exists($k)){
              $chunk=new $k($params);
                    $chunk->params=$params;
                    $chunk->selfpath=$config->paths["chunks"].$key.'/';
              return $chunk;
            }
    }
  public function getApp(){
    global $config;
        $args = func_get_args();
        $numArgs=func_num_args();
        $appName=$args[0];
        $key=$args[0];
        foreach($args as $a){
            $key.=strval($a);
        }
        $key=md5($key);
	if(!array_key_exists($key, $this->apps)){
	    $app=null;
             
	    if(file_exists($config->paths["app"].$appName."/".$appName.".app.php")){
                
                 
	      include_once $config->paths["app"].$appName."/".$appName.".app.php";
              
             
                
	      $appName=$appName."_app";
              
             
              
	      if(class_exists($appName)){
                  array_shift($args);
                 
			$app=new $appName($args);
	      	}
	    }
		$this->apps[$key]=$app;
		
	}
    return $this->apps[$key];
  }
  public function addAlert($message,$level=5){
    $_SESSION["alerts"][]=array("message"=>$message,"level"=>$level);
  }
  public function getAlerts(){
    $alerts=$_SESSION["alerts"];
    unset($_SESSION["alerts"]);
    return $alerts;
  }
  public function getHandler($name,$params=false){
    global $config;
	include_once $config->paths["core/interfaces"]."/handler.php";
  	$handler=null;
    if(file_exists($config->paths["handlers"].$name."/".$name.".handler.php")){
      include_once $config->paths["handlers"].$name."/".$name.".handler.php";
      $name=$name."_handler";
	  $handler=new $name($params);
	}
	return $handler;
  }
  
}
