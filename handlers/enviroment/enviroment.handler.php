<?php
class enviroment_handler implements handler{
    public function run($task,$params=array()){
        global $core,$result;
        switch($task){
            case "setEnviroment":
                $filters=$core->setEnviromentVar($_GET["filtername"],$_GET["filtervalue"],true);
                $result["error"]="0";
                $result["errorDescription"]="OK";
                break;
            case "set":
                $filters=$core->setFilter($_GET["filtername"],$_GET["filtervalue"]);
                $result["error"]="0";
                $result["errorDescription"]="OK";
                break;
        }
    }
}