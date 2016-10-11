<?php

class checkMetrica_hook implements hook{
    public function fire(){
        global $core;
        $metrica=$core->getEnviromentVar("metrica");
        switch($metrica){
            case "metros":
                break;
            case "pies":
                break;
            case "acres":
                break;
            default:
                $core->setEnviromentVar("metrica","metros",true);
                $metrica=$core->getEnviromentVar("metrica");
        }
    }
}