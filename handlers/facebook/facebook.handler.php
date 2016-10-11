<?php

class facebook_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$config;
        $app=$core->getApp("facebook");
        switch($task){
            case "login":
                $_SESSION["facebook_return"]=$_GET["return"]?$_GET["return"]:"/";
                $url=$app->getLoginUrl();
                header("location:".$url);
                exit;
                break;
            case "log":
                if($_GET["error"]){
                  $app->handleError();
                }
                if($_GET["code"]){

                  $app->submitCode($_GET["code"]);
                }
                header("location:".($_SESSION["facebook_return"]?$_SESSION["facebook_return"]:"/"));
                exit;
                break;
            default:
        }
    }
}