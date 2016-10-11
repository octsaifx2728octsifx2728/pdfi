<?php
class factura extends objeto{
	var $id;
	var $comandas=array();
        var $_tabla="facturas";
	public function factura($id){
		$this->id=$id;
	}
	public function addComanda($id_producto,inmueble $inmueble=null,$cantidad=1){
            global $core;
            $core->loadClass("user");
            $core->loadClass("producto");
            $user=new user($this->get("cliente"));
            $producto=new producto($id_producto);
		$q="select `precio`,`nombre`,`descripcion` 
			from `productos` 
			where `id`='".mysql_real_escape_string($id_producto)."' 
			limit 1";
		$r=mysql_query($q);
		if(mysql_num_rows($r)){
			$i=mysql_fetch_array($r,MYSQL_ASSOC);
			$monto=floatval($producto->getPrecio($user));
			$q="insert into `comandas`(`inmueble`,`pago`,`monto`,`fecha`,`factura`,`cantidad`,`nombre`,`descripcion`)
				values(
					'".($inmueble?mysql_real_escape_string($inmueble->id."-".$inmueble->get("id_cliente")."-".$inmueble->get("tipoobjeto")):"0")."',
					'".mysql_real_escape_string($id_producto)."',
					'".strval($monto)."',
					now(),
					'".mysql_real_escape_string($this->id)."',
					'".mysql_real_escape_string($cantidad)."',
					'".mysql_real_escape_string($i["nombre"])."',
					'".mysql_real_escape_string($i["descripcion"])."'
					)";
			mysql_query($q);
			$this->comandas[]=array(
				"inmueble"=>$inmueble,
				"pago"=>$id_producto,
				"monto"=>$monto,
				"cantidad"=>$cantidad
				);
			$this->calcTotal();
			return true;
			}
		else {
			return false;
		}
	}
	public function getUser(){
		$q="select `cliente` from `facturas` where  `id`='".mysql_real_escape_string($this->id)."' limit 1";
		$r=mysql_query($q);
		$i=mysql_fetch_array($r,MYSQL_ASSOC);
		$usuario=new user($i["cliente"]);
		return $usuario;
	}
	public function calcTotal(){
		$q="update `facturas` set `total`=(
				select SUM(`monto`*`cantidad`)
				from `comandas`
				where `factura`='".mysql_real_escape_string($this->id)."'
				group by `factura`
				)
			where `id`='".mysql_real_escape_string($this->id)."'";
		mysql_query($q);
	}
	public function getComandas(){
		$q="select * from `comandas` where `factura`='".mysql_real_escape_string($this->id)."'";
		$r=mysql_query($q);
		$this->comandas=array();
		while($i=mysql_fetch_array($r)){
			$this->comandas[]=$i;
		}
		return $this->comandas;
	}
	public function getTotal(){
		global $core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$q="select `total` from `facturas` where `id`='".mysql_real_escape_string($this->id)."' limit 1";
                
                 
                
		$r=mysql_query($q);
		$i=mysql_fetch_array($r);
		return $i["total"];
	}
	public function setToken($token){
		$q="update `facturas` set `token`='".mysql_real_escape_string($token)."' where `id`='".mysql_real_escape_string($this->id)."' limit 1";
		mysql_query($q);
	}
	public function pagar($data,$newAndPremium=false){
		global $config,$user,$core;
                //print_r($data);
                //exit;
                
            
             
                
	    $db=&$core->getDB(0,2);
                $core->loadClass("inmueble");
                $core->loadClass("producto");
                $manager=&$core->getApp("inmueblesManager");
		$comandas=$this->getComandas();
                
                
                
		foreach($comandas as $c){
			$id=explode("-",$c["inmueble"]);

                        
                        $usuario=new user($id[1]);
                        $inmueble=$manager->getInmueble($id[0],$id[2],$usuario);

                        if(!($inmueble == null)){
                            
                           
                        
                            
                            $producto=new producto($c["pago"]);

                            $producto->aplicar($inmueble);
                        }
                        
                }
                
                
		
		/*
		$q="update `facturas`
			set `pagado`='1',
			`token`='',
			 `fecha_pago`=now(),
			 `id_pago`='".intval($data["PAYMENTINFO_0_TRANSACTIONID"].$data["AuthCode"])."',
			 `pago_string`='".$db->real_escape_string(json_encode($data))."'
			where `id`='".intval($this->id)."' limit 1";
                 *
                 */
                $q="update `facturas`
			set `pagado`='1',
			`token`='',
			 `fecha_pago`=now(),
			 `id_pago`='"."12F60863PB467035R"."',
			 `pago_string`='".""."'
			where `id`='".intval($this->id)."' limit 1";
            $db->query($q);
            
             
            
            if(!$newAndPremium){
                
                 
             
            	$actividades=$core->getApp("actividades");
                
                
                
	  			$actividades->report($user,'$$has_published$$ : '.$inmueble->get("titulo"),$inmueble->getURL(),0,1,$inmueble->id."-",$inmueble->get("tipoobjeto"),$inmueble);
                                
                        
                                
          		$inmueble->reportToGeoURL();
            }

	}
	public function getToken(){
		global $core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
	    $q="select `token` from `facturas` where `id`='".mysql_real_escape_string($this->id)."' limit 1";
	    $r=mysql_query($q);
		$i=mysql_fetch_array($r);
		return $i["token"];
	}
	public function isRecurrent(){
		global $core;
		$core->loadClass("producto");
		$comandas=$this->getComandas();
		$recurrente=false;
		foreach($comandas as $c){
			$producto=new producto($c["pago"]);
			if($producto->get("recurrente")){
				$recurrente=true;
				break;
			}
		}
		return $recurrente;
	}
	public function initRecurrente($data,$proveedor,$dias){
		global $core,$user;
		$core->loadClass("producto");
		$comandas=$this->getComandas();
		
		foreach($comandas as $c){
			$producto=new producto($c["pago"]);
			$producto->adjudicar($user);
			}
		$q="update `facturas` 
				set  `pagado`='2',
				`token`='',
			 	`pago_string`='".mysql_real_escape_string(json_encode($data))."',
			 	`recurrente_id`='".mysql_real_escape_string($data["PROFILEID"])."'	,
			 	`recurrente_proveedor`='".mysql_real_escape_string($proveedor)."',
			 	`recurrente_ultimopago`=now(),
			 	`recurrente_dias`='".mysql_real_escape_string($dias)."'
			 	where `id`='".mysql_real_escape_string($this->id)."' limit 1";
		mysql_query($q);
		return true;
	}
	public function setError($error){
		$q="update `facturas` 
				set  `lasterror`='".mysql_real_escape_string(($error))."'
			 	where `id`='".mysql_real_escape_string($this->id)."' limit 1";
		mysql_query($q);
		return true;
	}
}