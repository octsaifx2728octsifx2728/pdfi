<?php

include_once 'config/paypal.config.php';
class paypal_app{
	var $url="https://api-3t.paypal.com/nvp";
	var $followurl="https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=#TOKEN#";
    //var $followurl="https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=#TOKEN#"; //Cuenta de prueba
	//var $url="https://api-3t.sandbox.paypal.com/nvp"; //Cuenta de prueba
	function setRecurrentCheckout(factura $factura){
	  	global $config,$core,$result;
		    $db=&$core->getDB();
		    $con=&$db->getConexion();
	    	$lexicon= $core->getLexicon();
			$core->loadClass("producto");
	  	$POST=array(
	  		"USER"=>$config->paypal["USER"],
	  		"PWD"=>$config->paypal["PWD"],
	  		"SIGNATURE"=>$config->paypal["SIGNATURE"],
	  		"VERSION"=>$config->paypal["VERSION"],
	  		"METHOD"=>"SetExpressCheckout",
	  		"L_BILLINGTYPE0"=>"RecurringPayments",
	  		"L_BILLINGAGREEMENTDESCRIPTION0"=>"e-spacios.com: ",
	  		"CANCELURL"=>$config->paypal["CANCELURL"],
	  		"RETURNURL"=>$config->paypal["RETURNURL"],
	  		
			"REQCONFIRMSHIPPING"=>$config->paypal["REQCONFIRMSHIPPING"],
			"NOSHIPPING"=>$config->paypal["NOSHIPPING"],
			"ALLOWNOTE"=>$config->paypal["ALLOWNOTE"],
			"HDRIMG"=>$config->paypal["HDRIMG"],
			"SOLUTIONTYPE"=>$config->paypal["SOLUTIONTYPE"],
			"LANDINGPAGE"=>$config->paypal["LANDINGPAGE"],
			"CHANNELTYPE"=>$config->paypal["CHANNELTYPE"],
			"BRANDNAME"=>$config->paypal["BRANDNAME"],
			"CUSTOMERSERVICENUMBER"=>$config->paypal["CUSTOMERSERVICENUMBER"],
			"BUYEREMAILOPTINENABLE"=>$config->paypal["BUYEREMAILOPTINENABLE"],
			$POST["MAXAMT"]=>0
			);
		
		$items=$factura->getComandas();	
		foreach($items as $i){
			$producto=new producto($i["pago"]);
			$POST["L_BILLINGAGREEMENTDESCRIPTION0"].=$lexicon->traduce($producto->get("nombre"))." USD$".$producto->get("precio")."/".$producto->get("dias").$lexicon->traduce(' $$dias$$');
			$POST["MAXAMT"]=$POST["MAXAMT"]+$producto->get("precio");
		}
			
		$respuesta=$this->request($POST);
                //print_r($respuesta);
                //exit;
		if($respuesta["ACK"]=="Success"){
			$factura->setToken($respuesta["TOKEN"]);
			$result["follow"]=str_replace("#TOKEN#",$respuesta["TOKEN"],$this->followurl);
			return $respuesta["TOKEN"];
			
			}
		else {
			$factura->setError($respuesta["L_LONGMESSAGE0"]);
			return $respuesta["L_LONGMESSAGE0"];
		}
		
	}
	function SetExpressCheckout(factura $factura){
		global $core,$user,$config,$result;
		if($core->lang=="es"){
			$lcode="es_MX";
		}
		else {
			$lcode=strtoupper($core->lang);
		}
		$POST=$config->paypal;
		$POST["METHOD"]="SetExpressCheckout";
		$POST["LOCALECODE"]=$lcode;
		$items=$factura->getComandas();
		$total=$factura->getTotal();
		$POST["PAYMENTREQUEST_0_AMT"]=$total;
		$POST["PAYMENTREQUEST_0_CURRENCYCODE"]="USD";
		$POST["PAYMENTREQUEST_0_ITEMAMT"]=$total;
		$POST["PAYMENTREQUEST_0_DESC"]="e-spacios.com";
		$POST["PAYMENTREQUEST_0_INVNUM"]=$factura->id;
		$POST["PAYMENTREQUEST_0_PAYMENTACTION"]="sale";
		$x=0;
    	$lexicon= $core->getLexicon();
		foreach($items as $i){
			$POST["L_PAYMENTREQUEST_0_NAME".strval($x)]=$lexicon->traduce($i["nombre"]);
			$POST["L_PAYMENTREQUEST_0_DESC".strval($x)]=$lexicon->traduce($i["descripcion"]);
			$POST["L_PAYMENTREQUEST_0_AMT".strval($x)]=$i["monto"];
			$POST["L_PAYMENTREQUEST_0_NUMBER".strval($x)]=$i["id"];
			$POST["L_PAYMENTREQUEST_0_QTY".strval($x)]=$i["cantidad"];
			$POST["L_PAYMENTREQUEST_0_ITEMCATEGORY".strval($x)]="Digital";
			$x++;
		}
		$respuesta=$this->request($POST);
		//print_r($respuesta);
	if($respuesta["ACK"]=="Success"){
		$factura->setToken($respuesta["TOKEN"]);
		$result["follow"]=str_replace("#TOKEN#",$respuesta["TOKEN"],$this->followurl);
		return $respuesta["TOKEN"];
		
		}
	else {
		$factura->setError($respuesta["L_LONGMESSAGE0"]);
		return $respuesta["L_LONGMESSAGE0"];
	}
	}
  function parseRespuesta($respuesta){
  	$respuesta=urldecode($respuesta);
	$respuesta=explode("&",$respuesta);
	$re=array();
	foreach ($respuesta as $r) {
		$r=explode("=",$r,2);
		$re[$r[0]]=$r[1];
	}
	return $re;
  }
  function request($POST){
  	
		$po=array();
		foreach(array_keys($POST) as $k){
			$po[]=$k."=".$POST[$k];
		}
		
    $ch = curl_init($this->url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_USERAGENT, 'paypal-pay-php-3.1');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$po));
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);


    $respuesta=curl_exec($ch);
	$respuesta=$this->parseRespuesta($respuesta);
	return $respuesta;
  }
  function submitToken($token,$payerid){
  	global $config,$core;
	    $db=&$core->getDB(0,2);
  	$POST=array(
  		"USER"=>$config->paypal["USER"],
  		"PWD"=>$config->paypal["PWD"],
  		"SIGNATURE"=>$config->paypal["SIGNATURE"],
  		"VERSION"=>$config->paypal["VERSION"],
  		"TOKEN"=>$token,
  		"METHOD"=>"GetExpressCheckoutDetails"
		);
		$respuesta=$this->request($POST);
                //print_r($respuesta); 
               // exit;
		if($respuesta["ACK"]=="Success"){
			$q="select `id` from `facturas` where `token`='".mysql_real_escape_string($token)."' limit 1";
			$r=mysql_query($q);
			if(mysql_num_rows($r)){
				include_once $config->paths["core/class"].'factura.class.php';
				$i=mysql_fetch_array($r,MYSQL_ASSOC);
				$factura=new factura($i["id"]);
                              //  print_r($respuesta);
                                
                               // exit;
				if($factura->getTotal()==$respuesta["AMT"]){
					
					return $this->requestPayment($token,$respuesta["PAYERID"],$respuesta["AMT"],$factura);
				}
				elseif($factura->isRecurrent()){
					return $this->setRecurringPayment($respuesta,$factura,$token);
				}
			}
			else{
			}
		}
  }
  function setRecurringPayment($data,factura $factura,$token){
  	global $config,$core,$user;
	$core->loadClass("producto");
	$lexicon= $core->getLexicon();
  	$POST=array(
	  		"USER"=>$config->paypal["USER"],
	  		"PWD"=>$config->paypal["PWD"],
	  		"SIGNATURE"=>$config->paypal["SIGNATURE"],
	  		"VERSION"=>$config->paypal["VERSION"],
	  		"METHOD"=>"CreateRecurringPaymentsProfile",
  			"PAYERID"=>$data["PAYERID"],
  			"TOKEN"=>$token,
  			
	  		"PROFILESTARTDATE"=>date("c"),
	  		"PROFILEREFERENCE"=>$factura->id,
	  		"DESC"=>"e-spacios.com: ",
	  		"BILLINGPERIOD"=>"Day",
	  		"BILLINGFREQUENCY"=>0,
	  		"AMT"=>$factura->getTotal(),
	  		"CURRENCYCODE"=>$data["CURRENCYCODE"],
	  		"COUNTRYCODE"=>$data["COUNTRYCODE"],
	  		"MAXFAILEDPAYMENTS"=>1,
	  		"AUTOBILLOUTAMT"=>"NoAutoBill",
	  		"FAILEDINITAMTACTION"=>"CancelOnFailure",
	  		"EMAIL"=>$user->get("usuario"),
	  		"PAYERSTATUS"=>$data["PAYERSTATUS"]
	  		);
			
			
		$items=$factura->getComandas();	
		$x=0;
		foreach($items as $i){
			$producto=new producto($i["pago"]);
			$POST["DESC"].=$lexicon->traduce($producto->get("nombre"))." USD$".$producto->get("precio")."/".$producto->get("dias").$lexicon->traduce(' $$dias$$');
			$POST["BILLINGFREQUENCY"]=$POST["BILLINGFREQUENCY"]>$producto->get("dias")?$POST["BILLINGFREQUENCY"]:$producto->get("dias");
			$POST["L_PAYMENTREQUEST_n_ITEMCATEGORY$x"]="Digital";
			$POST["L_PAYMENTREQUEST_n_NAME$x"]=$lexicon->traduce($producto->get("nombre"));
			$POST["L_PAYMENTREQUEST_n_DESC$x"]=$lexicon->traduce($producto->get("descripcion"));
			$POST["L_PAYMENTREQUEST_n_AMT$x"]=$producto->get("precio");
			$POST["L_PAYMENTREQUEST_n_NUMBER$x"]=$x+1;
			$POST["L_PAYMENTREQUEST_n_QTY$x"]=$i["cantidad"];
			$x++;
		}
		
		$respuesta=$this->request($POST);
		if($respuesta["PROFILEID"]){
			$factura->initRecurrente($respuesta,"paypal",$POST["BILLINGFREQUENCY"]);
			return true;
		}
		else {
			
			$factura->setError($respuesta["L_LONGMESSAGE0"]);
		}
  }
  function requestPayment($token,$payerid,$monto,factura $factura){
  	
  	global $config,$core;
  	$POST=array(
  		"USER"=>$config->paypal["USER"],
  		"PWD"=>$config->paypal["PWD"],
  		"SIGNATURE"=>$config->paypal["SIGNATURE"],
  		"VERSION"=>$config->paypal["VERSION"],
  		"TOKEN"=>$token,
  		"PAYMENTREQUEST_0_PAYMENTACTION"=>"Sale",
  		"PAYERID"=>$payerid,
  		"PAYMENTREQUEST_0_AMT"=>$monto,
  		"METHOD"=>"DoExpressCheckoutPayment"
		);
		$respuesta=$this->request($POST);
                //print_r($respuesta);
                //exit;
		if($respuesta["ACK"]=="Success"){
			$factura->pagar($respuesta);
			return true;
		}
		else {
			
					$factura->setError($respuesta["L_LONGMESSAGE0"]);
		}
  }
}
