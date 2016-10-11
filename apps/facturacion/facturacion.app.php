<?php
define("PAYMENTMETHOD_PAYPAL",1);
define("PAYMENTMETHOD_BANORTE",2);
class facturacion_app{
	function getPrecio($id){
		$q="select `precio`,`descuento` from `productos` where `id`='".mysql_real_escape_string($id)."' limit 1";
		$r=mysql_query($q);
		$i=mysql_fetch_array($r,MYSQL_ASSOC);
		return $i["precio"]-$i["descuento"];
	}
	function getProduct($id){
		$q="select * from `productos` where `id`='".mysql_real_escape_string($id)."' limit 1";
		$r=mysql_query($q);
		$i=mysql_fetch_array($r,MYSQL_ASSOC);
		return $i;
	}
	function getProductsByBound($id){
		$q="select * from `productos` where `bound`='".mysql_real_escape_string($id)."'";
		$r=mysql_query($q);
		$resp=array();
		while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
			$resp[]=$i;
		}
		return $resp;
	}
	function crearFactura($params){
		global $core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$q="insert into `facturas`(`cliente`,`fecha`) values('".mysql_real_escape_string($params["cliente"]->id)."',now())";
		mysql_query($q);
		$id_factura=mysql_insert_id();
		$core->loadClass("factura");
		$factura=new factura($id_factura);
		foreach($params["productos"] as $prod){
			$factura->addComanda($prod);
		}
		return $factura;
	}
	function getPaymentToken(factura $factura,$paymentMethod=PAYMENTMETHOD_PAYPAL){
		global $core;
		
                switch($paymentMethod){
                    case PAYMENTMETHOD_PAYPAL:
                        $paypal=$core->getApp("paypal");
			return $paypal->setRecurrentCheckout($factura);
                        break;
                    case PAYMENTMETHOD_BANORTE:
			$banorte=$core->getApp("banorte");
			return $banorte->getToken($factura);
                        break;
                }
		
	}
}
