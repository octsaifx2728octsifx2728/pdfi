<?php

class models_handler implements handler{
    public function run($task, $params = array()) {
        global $config,$result, $core;
    switch($task){
        case "prototipos":
            $manager=$core->getApp("inmueblesManager");
            $prots=$manager->getPrototypes();
            $prototipos=array();
            foreach($prots as $t=>$p){
                $form=$p->getNewForm();
                
                $transacciones=array();
          foreach($form[0]["fields"][0]["options"] as $trans){
              $key=md5($trans["name"].$t);
              $p1=array(
                "VALOR"=>$trans["name"],
                "TITULO"=>$trans["label"],
                "NEW"=>$trans["name"]=="tipovrcomparto"?"new":"",
                "NOMBRE"=>"tipovr",
                "KEY"=>$key
                );
              $transacciones[]=$p1;
          }
                
               $prototipos[]=array(
                    "VALOR"=>$t,
                    "TITULO"=>$p->nombreS,
                   "transacciones"=>$transacciones
                      );
            }
            
            $monedas=array();
        $default=$core->getEnviromentVar("currency");
        foreach($config->currencyconverter as $currency){
            
          $p=array(
            "KEY"=>$currency["key"],
            "DESCRIPTION"=>$currency["descripcion"],
              "ACTUAL"=>$default==$currency["key"]?1:0
            );
          $monedas[]=$p;
          }
            
            
            $result["error"]="0";
            $result["errorDescription"]='OK';
            $result["prototipos"]=$prototipos;
            $result["monedas"]=$monedas;
            break;
    }    
    }
}