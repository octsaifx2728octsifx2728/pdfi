<?php

class languaje_app {
  public function getLanguajesList(){
    global $config;
    $dir=scandir($config->paths["core/languajes"]);
    array_shift($dir);
    array_shift($dir);
    $langs=array();
    foreach($dir as $d){
      
      if(file_exists($config->paths["core/languajes"]."/".$d."/info.xml")){
        $cofiglan=simplexml_load_file($config->paths["core/languajes"]."/".$d."/info.xml");
        $langs[]=(object)array(
            "name"=>(string)$cofiglan->name[0],
            "key"=>(string)$cofiglan->key[0]
        );
      }
    }
    return $langs;
  }
}