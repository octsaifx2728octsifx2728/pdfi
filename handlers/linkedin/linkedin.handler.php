<?php

class linkedin_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$config,$user;
        $app=$core->getApp("linkedin");
        switch($task){
            case "login":
                $_SESSION["linkedin_state"]=md5("linkedinoauth".rand().time());
                $_SESSION["linkedin_return"]=$_GET["return"]?$_GET["return"]:"/";
                $path=$app->getLoginLink($_SESSION["linkedin_state"],$config->paths["urlbase"]."/app/linkedin/oauthreturn");

                header("location:".$path);
                break;
                //exit;
            case "oauthreturn":
                if($_SESSION["linkedin_state"]!=$_GET["state"]){
                    header("location:".($_SESSION["linkedin_return"]?$_SESSION["linkedin_return"]:"/"));
                    exit;
                }
                if($_GET["error"]=="access_denied"){
                    header("location:".($_SESSION["linkedin_return"]?$_SESSION["linkedin_return"]:"/"));
                    exit;
                }
                $r=$app->submitCode($_GET["code"],$config->paths["urlbase"]."/app/linkedin/oauthreturn");
                $app->login($r->access_token,$r->expires);
                header("location:".($_SESSION["linkedin_return"]?$_SESSION["linkedin_return"]:"/"));
                break;
        }
    }
}