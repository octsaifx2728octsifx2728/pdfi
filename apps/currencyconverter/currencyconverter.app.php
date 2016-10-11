<?php
class currencyconverter_app{
  function directConvert($valor,$monedaorigen,$monedadestino="MXN"){
      $valor=floatval($valor);
      $monedaorigen2=floatval($this->getDivisa($monedaorigen));
      $monedadestino2=floatval($this->getDivisa($monedadestino));
      $total=floatval(1/$monedaorigen2)*$monedadestino2*$valor;
     // $total=(1/$monedaorigen2)*$monedadestino2*intval($valor);
      //echo "::: $valor $monedaorigen ( $monedaorigen2 ) => $total $monedadestino ( $monedadestino2 ) :::";
      return $total;
  }
  function convert($id,$moneda){
    global $config,$core;
    include_once 'config/currencyconverter.config.php';
    $core->loadClass("inmueble");
      $idp=explode("_",$id);
    $inmueble=new inmueble($idp[0],$idp[1]);
    if($moneda==$config->defaults["currency_default"]){
      $resultado=floatval($inmueble->get("precio"));
    }
    else {
      $divisa=($this->getDivisa($moneda));
      $precio=floatval($inmueble->get("precio"));
      $resultado=$precio*$divisa;
      }
    $r=array(
      "precio"=>number_format($resultado,2),
      "preciom2"=>number_format($inmueble->getPreciom2($moneda),2),
      "id"=>$id,
      "simbolo"=>$config->currencyconverter[$moneda]["simbolo"]
      );
    return $r;
    }
  function getDivisa($moneda){
    global $config,$core;

    $db=&$core->getDB();
    $con=&$db->getConexion();

    //$q="select `valor` from `currencyconverter` where `date` >= (NOW() - INTERVAL 1 DAY) and `moneda`='".mysql_real_escape_string($moneda)."'";
    $q = "select `valor` from `currencyconverter` where `moneda`='".mysql_real_escape_string($moneda)."'";
    $r=mysql_query($q);
    if($r&&mysql_num_rows($r)){
      $i=mysql_fetch_array($r,MYSQL_ASSOC);
      return floatval($i["valor"]);
    }
    else {
      $url="http://www.google.com/ig/calculator?hl=es&q=1#CURRENCY_DEFAULT#%3D%3F#CURRENCY#";
      $p=array(
	"#CURRENCY_DEFAULT#"=>$config->defaults["currency_default"],
	"#CURRENCY#"=>$moneda
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
      $respuesta=explode(",",trim($respuesta,"{}"));
      $resp=array();
      foreach($respuesta as $r){
	$r=explode(":",$r);
	$resp[$r[0]]=trim(utf8_encode($r[1]),'"');
      }
      $val=array();
      $valor=floatval(trim(preg_replace("/[a-z \"]/i","",$resp["rhs"]),". "));
      if($valor){
	$q="delete from `currencyconverter` where `moneda`='".mysql_real_escape_string($moneda)."'";
	mysql_query($q);
	$q="insert into `currencyconverter`(`moneda`,`valor`,`date`) values('".mysql_real_escape_string($moneda)."','".mysql_real_escape_string(($valor))."',NOW())";
	mysql_query($q);
	return floatval($valor);
      }
      else {
          return 0;
      }
    }
  }
}