<?php

class forceLang_hook implements hook{
    public function fire(){
        global $core;
        if($_GET["forceLang"]){
            $core->setLanguaje($_GET["forceLang"]);
        }
    }

}