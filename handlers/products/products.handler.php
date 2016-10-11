<?php

class products_handler implements handler{
    public function run($task, $params = array()) {
      global $core,$result;
      $task=explode("/",$task);  
      switch($task[0]){
          case "getProductInfo":
              $manager=$core->getApp("inmueblesManager");
              $inmueble=$manager->getInmueble($_GET["id"],$_GET["tipo"]);
              if($inmueble){
                  $core->loadClass("producto");
                  $product=new producto($task[1]);
                  $data=$product->getInfo($inmueble);
                  
		  $result["error"]="0";
                  $result["errorDescription"]='ok';
                  $result["datos"]=$data;
              }
              break;
      }
    }
}
