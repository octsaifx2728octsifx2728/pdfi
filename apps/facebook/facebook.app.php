<?php
class facebook_app{
  function handleError(){
    global $core;
    $core->addAlert('$$facebook_connectError$$: '.$_GET["error_description"]);
    header("location:/");
    exit;
  }
  public function renewToken($oldToken){
      /*
      global $config;
      $url="https://graph.facebook.com/oauth/access_token?"            
            ."client_id=APP_ID&"
            ."client_secret=APP_SECRET&"
            ."grant_type=fb_exchange_token&"
            ."fb_exchange_token=EXISTING_ACCESS_TOKEN";
      
      $p=array(
          "APP_ID"=>$config->defaults["facebook"]->app_id,
          "APP_SECRET"=>$config->defaults["facebook"]->app_secret,
          "EXISTING_ACCESS_TOKEN"=>$oldToken
         );
        $url=str_replace(array_keys($p),$p,$url);
        
        
        
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'facebook-php-3.1');

    $respuesta=curl_exec($ch);
    curl_close($ch);

    //echo $respuesta;
    $respuestap=explode("&",$respuesta);
    $code=array();
    foreach($respuestap as $r){
	$r=explode("=",$r);
	$code[$r[0]]=$r[1];
    }
     return $code;   
       * 
       */
  }
  public function getLoginUrl($app_id=false,$redirect_uri=false,$arbitrary=false,$scope=false){
      /*
      global $config;
      $app_id=$app_id?$app_id:$config->defaults["facebook"]->app_id;
      $redirect_uri=$redirect_uri?$redirect_uri:"/app/facebook/log";
      $arbitrary=$arbitrary?$arbitrary:md5($this->get("username"));
      $scope=$scope?$scope:"email";
        $url="http://www.facebook.com/dialog/oauth?client_id=YOUR_APP_ID&redirect_uri=YOUR_REDIRECT_URI&state=SOME_ARBITRARY_BUT_UNIQUE_STRING&scope=SCOPE";
        $p=array(
          "YOUR_APP_ID"=>$app_id,
          "YOUR_REDIRECT_URI"=>urlencode($config->paths["urlbase"].$redirect_uri),
          "SOME_ARBITRARY_BUT_UNIQUE_STRING"=>$arbitrary,
          "SCOPE"=>$scope
         );
        $url=str_replace(array_keys($p),$p,$url);
      return $url;
       * 
       */
  }
  public function get($path,$token,$parsed=false){
      /*
      $url="https://graph.facebook.com/".trim($path,"/")."?access_token=".$token;
   // $url=str_replace(array_keys($p),$p,$url);
    
    //echo $url;
    
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'facebook-php-3.1');

    $respuesta=curl_exec($ch);
    curl_close($ch);
    if($parsed){
        if($respuesta_json=json_decode($respuesta)){
            return $respuesta_json;
        }
        $respuestap=explode("&",$respuesta);
        $code=array();
        foreach($respuestap as $r){
            $r=explode("=",$r);
            $code[$r[0]]=$r[1];
        }
        return (object)$code;
    }
    return $respuesta;
       * 
       */
  }
  public function getAppAccessToken(){
   /*
      global $config;
      $url="https://graph.facebook.com/oauth/access_token?client_id=YOUR_APP_ID&client_secret=YOUR_APP_SECRET&grant_type=client_credentials";
      $p=array(
      "YOUR_APP_ID"=>$config->defaults["facebook"]->app_id,
      "YOUR_APP_SECRET"=>$config->defaults["facebook"]->app_secret
      );
    $url=str_replace(array_keys($p),$p,$url);
    //echo $url;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'facebook-php-3.1');

    $respuesta=curl_exec($ch);
    curl_close($ch);
    $respuestap=explode("&",$respuesta);
    $code=array();
    foreach($respuestap as $r){
	$r=explode("=",$r);
	$code[$r[0]]=$r[1];
    }
    if($code["access_token"]){
        return $code["access_token"];
    }
    * 
    */
  }
  function exchangeCode($code,$app_id=false,$app_secret=false,$redirect_uri=false){
     /*
      global $config;
      $app_id=$app_id?$app_id:$config->defaults["facebook"]->app_id;
      $redirect_uri=$redirect_uri?$redirect_uri:"/app/facebook/log";
      $app_secret=$app_secret?$app_secret:$config->defaults["facebook"]->app_secret;
      $p=array(
      "YOUR_APP_ID"=>$app_id,
      "YOUR_REDIRECT_URI"=>urlencode($config->paths["urlbase"].$redirect_uri),
      "YOUR_APP_SECRET"=>$app_secret,
      "CODE_GENERATED_BY_FACEBOOK"=>urlencode($code)
      );
    $url="https://graph.facebook.com/oauth/access_token?client_id=YOUR_APP_ID&redirect_uri=YOUR_REDIRECT_URI&client_secret=YOUR_APP_SECRET&code=CODE_GENERATED_BY_FACEBOOK";
    $url=str_replace(array_keys($p),$p,$url);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'e-spacios.com Facebook Api');

    $respuesta=curl_exec($ch);
    curl_close($ch);
    if($respuesta_json=json_decode($respuesta)){
        return $respuesta_json;
    }
    $respuestap=explode("&",$respuesta);
    $code=array();
    foreach($respuestap as $r){
	$r=explode("=",$r);
	$code[$r[0]]=$r[1];
    }
    return (object)$code;
      * 
      */
  }
  function submitCode($code){
      /*
    global  $core, $user,$document;
    
    $code=(array)$this->exchangeCode($code);
    if($code["access_token"]){
      if($user->logged){
	$user->submitToken($code);
	}
      else {
	if($user->loginFacebook($code)){
		if(array_key_exists("actualURL", $_COOKIE)){
			header("location:".$_COOKIE["actualURL"]);
		}
		else{
	    header("location:/");
			}
	  exit;
	  }
	else {
	$user->fb_token=$code["access_token"];
        
	$document=$core->getDocument("connectFacebook.html");
	}
      }
    }
    else {
      $respuestap=json_decode($respuesta);
      $core->addAlert('$$facebook_connectError$$: '.$respuestap->error->message);
		if(array_key_exists("actualURL", $_COOKIE)){
			header("location:".$_COOKIE["actualURL"]);
		}
		else{
	    header("location:/");
			}
      exit;
    }
       * 
       */
  }
  function publisherStoreParam($param,$val){
      /*
      global $core;
      $db=&$core->getDB(0,2);
      $q="update `fb_publisher` set `val`='".$db->real_escape_string($val)."' where `param`='".$db->real_escape_string($param)."'";
      $db->query($q);
      if($db->affected_rows<1){
          $q="insert into `fb_publisher`(`val`,`param`) values ('".$db->real_escape_string($val)."','".$db->real_escape_string($param)."')";
          $db->query($q);
      }
      return $db->affected_rows;
      
       * 
       */
  }
  function publisher_getParam($param){
      /*
      global $core;
      $db=&$core->getDB(0,2);
      $q="select `val` from `fb_publisher` where `param`='".$db->real_escape_string($param)."' limit 1";
      if($r=$db->query($q)){
          if($i=$r->fetch_assoc()){
              return $i["val"];
          }
      }
       * 
       */
  }
  function publisherPostInmueble(inmueble $inmueble,$lang=false,$prefixMessage='#espacios $$new_property$$'){
      /*
      global $user,$config,$core;
      $lang=$lang?$lang:$user->get("languaje");
      $lexicon=$core->getLexicon();
      $urlShorter=$core->getApp("urlShorter");
      if(($page=$this->publisher_getParam("pageID".$lang))){
          $token=$this->publisher_getParam("pageToken_".$lang);
      }
      else {
          $page=$this->publisher_getParam("pageIDen");
          $token=$this->publisher_getParam("pageToken_en");
          $lang="en";
      }
          $page=$this->publisher_getParam("pageID".$lang);
          //$page2=$this->publisher_getParam("pageIDes");
      $image=$inmueble->getImage(0,1,0);
      if(file_exists($image[0]->path)){
          $image=$image[0]->path;
          //$image="galeria/imagenes/sinimagen.jpg";
      }
      else {
          $image="galeria/imagenes/sinimagen.jpg";
      }
      $arguments=array(
          "message"=>$lexicon->traduce($prefixMessage),
          // "thumbnail"=>"@".$config->paths["base"].$image,
           "picture"=>(str_replace(".jpg.jpg",".jpg",$config->paths["urlbase"]."/cache/100/115/".$image.".jpg")),
          "link"=>($lexicon->traduce($inmueble->getURL($lang))),
          //"name"=>$inmueble->get("titulo"),
          //"caption"=>"http://www.e-spacios.com",
          //"description"=>$inmueble->get("descripcion"),
          //"source"=>$config->paths["urlbase"]
      );
      if($user->get("fb_id")){
         // $arguments["place"]=$lexicon->traduce($inmueble->getURL($lang));
         // $arguments["tags"]=$user->get("fb_id");
      }
      //echo "sdfsd";
      //print_r($arguments);
       $this->post("/".$page."/feed",$token,$arguments);
       //$this->post("/".$page2."/feed",$token,$arguments);
       * 
       */
  }
  function post($path,$token,$arguments=array()){
      /*
     // print_r($arguments);
        $url="https://graph.facebook.com/".trim($path,"/");
        $arguments["access_token"]=$token;
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'e-spacios.com Facebook Api');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
    $result = curl_exec($ch);
   // echo $result;
    return ( $result);
       * 
       */
  }
}
