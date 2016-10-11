<?php
class upload_handler implements handler{
    public function run($task, $params = array()) {
        global $core;
    	$app=$core->getApp("uploadManager");
    	if($app){
    		$result=$app->upload($task);
		}
	echo "<script type='text/javascript'>". ($_POST["callback"]?$_POST["callback"]:$_GET["callback"])."(".json_encode($result).",'".($_POST["idcallback"]?$_POST["idcallback"]:$_GET["idcallback"])."','".($_POST["tipo"]?$_POST["tipo"]:$_GET["tipo"])."');</script>";
	exit;
    }
}