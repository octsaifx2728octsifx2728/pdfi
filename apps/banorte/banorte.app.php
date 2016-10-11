<?php
include_once 'config/banorte.config.php';
include_once $config->paths["core/class"].'factura.class.php';

class banorte_app{
	var $_soap="https://eps.banorte.com/recibo";
	var $followurl="/app/procesarpago/#TOKEN#";
	var $followurl2="/api/banorte/ipn";
	public function banorte_app(){
            global $config;
            $this->followurl=$config->paths["surlbase"].$this->followurl;
            $this->followurl2=$config->paths["surlbase"].$this->followurl2;
        }
        public function checkout(&$variables,factura $factura){
            global $config,$core;
                $core->loadClass("user");
                $currencyconverter=$core->getApp("currencyconverter");
                $user=new user($factura->get("cliente"));
		$POST["Name"]=$config->banorte["Name"];
		$POST["Password"]=$config->banorte["Password"];
		$POST["ClientId"]=$config->banorte["ClientId"];
		$POST["Mode"]=$config->banorte["Mode"];
		$POST["Cvv2Indicator"]=$config->banorte["Cvv2Indicator"];
		$POST["ResponsePath"]=$this->followurl2;
		$POST["Currency"]=$config->banorte["Currency"];
		$POST["TransType"]="Auth";
                
		$POST["Number"]=$variables["Number"];
		$POST["Expires"]=$variables["Expires"];
		$POST["Cvv2Val"]=$variables["Cvv2Val"];
		$POST["Total"]=  number_format($currencyconverter->directConvert($factura->getTotal(),"USD"),2,".","");
		$POST["SubTotal"]=number_format($currencyconverter->directConvert($factura->getTotal(),"USD"),2,".","");
		$POST["E1"]=$factura->getToken();
		$POST["PoNumber"]=$factura->id;
		$POST["UserId"]=$user->id;
                
                $lexicon=$core->getLexicon();
                
		$items=$factura->getComandas();
		$x=1;
		foreach($items as $i){
			$POST["ChargeDesc".$x]=  urlencode(trim($lexicon->traduce($i["nombre"])));
			$x++;
		}
		
		if(trim($variables["nombre"])){
			$POST["BillToFirstName"]=$variables["nombre"];
			$user->set("firstName",$variables["nombre"]);
		}
		if(trim($variables["apellidos"])){
			$POST["BillToLastName"]=$variables["apellidos"];
			$user->set("lastName",$variables["apellidos"]);
		}
		if(trim($variables["compania"])){
			$POST["BillToCompany"]=$variables["compania"];
			$user->set("bussinessName",$variables["compania"]);
		}
		if(trim($variables["telefono"])){
			$POST["BillToTelVoice"]=$variables["telefono"];
			$user->set("telefono",$variables["telefono"]);
		}
		if(trim($variables["fax"])){
			$POST["BillToFax"]=$variables["fax"];
			$user->set("fax",$variables["fax"]);
		}
		if(trim($variables["direccion1"])){
			$POST["BillToStreet2"]=$variables["direccion1"];
			$user->set("street",$variables["direccion1"]);
		}
		if(trim($variables["direccion2"])){
			$POST["BillToStreet3"]=$variables["direccion2"];
			$user->set("street2",$variables["direccion2"]);
		}
		if(trim($variables["pais"])){
			$POST["BillToCity"]=$variables["pais"];
			$user->set("pais",$variables["pais"]);
		}
		if(trim($variables["estado"])){
			$POST["BillToState"]=$variables["estado"];
			$user->set("estado",$variables["estado"]);
		}
		if(trim($variables["ciudad"])){
			$POST["BillToCountry"]=$variables["ciudad"];
			$user->set("city",$variables["ciudad"]);
		}
		if(trim($variables["nacimiento"])){
			$POST["BirthDate"]=date("m/d/Y",strtotime($variables["nacimiento"]));
			$user->set("nacimiento",$variables["nacimiento"]);
		}
		if(trim($variables["email"])){
			$POST["Email"]=$variables["email"];
		}
		if(trim($variables["rfc"])){
			$POST["FedTaxId"]=$variables["rfc"];
			$user->set("rfc",$variables["rfc"]);
		}
		if(trim($variables["comentarios"])){
			$POST["Comments"]=$variables["comentarios"];
		}
		$respuesta=$this->conecta($POST);
		$respuesta=$this->parseRespuesta($respuesta);
                   // print_r($respuesta);
		if($respuesta->CcErrCode==1){
                    $factura->pagar((array)$respuesta);
                    return true;
                }
                else{
                    $variables["Text"]=$respuesta->CcReturnMsg;
                    return false;
                }
        }
	function getToken($factura){
		global $core,$user,$config,$result;
		
		$token=md5("e-spacios".$user->id.microtime());
		
		$factura->setToken($token);
                
                
		$POST["Name"]=$config->paypal["Name"];
		$POST["Password"]=$config->paypal["Password"];
		$POST["ClientId"]=$config->paypal["ClientId"];
		$POST["Mode"]=$config->paypal["Mode"];
		$POST["Cvv2Indicator"]=$config->paypal["Cvv2Indicator"];
		$POST["ResponsePath"]=$config->paypal["ResponsePath"];
		$POST["Currency"]=$config->paypal["Currency"];
		$POST["TransType"]="Auth";
		$POST["Number"]=$variables["tarjeta"];
		$POST["expires"]=$variables["exp"];
		$POST["Cvv2Val"]=$variables["ccv"];
		$POST["Total"]=$factura->getTotal();
		$POST["SubTotal"]=$factura->getTotal();
		$POST["E1"]=$factura->getToken();
		$POST["PoNumber"]=$factura->id;
		$POST["UserId"]=$user->id;
                
                
		$result["follow"]=str_replace("#TOKEN#",$token,$this->followurl);
	}
	function pagar($variables ){
		global $config,$user,$core,$result;
		$lexicon=$core->getLexicon();
		$factura=new factura($variables["factura"]);
		$POST=array();
		$POST["Name"]=$config->paypal["Name"];
		$POST["Password"]=$config->paypal["Password"];
		$POST["ClientId"]=$config->paypal["ClientId"];
		$POST["Mode"]=$config->paypal["Mode"];
		$POST["Cvv2Indicator"]=$config->paypal["Cvv2Indicator"];
		$POST["ResponsePath"]=$config->paypal["ResponsePath"];
		$POST["Currency"]=$config->paypal["Currency"];
		$POST["TransType"]="Auth";
		$POST["Number"]=$variables["tarjeta"];
		$POST["expires"]=$variables["exp"];
		$POST["Cvv2Val"]=$variables["ccv"];
		$POST["Total"]=$factura->getTotal();
		$POST["SubTotal"]=$factura->getTotal();
		$POST["E1"]=$factura->getToken();
		$POST["PoNumber"]=$factura->id;
		$POST["UserId"]=$user->id;
		
		$items=$factura->getComandas();
		$x=1;
		foreach($items as $i){
			$POST["ChargeDesc".$x]=$lexicon->traduce($i["nombre"]);
			$x++;
		}
		
		if(trim($variables["nombre"])){
			$POST["BillToFirstName"]=$variables["nombre"];
			$user->set("firstName",$variables["nombre"]);
		}
		if(trim($variables["apellidos"])){
			$POST["BillToLastName"]=$variables["apellidos"];
			$user->set("lastName",$variables["apellidos"]);
		}
		if(trim($variables["compania"])){
			$POST["BillToCompany"]=$variables["compania"];
			$user->set("bussinessName",$variables["compania"]);
		}
		if(trim($variables["telefono"])){
			$POST["BillToTelVoice"]=$variables["telefono"];
			$user->set("telefono",$variables["telefono"]);
		}
		if(trim($variables["fax"])){
			$POST["BillToFax"]=$variables["fax"];
			$user->set("fax",$variables["fax"]);
		}
		if(trim($variables["direccion1"])){
			$POST["BillToStreet2"]=$variables["direccion1"];
			$user->set("street",$variables["direccion1"]);
		}
		if(trim($variables["direccion2"])){
			$POST["BillToStreet3"]=$variables["direccion2"];
			$user->set("street2",$variables["direccion2"]);
		}
		if(trim($variables["pais"])){
			$POST["BillToCity"]=$variables["pais"];
			$user->set("pais",$variables["pais"]);
		}
		if(trim($variables["estado"])){
			$POST["BillToState"]=$variables["estado"];
			$user->set("estado",$variables["estado"]);
		}
		if(trim($variables["ciudad"])){
			$POST["BillToCountry"]=$variables["ciudad"];
			$user->set("city",$variables["ciudad"]);
		}
		if(trim($variables["nacimiento"])){
			$POST["BirthDate"]=date("m/d/Y",strtotime($variables["nacimiento"]));
			$user->set("nacimiento",$variables["nacimiento"]);
		}
		if(trim($variables["email"])){
			$POST["Email"]=$variables["email"];
		}
		if(trim($variables["rfc"])){
			$POST["FedTaxId"]=$variables["rfc"];
			$user->set("rfc",$variables["rfc"]);
		}
		if(trim($variables["comentarios"])){
			$POST["Comments"]=$variables["comentarios"];
		}
		
		echo "VARIABLES DE ENVIO:<hr>";
		
		print_r($POST);
		$respuesta=$this->conecta($POST);
		echo "<hr> RESPUESTA <hr>";
		echo $respuesta;
		
		$result["action"]="wait";
		
		$result["token"]=$factura->getToken();
		
		return true;
	}
	function conecta($POST,$url=false){
		$po=array();
		foreach(array_keys($POST) as $k){
			$po[]=$k."=".utf8_encode($POST[$k]);
		}
	$url=$url?$url:$this->_soap;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_USERAGENT, 'banorte-paynalton-php-3.1');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$po));
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    $respuesta=curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close( $ch );
	
	$headers = explode("\n",$respuesta);
	
	foreach($headers as $h){
		
		if(strpos($h,"Location:") !== false){
        $url = trim(str_replace("Location:","",$h));
        $respuesta=$this->conecta($POST,$url);
        break;
    }
	}
	
	return $respuesta;
	}
  function parseRespuesta($respuesta){
      $respuesta=explode("\n",$respuesta);
      $respuesta=$respuesta[count($respuesta)-1];
      $respuesta=  json_decode(rtrim(ltrim($respuesta,"("),",'');"));
      
	return $respuesta->respuesta;
  }
}
