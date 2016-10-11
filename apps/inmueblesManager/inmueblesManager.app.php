<?php
define("INMUEBLE_ERROR_LOGINREQUIRED",1);
define("INMUEBLE_ERROR_INSERT",2);
define("INMUEBLE_ERROR_INSERT_FACTURA",3);
define("INMUEBLE_ERROR_PAYMENTMETHOD_UNKNOW",4);
define("INMUEBLE_BADTOKEN",5);
define("INMUEBLE_PAYMENTMETHOD_PAYPAL",1);
define("INMUEBLE_PAYMENTMETHOD_BANORTE",2);
class inmueblesManager_app{
    
    private $_prototipos=array(
        "residencial"=>"residencial_inmueble",
        "comercial"=>"comercial_inmueble",
        "oficina"=>"oficina_inmueble",
        "industrial"=>"industrial_inmueble",
        "terreno"=>"terreno_inmueble",
        "funerario"=>"funerario_inmueble"
        );
    public $_tipos=array(
        "residencial",
        "comercial",
        "oficina",
        "industrial",
        "terreno",
        "funerario"
        );
    public function purge(user $user){
        global $core;
	$db=&$core->getDB(0,2);
        $q="delete from `anuncios` where `user`='".intval($user->id)."' and `incompleto`='1'";
        $db->query($q);
    }
    public function getInmueble($id,$tipo,user $user=null){
        
        error_reporting(1);
        ini_set('display_errors', '1');
        
        
        global $core;
        $inm = NULL; 
        
        $core->loadClass($this->_tipos[$tipo-1]);

        if(class_exists($this->_prototipos[$this->_tipos[$tipo-1]])){
                    $clase=$this->_prototipos[$this->_tipos[$tipo-1]];
                    
                    
                    $inm= new $clase($id);
                    
                    
                    
                    $inm->campos['tipoobjeto'] = $tipo;
                    
                    
                    /*
                    if($user&&$user->id==$inm->get("id_cliente")){
                        
                        
                        
                       
                    }
                     * 
                     */
                    
                   
                    
                    }

                    
                 return $inm;
                
    }
    public function getIncomplete(user $user){
        global $core;
	$db=&$core->getDB(0,2);
        
        $q="select `idinmueble`, `tipoinmueble` from anuncios where `user`='".intval($user->id)."'
            and `incompleto`='1' limit 1";
        
       
        
        
        if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
           
                    
                
                    $inm1 = $this->getInmueble($i["idinmueble"],$i["tipoinmueble"],$user);
                    
                    
                   
                       
                    
                    //$inm1->$campos['tipoobjeto'] = $i["tipoinmueble"];
                    
                     
                    return $inm1;
                }
            }
        }
    function getPrototypes() {
        global $core;
        $prototipos=array();
        foreach($this->_prototipos as $k=>$v){
            $core->loadClass($k);
            $prototipos[$k]=new $v();
        }
        return $prototipos;
    }
	function getPaymentToken(inmueble $inmueble,$productos,$method=INMUEBLE_PAYMENTMETHOD_PAYPAL,$newAndPremium=false){
		global $user,$core,$config,$result;
                
                
                
                $core->loadClass("producto");
                $core->loadClass("factura");
                $db=&$core->getDB(0,2);
		$q="insert into `facturas`(`cliente`,`fecha`) values('".intval($user->id)."',now())";
                
		if($db->query($q)){
                    $id_factura=$db->insert_id;
                    if(!$id_factura) return INMUEBLE_ERROR_INSERT_FACTURA;
                    
                    
                    
                    $search=$core->getapp("search");
                    
                    
                     
                    
                    
                    $factura=new factura($id_factura);
                   
                    foreach($productos as $prod){
                            
                           
                        
                            $prod=new producto($prod);
                            $factura->addComanda($prod->id, $inmueble);
                    }
                }
                else {
                   return INMUEBLE_ERROR_INSERT_FACTURA; 
                }
		if(!$factura->getTotal()||$_GET["prueba"]){
                        
			if($user->getFreemiumAvaliable()>0){
                                
				$factura->pagar(array("Anuncio Gratuito"),$newAndPremium);
				
                                $result["paymentMessage"]='$$anuncio_gratis_success$$';
			}
			elseif($user->getBoundAvaliable()>0){
				$factura->pagar(array("Anuncio Gratuito"),$newAndPremium);
				$result["paymentMessage"]='$$anuncio_gratis_success2$$';
				
			}
			else {
                            $factura->pagar(array("a prueba"),$newAndPremium);
				$result["paymentMessage"]='$$anuncio_gratis_error$$';
			}
		}
		else{
			switch($method){
				case INMUEBLE_PAYMENTMETHOD_PAYPAL:
					$paypal=$core->getApp("paypal");
                                    if($prod->get("recurrente")){

					//$token=$paypal->setExpressCheckout($factura);
                                        $factura->pagar($respuesta);
                                        
                                        //$token=$paypal->setRecurrentCheckout($factura);
                                    }
                                    else {
                                        
                  
                                        $factura->pagar($respuesta);
                                        
                                        //$token=$paypal->setExpressCheckout($factura);
                                    }
					break;
				case INMUEBLE_PAYMENTMETHOD_BANORTE:
					$banorte=$core->getApp("banorte");
					$token=$banorte->getToken($factura);
					break;
				default:
				 	return INMUEBLE_ERROR_PAYMENTMETHOD_UNKNOW;
					break;
			}
		}
                
                
		return array("factura"=>$factura->id,"total"=>$factura->getTotal(),"token"=>$token);
	}
  function getFacturaByToken($token){
  	
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
  	global $config,$core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$q="select `id` 
			from `facturas` 
			where `token`='".mysql_real_escape_string($token)."' 
			limit 1";
			$res=mysql_query($q);
                        //echo $q;
			if(mysql_num_rows($res)){
			
    			include_once $config->paths["core/class"].'factura.class.php';
				$i=mysql_fetch_array($res);
				if($i["id"]){
					$factura=new factura($i["id"]);
					return $factura;
				}
				else {
					return INMUEBLE_BADTOKEN;
				}
			}
			else {
				return INMUEBLE_BADTOKEN;
			}
			
  }
	function autonotificar(){
  	global $config,$core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$core->loadClass("inmueble");
		$x=0;
		$q="SELECT distinct
				`i`.`id_expediente` as 'id_expediente',
				`i`.`id_cliente` as 'id_cliente'
			from `inmuebles` as `i` 
					left join `notificaciones` as `n` 
						on `i`.`id_expediente`=`n`.`id_inmueble` 
						and `i`.`id_cliente` =`n`.`id_cliente`,
				`bounds_user_inmueble` as `bui`, 
				`bounds_stock` as `bs`
			where  `bui`.`inmueble` = `i`.`id_expediente`
				and `bui`.`user` = `i`.`id_cliente`
				and `bui`.`user` = `bs`.`user`
				and `i`.`fecvennormal`>=now()
				and `bs`.`expire`>=now()
				and (`i`.`fecvennormal`<=now()+interval 5 day 
					OR  `bs`.`expire`<=now()+interval 5 day )
				and `n`.`tipo` is null";
		$r=mysql_query($q);
		while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
			$i=new inmueble($i["id_expediente"],$i["id_cliente"]);
			$i->notificar(1);
			$x++;
		}
		$q="SELECT distinct
				`i`.`id_expediente` as 'id_expediente',
				`i`.`id_cliente` as 'id_cliente'
			from `inmuebles` as `i` 
					left join `notificaciones` as `n` 
						on `i`.`id_expediente`=`n`.`id_inmueble` 
						and `i`.`id_cliente` =`n`.`id_cliente`,
				`bounds_user_inmueble` as `bui`, 
				`bounds_stock` as `bs`
			where  `bui`.`inmueble` = `i`.`id_expediente`
				and `bui`.`user` = `i`.`id_cliente`
				and `bui`.`user` = `bs`.`user`
				and (`i`.`fecvennormal`<=now()
				or `bs`.`expire`<=now())
				and `n`.`tipo` is null";
//				and `n`.`tipo` ='1'";
		$r=mysql_query($q);
		while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
			$i=new inmueble($i["id_expediente"],$i["id_cliente"]);
			$i->notificar(2);
			$x++;
		}
		return $x;
	}
	
}