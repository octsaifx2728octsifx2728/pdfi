<?php
class linkedin_app {
    private $_oauthLink="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=YOUR_API_KEY&scope=SCOPE&state=STATE&redirect_uri=YOUR_REDIRECT_URI";
                         
    private $_getToken="https://www.linkedin.com/uas/oauth2/accessToken";
    private $_getURL="https://api.linkedin.com/v1/PATH";
    function getLoginLink($state,$return,$apiKey=false,$scope=false){
        global $config;
        
        $apiKey=$apiKey?$apiKey:$config->defaults["linkedin"]->client_id;
        $scope=$scope?$scope:$config->defaults["linkedin"]->scope;
        $p=array(
            "YOUR_API_KEY"=>$apiKey,
            "SCOPE"=>urlencode($scope),
            "STATE"=>urlencode($state),
            "YOUR_REDIRECT_URI"=>urlencode($return)
        );
        $url=str_replace(array_keys($p),$p,$this->_oauthLink);
        
       
        
        
        //print_r($url);
       // exit;
        return $url;
    }
    function submitCode($code,$return,$apikey=false,$apisecret=false){
        $p=array(
            "grant_type"=>"authorization_code",
            "code"=>$code,
            "redirect_uri"=>urlencode($return)
        );
        //$url=str_replace(array_keys($p),$p,$this->_getToken);
        $r=$this->post($this->_getToken,$p,$apikey,$apisecret);
        return $r;
    }
    function get($path,$token,$params=array()){
        global $config;
        $params["oauth2_access_token"]=$token;
        $_params2=array();
        foreach($params as $k=>$v){
            $_params2[]=$k."=".$v;
        }
        $p=array(
            "YOUR_API_KEY"=>$config->defaults["linkedin"]->client_id,
            "YOUR_SECRET_KEY"=>$config->defaults["linkedin"]->client_secret,
            "ACCESS_TOKEN"=>$token,
            "PATH"=>trim($path,"/ ")."?".implode("&",$_params2)
        );
        $url=str_replace(array_keys($p),$p,$this->_getURL);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'pay-linkedin:e-spacios.com');

