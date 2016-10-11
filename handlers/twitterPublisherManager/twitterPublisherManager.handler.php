<?php


class twitterPublisherManager_handler implements handler{
    private $_oauth_consumer_key="jUAhVJ0bvfU33fV9MvemCw";
    private $_oauth_consumer_secret="gYufKpEEglnPISL2mA1hF3hsukoBtaosRSv5Wu1w";
    private $_app_secret="4CIgNM73Iz643bz3";
    private $_scope=array(
        "r_basicprofile",
        "rw_company_admin"
    );
    private $_pageIDes="2414183";
    private $_pageIDen="2414183";
	function run($task,$params=array()){
            global $core,$config;
            switch($task){
                case "connect":
                    $TwitterOAuth=$core->getApp("TwitterOAuth",$this->_oauth_consumer_key,$this->_oauth_consumer_secret);
                    $temporary_credentials = $TwitterOAuth->getRequestToken($config->paths["urlbase"]."/app/twitterPublisherManager/connected");
                    $_SESSION["twitterpublisher_oauth_token"]=$temporary_credentials["oauth_token"];
                    $_SESSION["twitterpublisher_oauth_token_secret"]=$temporary_credentials["oauth_token_secret"];
                    $redirect_url = $TwitterOAuth->getAuthorizeURL($temporary_credentials,false);
                    header("location:".$redirect_url);
                    exit;
                    
                    break;
                case "connected":
                    $TwitterOAuth=$core->getApp("TwitterOAuth",$this->_oauth_consumer_key,$this->_oauth_consumer_secret,$_SESSION["twitterpublisher_oauth_token"],$_SESSION["twitterpublisher_oauth_token_secret"]);
                     $token_credentials = $TwitterOAuth->getAccessToken($_REQUEST['oauth_verifier']);
                     $TwitterOAuth->publisherStoreParam("twitter_oauth_token",$token_credentials["oauth_token"]);
                     $TwitterOAuth->publisherStoreParam("twitter_oauth_token_secret",$token_credentials["oauth_token_secret"]);
                     $TwitterOAuth->publisherStoreParam("twitter_user_id",$token_credentials["user_id"]);
                     $TwitterOAuth->publisherStoreParam("twitter_screen_name",$token_credentials["screen_name"]);
                     header("location:/");
                    exit;
                    break;
                case "post":
                    $im=$core->getApp("inmueblesManager");
                    $inmueble=$im->getInmueble($_GET["id"],$_GET["tipo"]);
                    $TwitterOAuth=$core->getApp("TwitterOAuth",
                            $this->_oauth_consumer_key,
                            $this->_oauth_consumer_secret);
                    $TwitterOAuth=$core->getApp("TwitterOAuth",
                            $this->_oauth_consumer_key,
                            $this->_oauth_consumer_secret,
                            $TwitterOAuth->publisherStoreParam("twitter_oauth_token"),
                            $TwitterOAuth->publisherStoreParam("twitter_oauth_token_secret"));
                    echo $TwitterOAuth->publisherStoreParam("twitter_oauth_token");
                    $TwitterOAuth->publisherPostInmueble($inmueble);
                    exit;
                    break;
            }
        }
}
