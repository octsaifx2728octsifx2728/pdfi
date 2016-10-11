<?php


class linkedinPublisherManager_handler implements handler{
    private $_app_id="fl4h6p68t9ns";
    private $_app_secret="4CIgNM73Iz643bz3";
    private $_scope=array(
        "r_basicprofile",
        "rw_company_admin",
        "rw_nus"
    );
    private $_pageIDes="2507662";
    private $_pageIDen="2507662";
	function run($task,$params=array()){
            global $core,$config;
            $app=$core->getApp("linkedin");
            switch($task){
                case "connect":
                    $_SESSION["linkedinPublisher_state"]=md5("linkedinoauth".rand().time());
                    $path=$app->getLoginLink($_SESSION["linkedinPublisher_state"],
                            $config->paths["urlbase"]."/app/linkedinPublisherManager/connected",
                            $this->_app_id,
                            implode(",",$this->_scope));
                    header("location:".$path);
                    exit;
                    break;
                case "connected":
                    
                    if($_SESSION["linkedinPublisher_state"]!=$_GET["state"]){
                       echo "conexión ilegal";
                        exit;
                    }
                    if($_GET["error"]=="access_denied"){
                        echo "acceso denegado";                        
                        exit;
                    }
                    $r=$app->submitCode($_GET["code"],
                            $config->paths["urlbase"]."/app/linkedinPublisherManager/connected",
                            $this->_app_id,
                            $this->_app_secret);
                    if($r->access_token){
                        $app->publisherStoreParam("ln_userToken",$r->access_token);
                        $app->publisherStoreParam("ln_userTokenExpires",$r->expires_in);
                        $app->publisherStoreParam("ln_company_en",$this->_pageIDen);
                        $app->publisherStoreParam("ln_company_es",$this->_pageIDes);
                        header("location:/");
                        exit;
                    }
                    else{
                       echo $r->error_description;
                       exit;
                    }
                    exit;
                    break;
                case "post":
                    $im=$core->getApp("inmueblesManager");
                    $inmueble=$im->getInmueble($_GET["id"],$_GET["tipo"]);
                    $app->publisherPostInmueble($inmueble);
                    exit;
                    break;
            }
        }
}
