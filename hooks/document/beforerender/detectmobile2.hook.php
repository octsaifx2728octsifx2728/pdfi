<?php

class detectmobile2_hook implements hook{
    public function fire(){
        global $core,$document;
        switch($core->browser->browser){
        case "Android":
            $document->addStyle("/css/android.css");
            break;
        case "AndroidTablet":
            $document->addStyle("/css/android.css");
            break;
        }
    }
}