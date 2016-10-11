<?php


class facebookPublisherManager_handler implements handler{
    private $_app_id="1415675568645418";
    private $_app_secret="a6db0ef4dab8a2e88ad5f34ae6ef8abc";
    private $_scope=array(
        "publish_actions",
        "manage_pages",
        "publish_stream"
    );
    private $_pageIDes="199206810155607";
    private $_pageIDen="155311924582919";
	function run($task,$params=array()){
            global $core;
            switch($task){
                case "connect":
                    $facebook=$core->getApp("facebook");
                    $token=md5("token".rand());
                    $_SESSION["facebookPublisherManager_connectToken"]=$token;
                    $url=$facebook->getLoginUrl(
                            $this->_app_id,
                            "/app/facebookPublisherManager/connected",
                            $token,
                            implode(",", $this->_scope)
                            );
                    header("location:".$url);
                    exit;
                    break;
                case "connected":
                    if($_GET["code"]){
                        $facebook=$core->getApp("facebook");
                        $fb_data=$facebook->exchangeCode(
                                $_GET["code"],
                                $this->_app_id,
                                $this->_app_secret,
                                "/app/facebookPublisherManager/connected"
                                );
                        if($fb_data->error){
                            echo $fb_data->error->message;
                            exit;
                        }
                        else{
                            $facebook->publisherStoreParam("userToken",$fb_data->access_token);
                            $facebook->publisherStoreParam("userTokenExpires",$fb_data->expires_in);
                            
                            $pages=($facebook->get("me/accounts",$fb_data->access_token,true));
                            $found=false;
                            foreach($pages->data as $p){
                                if($p->id==$this->_pageIDes){
                                   $facebook->publisherStoreParam("pageToken_es",$p->access_token);
                                   $found=true;
                                }
                                if($p->id==$this->_pageIDen){
                                   $facebook->publisherStoreParam("pageToken_en",$p->access_token);
                                   $found=true;
                                }
                            }
                            if(!$found){
                                echo "pagina no pertenece a este usuario";
                                exit;
                            }
                            header("location:/");
                            exit;
                        }
                    }
                    else {
                        echo "failed";
                        exit;
                    }
                    exit;
                    break;
                case "post":
                    $facebook=$core->getApp("facebook");
                    $im=$core->getApp("inmueblesManager");
                    $inmueble=$im->getInmueble($_GET["id"],$_GET["tipo"]);
                    $facebook->publisherPostInmueble($inmueble);
                    exit;
                    break;
            }
        }
}