        $respuesta=(curl_exec($ch));
        curl_close($ch);
        $respuesta=  simplexml_load_string($respuesta);
        return $respuesta;
    }
    function post($url,$options=array(),$apikey=false,$apisecret=false){
        global $config;
        
        $options["client_id"]=$apikey?$apikey:$config->defaults["linkedin"]->client_id;
        $options["client_secret"]=$apisecret?$apisecret:$config->defaults["linkedin"]->client_secret;
        
        $p2=array();
        
        foreach($options as $k=>$v){
            $p2[]=$k."=".$v;
        }
        $p2=  implode("&", $p2);
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'pay-linkedin:e-spacios.com');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p2);

        $respuesta=json_decode(curl_exec($ch));
        
        curl_close($ch);
        return($respuesta);
    }
    function login($token,$expires=0){
        global $core,$user;
        
        $db=$core->getDB(0,2);
        $email=strval($this->get("people/~/email-address",trim($token)));
        
        $q="select `id_cliente` from `usuarios` 
                where `usuario` LIKE '".$db->real_escape_string($email)."'" 
                    .($token?"or `ln_token`='".$db->real_escape_string($token)."'":"")
                    ."limit 1";
        
        $logged=false;
       if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
                if($i["id_cliente"]){
                    $user=new user($i["id_cliente"]);
                    $user->forceLogin();
                    $logged=true;
                }
            }
            
        }
        if(!$logged&&$email){
            $perfil=$this->get("people/~",trim($token));
            foreach($perfil as $c){
                switch($c->getName()){
                    case "first-name":
                        $nombre=  strval($c);
                        break;
                    case "last-name":
                        $apellidos=  strval($c);
                        break;
                }
            }
            $datos=array(
                "email"=>$email,
                "password"=>md5($email.rand()),
                "screenName"=>$nombre." ".$apellidos
                    );
            
            $user->register($datos);
            if($user->id){
                $logged=true;
            }
        }
        if($token){
            $user->set("ln_expires",$expires+time());
            $user->set("ln_token",$token);
        }
        return $logged;
    }
    function getAToken(){
        global $core;
        $db=$core->getDB(0,2);
        $q="select `ln_token` from `usuarios` where `ln_expires`>'".time()."' limit 1";
        if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
                return $i["ln_token"];
            }
        }
    }
    
    
  function publisherStoreParam($param,$val){
      global $core;
      $db=&$core->getDB(0,2);
      $q="update `fb_publisher` set `val`='".$db->real_escape_string($val)."' where `param`='".$db->real_escape_string($param)."'";
      $db->query($q);
      if($db->affected_rows<1){
          $q="insert into `fb_publisher`(`val`,`param`) values ('".$db->real_escape_string($val)."','".$db->real_escape_string($param)."')";
          $db->query($q);
      }
      return $db->affected_rows;
      
  }
  
  function publisher_getParam($param){
      global $core;
      $db=&$core->getDB(0,2);
      $q="select `val` from `fb_publisher` where `param`='".$db->real_escape_string($param)."' limit 1";
      if($r=$db->query($q)){
          if($i=$r->fetch_assoc()){
              return $i["val"];
          }
      }
  }
  
  function publisherPostInmueble(inmueble $inmueble){
      global $core,$config;
      $lexicon=$core->getLexicon();
      $urlShorter=$core->getApp("urlShorter");
      $p=array(
            "PATH"=>"companies/".$this->publisher_getParam("ln_company_es")."/shares"
        );
      
        $url=str_replace(array_keys($p),$p,$this->_getURL)."?oauth2_access_token=".$this->publisher_getParam("ln_userToken");
        
        $data=  simplexml_load_string("<share></share>");
        $data->visibility->code="anyone";
        
        
      $image=$inmueble->getImage(0,1,0);
      if(file_exists($image[0]->path)){
          $image=$image[0]->path;
      }
      else {
          $image="galeria/imagenes/sinimagen.jpg";
      }
        
        $data->comment="#espacios ".$lexicon->traduce('$$new_property$$');
        $data->addChild("content");
        $data->content->addChild("submitted-url",$urlShorter->acortar($lexicon->traduce($inmueble->getURL($lang))));
        $data->content->addChild("title",$inmueble->get("titulo"));
        $data->content->addChild("description",$inmueble->get("descripcion"));
        $data->content->addChild("submitted-image-url",(str_replace(".jpg.jpg",".jpg",$config->paths["urlbase"]."/cache/180/110/".$image.".jpg")));
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data->saveXML());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
        $response = curl_exec($ch);
        
      $p=array(
            "PATH"=>"people/~/shares"
        );
      
        $url=str_replace(array_keys($p),$p,$this->_getURL)."?oauth2_access_token=".$this->publisher_getParam("ln_userToken");
        
        $data=  simplexml_load_string("<share></share>");
        $data->visibility->code="anyone";
        
        
      $image=$inmueble->getImage(0,1,0);
      if(file_exists($image[0]->path)){
          $image=$image[0]->path;
      }
      else {
          $image="galeria/imagenes/sinimagen.jpg";
      }
        
        $data->comment="#espacios ".$lexicon->traduce('$$new_property$$');
        $data->addChild("content");
        $data->content->addChild("submitted-url",$urlShorter->acortar($lexicon->traduce($inmueble->getURL($lang))));
        $data->content->addChild("title",$inmueble->get("titulo"));
        $data->content->addChild("description",$inmueble->get("descripcion"));
        $data->content->addChild("submitted-image-url",(str_replace(".jpg.jpg",".jpg",$config->paths["urlbase"]."/cache/180/110/".$image.".jpg")));
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data->saveXML());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
        $response2 = curl_exec($ch);
        //return simplexml_load_string($response);
        return $response2;
        //header("content-type:text/xml");
       //echo($response2);
        //echo $data->saveXML();
        //echo $url;
        
       // $r=$this->post($this->_getToken,$p,$apikey,$apisecret);
  }
}
