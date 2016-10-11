<?php
/*
echo "yaa1a";
$tapp = new twitter_app;
echo $tapp->shortenURL("http://google.com");*/
class twitter_app{
    public function publisherPostInmueble(inmueble $inmueble=null){
        /*
        $titulo = $inmueble->get("titulo");
        //$descripcion = substr($descripcion, 0,50);
        if(strlen($titulo)>90){
            $titulo = substr($titulo,0,87)."...";
        }
        
        //$descripcion = $descripcion["descripcion"];
        $url= $this->shortenURL($inmueble->getURL(false));
        $status = "#espacios New Property: $titulo $url";
        
        $time = time();
        $nonce=$this->randomStr(10);
        $parameters=array(
            "status"=> $status,
            "oauth_consumer_key"=>"RxgnCpBnYTiV3OmThaz2Xw",
            "oauth_nonce"=>$nonce,
            "oauth_signature_method"=>"HMAC-SHA1",
            "oauth_timestamp"=>$time,
            "oauth_token"=>"408827027-fJBEZOSxq0kFEZ7jiq8l5VWg1gmgRtNxAucpqQwx",
            "oauth_version"=>"1.0"
            );
        
        $baseURL = "https://api.twitter.com/1.1/statuses/update.json";
        //$baseURL = "https://api.twitter.com/1.1/help/configuration.json";
        //$status = e
        
        $signature = $this->generateSignature("POST",$baseURL,$parameters);
        $parameters['oauth_signature']=$signature;
        //echo $signature;
        unset($parameters['status']);
        //unset($parameters['include_identities']);
        
        $authHeaderString=$this->generateAuthorizationHeaderString($parameters);

        $headers = array(
            'Content-type: application/x-www-form-urlencoded', 
            "Authorization: $authHeaderString"
        );

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, "status=".  rawurlencode($status));
        curl_setopt($ch, CURLOPT_URL, $baseURL);
        //echo $headers[1];
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //echo $headers[1];
        //curl_setopt($ch, CURLOPT_POST, count($parameters));
        //curl_setopt($ch,CURLOPT_POSTFIELDS, $headerString);
        $result = curl_exec($ch);
        curl_close($ch);
        
    }
    private function shortenURL($url){
        $resource = "https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyCl8QC-B8y6AanXpA-a9Lm8m6JSyU240bA";
        $headers = array('Content-Type: application/json','X-JavaScript-User-Agent:  Google APIs Explorer');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode(array('longUrl'=>$url)));
        curl_setopt($ch, CURLOPT_URL, $resource);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        return $result->{'id'};
         * 
         */
    }
    private function generateAuthorizationHeaderString($params){
        //ksort($params);
        $dst = "OAuth ";
        $size = count($params);
        $c=0;
        foreach ($params as $key => $value) {
            if($c==$size-1){
                $dst.=rawurlencode($key)."=".'"'.rawurlencode($value).'"';
            }else{
                $dst.=rawurlencode($key)."=".'"'.rawurlencode($value).'"'.", ";
            }
            $c++;
        }
        return $dst;
    }
    private function generateSignature($httpMethod="POST",$baseURL,$params){
        ksort($params);
        $paramStr="";
        $size = count($params);
        $c=0;
        foreach ($params as $key => $value) {
            if($c==$size-1){
                $paramStr.=rawurlencode($key)."=".rawurlencode($value);
            }else{
                $paramStr.=rawurlencode($key)."=".rawurlencode($value)."&";
            }            
            $c++;
        }
        $signatureBaseString = strtoupper($httpMethod)."&".rawurlencode($baseURL)."&".rawurlencode($paramStr);
        $consumerSecret = "wDsIqu49EJwrIM22CLp0yx7C544ek6T6jgykd0a2Hc";
        $tokenSecret = "MjLOMsg4rOZhAt7nJ958fiHM0JcuJCOV21jppAH87ZREI";
        $signingKey = rawurlencode($consumerSecret)."&".rawurlencode($tokenSecret);
        $oathSign = base64_encode(hash_hmac("SHA1", $signatureBaseString, $signingKey, true));
        return $oathSign;
        
    }
      private function randomStr($length=0){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chsize = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $chsize - 1)];
    }
    return $randomString;
  }
      public function get($path,$params){
      $url="http://api.twitter.com/".$path.".json";
      foreach($params as $k=>$v){
          $params[$k]=$k."=".urlencode($v);
      }
      $url.="?".implode("&",$params);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'facebook-php-3.1');

    $respuesta=curl_exec($ch);
    curl_close($ch);
    return $respuesta;
  }
    /*
    private $_oauthURL="https://api.twitter.com/oauth/authenticate";
    private $_requestToken="https://api.twitter.com/oauth/request_token";
  public function get($path,$params){
      $url="http://api.twitter.com/".$path.".json";
      foreach($params as $k=>$v){
          $params[$k]=$k."=".urlencode($v);
      }
      $url.="?".implode("&",$params);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'facebook-php-3.1');

    $respuesta=curl_exec($ch);
    curl_close($ch);
    return $respuesta;
  }

  public function getLoginLink($ON,$callback,$OCK,$OCS){
      global $core;
      $url=$this->_requestToken;
      $p=array(
          "oauth_nonce"=>'"'.$ON.'"',
          "oauth_callback"=>'"'.$callback.'"',
          "oauth_signature_method"=>'"'."HMAC-SHA1".'"',
          "oauth_timestamp"=>'"'.time().'"',
          "oauth_consumer_key"=>'"'.$OCK.'"',
          "oauth_version"=>'"'."1.0".'"'
      );
      $p["oauth_signature"]='"'.($this->_sign($p,$url,$OCS,"","POST")).'"';
      
      $req=array();
      
      foreach($p as $k=>$v){
          $req[]=$k."=".$v;
      }
      $header=array(
          "Authorization: ".implode(",",$req),
          'User-Agent: e-spacios twitter publisher 1.0'
      );
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);    
      
      $respuesta=curl_exec($ch);
        curl_close($ch);
      header("content-type:text/plain");
      print_r($url);
      print_r($respuesta);
  }
  private function _sign($params=array(),$url,$consumer_secret,$token_secret="",$method="GET"){
      
      $p=array();
      foreach($params as $k=>$v){
          $p[]=$k."=".$v;
      }
      $p=  strtoupper($method)."&".urlencode($url)."&".urlencode(implode("&",$p));
      $sk=$consumer_secret."&".$token_secret;
      
      $sign= base64_encode(hash_hmac("SHA1", $p, $sk, true));
      
      return($sign);
  }*/

}
