<?php
class lang_handler implements handler{
	function run($task,$params=array()){
            global $document,$core, $user_view,$user;
            if($core->languajeExists($task)){
              $core->setLanguaje($task);
            }
            $task=$core->getEnviromentVar("languaje");
            if($_GET["return"]){
                    if($_GET["return"]!="none"){
                    header("location:".$_GET["return"]."#c".rand());
                    }
              }
            elseif($_SERVER["HTTP_REFERER"]){
                    header("location:".$_SERVER["HTTP_REFERER"]."#c".rand());
              }
              else {
                    header("location:/"."#c".rand());
              }
              exit;
            
	
}}