<?php

class metricaconverter_app{
    public function convert($valor,$origen="metros",$destino="metros"){
        global $core;
        $db=&$core->getDB(0,2);
        $q="select `valor` from `metricas` where `metrica`='".$db->real_escape_string($origen)."' limit 1";
        
        $valor1=0;
        $valor2=1;
        if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
                $valor1= floatval($i["valor"]);
            }
        }
        if($destino!="metros"){
            $q="select `valor` from `metricas` where `metrica`='".$db->real_escape_string($destino)."' limit 1";
            
            if($r=$db->query($q)){
                if($i=$r->fetch_assoc()){
                    $valor2= floatval($i["valor"]);
                }
            }
        }
        //echo "(floatval($valor)*floatval($valor1))*floatval($valor2) = ".(floatval($valor)*floatval($valor1)*floatval($valor2));
        return floatval($valor)*floatval($valor1)/floatval($valor2);
    }
}