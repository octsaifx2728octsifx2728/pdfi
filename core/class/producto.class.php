<?php
global $core;
$core->loadClass("objeto");
class producto extends objeto{
	var $id;
	var $_tabla="productos";
	public function producto($id){
		$this->id=$id;
	}
	public function getPrecio($user=false){
            $av=0;
            if($user&&$this->id!=2){
                $av=$user->getAvaliables($this->get("bound"));
            }
            if($av>0){
                return 0;
            }
            else {
		return $this->get("precio");
            }
	}
	public function getDias(){
		if(!$this->dias){
			$q="select `dias` from `productos` where `id`='".mysql_real_escape_string($this->id)."'";
			$r=mysql_query($q);
			$i=mysql_fetch_array($r,MYSQL_ASSOC);
			$this->dias= $i["dias"];
			}
		return $this->dias;
	}
	public function getBound(user $user=null){
		if(!$this->bound){
			$q="select `bound` from `productos` where `id`='".mysql_real_escape_string($this->id)."'";
			$r=mysql_query($q);
			$i=mysql_fetch_array($r,MYSQL_ASSOC);
			if($i["bound"]){
				$this->bound= $i["bound"];
				}
			else {
				if($user->getAvaliables(3)){
					$this->bound=3;
				}
				elseif($user->getAvaliables(4)) {
					$this->bound=4;
				}
				}
			}
		return $this->bound;
	}
	public function aplicar(inmueble $inmueble){
            global $core;
            $db=&$core->getDB(0,2);
            $user=new user($inmueble->get("id_cliente"));
			$vencimiento=($dias*86400);
                        $anuncio=$inmueble->getAnuncio();
                        //print_r($anuncio);
                        //exit;
                        switch ($this->id){
                                case 1:
                                    
                                    $anuncio->set("fecvennormal",date("Y-m-d H:i:s",(time()+($this->get("dias")*86400))));
                            $inmueble->set("fecvennormal",date("Y-m-d H:i:s",(time()+($this->get("dias")*86400))));
                            
                            $anuncio->set("activoForced","1");
                            $anuncio->set("incompleto","0");
                            $q="delete from `bounds_user_inmueble` 
                                where `inmueble`='".intval($inmueble->id)."'
                                    and `tipo`='".intval($inmueble->get("tipoobjeto"))."'";
                            $db->query($q);
                            $q="insert into `bounds_user_inmueble`(`bound`,`user`,`inmueble`,`tipo`,`anuncio`) 
                                    values('".intval($this->get("bound"))."',
                                            '".intval($user->id)."',
                                            '".intval($inmueble->id)."',
                                            '".intval($inmueble->get("tipoobjeto"))."',
                                            '".intval($anuncio->id)."')";

                            mysql_query($q);
                                    
                                break;
                                case "14":
                                    $anuncio->set("vendido",1);
                                    $anuncio->set("activo",1);
                                    $anuncio->set("vendido_date",date("Y-m-d H:i:s"));
                                    $anuncio->set("fecvennormal",date("Y-m-d H:i:s",(time()+($this->get("dias")*864000))));
                                    $inmueble->set("fecvennormal",date("Y-m-d H:i:s",(time()+($this->get("dias")*864000))));
                                    break;
                                case 5:
                                    $anuncio->set("activoForced","1");
                                    $anuncio->set("incompleto","0");
                                    $q="delete from `bounds_user_inmueble` 
                                        where `inmueble`='".intval($inmueble->id)."'
                                            and `tipo`='".intval($inmueble->get("tipoobjeto"))."'";
                                    $db->query($q);
                                    $q="insert into `bounds_user_inmueble`(`bound`,`user`,`inmueble`,`tipo`,`anuncio`) 
                                            values('".intval($this->get("bound"))."',
                                                    '".intval($user->id)."',
                                                    '".intval($inmueble->id)."',
                                                    '".intval($inmueble->get("tipoobjeto"))."',
                                                    '".intval($anuncio->id)."')";

                                    mysql_query($q);
                                    
                                    
                                    
                                    
                                    $anuncio->set("fecvenpremium",date("Y-m-d H:i:s",(time()+($this->get("dias")*86400))));
                                    $inmueble->set("fecvenpremium",date("Y-m-d H:i:s",(time()+($this->get("dias")*86400))));
                                    break;
                                case 15:
                                    
                                    $anuncio->set("activoForced","1");
                                    $anuncio->set("incompleto","0");
                                    $q="delete from `bounds_user_inmueble` 
                                        where `inmueble`='".intval($inmueble->id)."'
                                            and `tipo`='".intval($inmueble->get("tipoobjeto"))."'";
                                    $db->query($q);
                                    $q="insert into `bounds_user_inmueble`(`bound`,`user`,`inmueble`,`tipo`,`anuncio`) 
                                            values('".intval($this->get("bound"))."',
                                                    '".intval($user->id)."',
                                                    '".intval($inmueble->id)."',
                                                    '".intval($inmueble->get("tipoobjeto"))."',
                                                    '".intval($anuncio->id)."')";
                                    
                                    
                                    mysql_query($q);
                                    
                                    
                                    $anuncio->set("fecvenoferta",date("Y-m-d H:i:s",(time()+($this->get("dias")*86400))));
                                    $inmueble->set("fecvenoferta",date("Y-m-d H:i:s",(time()+($this->get("dias")*86400))));
                                    break;
                                }
                        
                        
			return true;
	}
	public function adjudicar($usuario){
	    global $core;
	    $db=&$core->getDB();
	    $con=&$db->getConexion();
		$dias=$this->getDias();
		$bound=$this->getBound();
			
		$q="insert into `bounds_stock`(`user`,`bound`,`cantidad`,`expire`)
			values (
				'".mysql_real_escape_string($usuario->id)."',
				'".mysql_real_escape_string($bound)."',
				'1',
				now() + interval ".mysql_real_escape_string($dias)." day
				)";
		mysql_query($q);
				
	}
        public function getInfo(inmueble $inmueble=NULL,user $user=NULL){
            if($this->get("avaliableRequired")&&$inmueble&&!$inmueble->isActive()){
                return false;
            }
            if($this->get("stockable")&&$user&$user->getAvaliables($this->get("bound"))){
                $precio=0;
            }
            else {
                $precio=$this->get("precio");
            }
            $info=array( 
                "id"=>$this->id,
                "nombre"=>$this->get("nombre"),
                "inmueble"=>$inmueble?$inmueble->id:0,
                "inmuebleTipo"=>$inmueble?$inmueble->get("tipoobjeto"):0,
                "descripcion"=>$this->get("descripcion"),
                "bound"=>$this->get("bound"),
                "cantidad"=>$this->get("cantidad"),
                "dias"=>$this->get("dias"),
                "tipo"=>$this->get("tipo"),
                "recurrente"=>$this->get("recurrente"),
                "precio"=>$precio
                );
            return $info;
        }
}
