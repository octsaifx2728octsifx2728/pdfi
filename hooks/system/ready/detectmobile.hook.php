<?php

class detectmobile_hook implements hook{
    public function fire(){
        global $core,$config;
        if(strpos($_SERVER["HTTP_USER_AGENT"],"AndroidTablet")){
            $core->browser->browser="AndroidTablet";
        }
        switch($core->browser->browser){
            case "Android":
               $core->setEnviromentVar("mobile",true);
               break;
            case "AndroidTablet":
               $core->setEnviromentVar("tablet",true);
               break;
        }
    }
}