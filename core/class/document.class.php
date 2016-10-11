<?php

class document{
   private $_data;
  private $_strings=array("doctype"=>"","namespaces"=>"");
  private $_estilos=array();
  private $_scripts=array();
  private $_variables=array();
  private $_addAlerts=false;
  function document($path){
    global $config;
    $this->_data=file_get_contents($path);
    $this->setDoctype($config->defaults["doctype"]);
    foreach(array_keys($config->defaults["namespaces"]) as $nm){
      $this->addNamespace($nm,$config->defaults["namespaces"][$nm]);
    }
  }
  public function addNamespace($n,$v){
    $this->_strings["namespaces"].=" ".$n.'="'.$v.'"';
  }
  public function setDoctype($type){
    switch($type){
      case "html5":
	$this->_strings["doctype"]="<!DOCTYPE html>";
	break;
      default:
	$this->_strings["doctype"]="";
    }
  }
  public function addStyle($path){
  	global $core;
      $path=ltrim($path,"/");
      
  	if(strpos($path,"http")===false){
            
  		 if($core->getEnviromentVar("mobile")){
                    $pathm=str_replace("css/","css_mobile/",$path);
                    if(file_exists($pathm)){
                        $path=$pathm;
                    }
                 }
                 elseif($core->getEnviromentVar("tablet")){
                    $pathm=str_replace("css/","css_tablet/",$path);
                    if(file_exists($pathm)){
                        $path=$pathm;
                    }
                 }
                 else {
                 }
  	}
	else {
		$path=str_replace("http:",($_SERVER["HTTPS"]?"https:":"http:"),$path);
	}
    $this->_estilos[$path]=$path;
  }
  public function addScript($path){
      global $core; 
      $path=ltrim($path,"/");
  	if(strpos($path,"http")===false){
            
  		 if($core->getEnviromentVar("mobile")){
                    $pathm=str_replace("js/","js_mobile/",$path);
                    if(file_exists($pathm)){
                        $path=$pathm;
                    }
                    //echo "mobile";
                 }
  		 elseif($core->getEnviromentVar("tablet")){
                    $pathm=str_replace("js/","js_tablet/",$path);
                    if(file_exists($pathm)){
                        $path=$pathm;
                    }
                   // echo "tablet";
                 }
                 else {
                     //echo "normal";
                 }
  	}
	else {
		$path=str_replace("http:",($_SERVER["HTTPS"]?"https:":"http:"),$path);
	}
    $this->_scripts[$path]=$path;
  }
  private function _parseEstilos(){
    global $config;
    
    
    $key=md5(trim(file_get_contents(".git-ftp.log")));
    
    $plantilla=file_get_contents($config->paths["html"]."style.html");
    
    $estilos="";
    foreach($this->_estilos as $es){
      $estilos.=str_replace("#PATH#",$es."?v=".$key,$plantilla);
    }
    return $estilos;
  }
  private function _parseScripts(){
    global $config;
    $key=md5(trim(file_get_contents(".git-ftp.log")));
    
    $plantilla=file_get_contents($config->paths["html"]."script.html");
    $script="";
    foreach($this->_scripts as $es){
      $script.=str_replace("#PATH#",$es.(substr_count($es,"http",0,6)?"":(substr_count($es,"?")?"&":"?")."v=".$key),$plantilla);
    }
    return $script;
  }
  public function parsechunks($doc){
    global $core;
    $chunk_keys=array();
    preg_match_all("/\[\[.*\]\]/i",$doc,$chunk_keys);
    $chunk_keys=$chunk_keys[0];
    foreach($chunk_keys as $k){
      $chunk=$core->getChunk($k);
      if($chunk){
	$text=$chunk->out();
      }
      else {
	$text="";
      }
      $doc=str_replace($k,$text,$doc);
    }
    return $doc;
  }
  public function addAlerts(){
    $this->_addAlerts=true;
  }
  public function addVariable($var,$val){
    $this->_variables[$var]=$val;
  }
  public function out($return=false){
    global $core,$config;

    $doc=$this->_data;

    $doc=$this->parsechunks($doc);

    if($this->_addAlerts){
      $alerts=$core->getAlerts();
      if(is_array($alerts)){
	$alert_plantilla=file_get_contents($config->paths["html"]."alert.html");
	$alertas="";
	foreach($alerts as $a){
	  $p=array(
	    "#MENSAJE#"=>$a["message"],
	    "#NIVEL#"=>$a["level"]
	    );
	  $alertas.=str_replace(array_keys($p),$p,$alert_plantilla);
	}
      }
    }

    $p=array(
      "#DOCTYPE#"=>$this->_strings["doctype"],
      "#NAMESPACES#"=>$this->_strings["namespaces"],
      "#LANG#"=>$core->lang,
      "#ESTILOS#"=>$this->_parseEstilos(),
      "#SCRIPTS#"=>$this->_parseScripts(),
      "#ALERTAS#"=>$alertas
      );
    $p=array_merge($p,$this->_variables);
    $doc=str_replace(array_keys($p),$p,$doc);

    

    $lexicon= $core->getLexicon();
    if($lexicon){
      $doc=$lexicon->traduce($doc);
    }
    if($return){
    	return $doc;
    }
    echo $doc;
  }
}