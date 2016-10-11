<?php

class urlShorter_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$result;
        switch($task){
            case "short":
                $shorter=$core->getApp("urlShorter");
                $url=$shorter->acortar($_GET["url"]);
                $result["error"]=0;
                $result["errorDescription"]="ok";
                $result["url"]=$url;
                break;
            case "long":
                $shorter=$core->getApp("urlShorter");
                $url=$shorter->alargar($_GET["key"]);
                $result["error"]=0;
                $result["errorDescription"]="ok";
                $result["url"]=$url;
                if($_GET["redirect"]){
                    header("location:".$url);
                    exit;
                }
                break;
        }
    }    
}