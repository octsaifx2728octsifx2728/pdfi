<?php
class user extends objeto{
	var $logged=false;
	var $id;
        protected $_idField="id_cliente";
        protected $_tabla="usuarios";
	var $vals=array();
        public $telefonos=array();
        public $telefonosTags=array();
  public function user($id=false){
  	if(!session_id()){
		session_start();	
		}
    $this->id=$id;
    if(!$this->id&&$_SESSION['user_id']){
      $this->id=$_SESSION['user_id'];
      $this->logged=true;
    }
  }
  public function getToken($id=null,$pass=null){
    global $core;
    $db=$core->getDB(0,2);
      if(!$id){
          $id=$this->id;
      }
      if(!$pass){
          $pass=$this->get("password");
      }
    $q="select `u`.`token` from `usuarios` as `u` where `u`.`id_cliente`=";
  }
  public function getPhones(){
      $phones=array();
      if($this->get("telefono")){
        $phones[]=array(
            "telefono"=>$this->get("telefono"),
            "tag"=>$this->get("telefonotag")
                );
        }
      if($this->get("telefono1")){
        $phones[]=array(
            "telefono"=>$this->get("telefono1"),
            "tag"=>$this->get("telefonotag1")
                );
        }
      if($this->get("telefono2")){
        $phones[]=array(
            "telefono"=>$this->get("telefono2"),
            "tag"=>$this->get("telefonotag2")
                );
        }
      return $phones;
  }
  public function setTelefono($index=0,$val){
    global $core; 
    $db=$core->getDB(0,2);
    $q="( select `id` from `telefonos` where `user`='".intval($this->id)."'
        limit ".($index?intval($index):"0").",1)";
    $q="update `telefonos`  as `t`, ( select `id` from `telefonos` where `user`='".intval($this->id)."'
        limit ".($index?intval($index):"0").",1) as`t2`  set `t`.`telefono`='".$db->real_escape_string(trim($val))."' 
        where `t`.`id`= `t2`.`id`";
    $db->query($q);
    if($db->affected_rows>0){
        return true;
    }
    else {
        $q="insert into `telefonos`(`telefono`,`user`,`tag`) values
            ('".$db->real_escape_string(trim($val))."','".intval($this->id)."','_____telefono-----')";
        $db->query($q);
        return true;
    }
  }
  public function setTelefonoTag($index=0,$val){
    global $core; 
    $db=$core->getDB(0,2);
    $q=" select `id` from `telefonos` where `user`='".intval($this->id)."'
        limit ".($index?intval($index):"0").",1";
    
    $q="update `telefonos`  as `t`, ( select `id` from `telefonos` where `user`='".intval($this->id)."'
        limit ".($index?intval($index):"0").",1) as`t2`  set `t`.`tag`='".$db->real_escape_string(trim($val))."' 
        where  `t`.`id`= `t2`.`id`";
    $db->query($q);
    if($db->affected_rows>0){
        return true;
    }
    else {
        $q="insert into `telefonos`(`tag`,`user`) values
            ('".$db->real_escape_string(trim($val))."','".intval($this->id)."')";
        $db->query($q);
        return true;
    }
  }
  public function getTelefono($index=0){
    global $core;
    if(array_key_exists($index, $this->telefonos)){
        return $this->telefonos[$index];
    }
      
      
    $db=$core->getDB(0,2);
    
    
    $q="select `telefono`,`tag` from `telefonos` 
        where `user`='".intval($this->id)."'
        limit ".($index?intval($index):"0").",1";
    
      
    
    if($r=$db->query($q)){
        if($i=$r->fetch_array()){
            $this->telefonos[$index]=$i["telefono"];
            $tag=str_replace(array("_____","-----"),'$$',$i["tag"]);
            $this->telefonosTags[$index]=$tag;
        }
    }
    

    
    return $this->telefonos[$index];
  }
  public function getTelefonoTag($index=0){
    global $core;
    if(array_key_exists($index, $this->telefonosTags)){
        return $this->telefonosTags[$index];
    }
      
      
    $this->getTelefono($index);
    return $this->telefonosTags[$index];
  }
  public function get($param){
    global $core;
    switch($param){
      case   "URL":
          return $this->getLink();
          break;
      case "telefono":
          return $this->getTelefono();
          break;
      case "telefono1":
          return $this->getTelefono(1);
          break;
      case "telefono2":
          return $this->getTelefono(2);
          break;
      case "telefonotag":
          return $this->getTelefonoTag(0);
          break;
      case "telefonotag1":
          return $this->getTelefonoTag(1);
          break;
      case "telefonotag2":
          return $this->getTelefonoTag(2);
          break;
      default:
          return parent::get($param);
          break;
    }
  }
  public function setCookie(){
      $key=md5("espacios".$this->id.rand());
      $this->set("token_cook",$key);
      setcookie("esp_l_334",$key,time()+155520000,"/");
      return $key;
  }
  public function removeCookie(){
      setcookie("esp_l_334","",time()-1000,"/");
  }
  public function moveAvatar($path){
  	global $config;
	if(strpos($path,"http")===false){
            $path2=tempnam($config->paths["avatars"], "Avatar_");
            if(copy($path,$path2)){
                return str_replace($config->paths["base"],"",$path2);
            }
	  else {
	  	return 'galeria/perfil/avatar.png';
	  }
	}
	else {
		return $path;
	}
  }
  public function loginWithToken($token){
    global $core;
    $db=$core->getDB(0,2);
    $q="select `u`.`id_cliente` as 'id' from `usuarios` as `u` where `u`.`token`='".$db->real_escape_string($token)."' limit 1";
    if($r=$db->query($q)){
        if($r->num_rows&&$i=$r->fetch_assoc()){
            $this->id=intval($i["id"]);
            $this->forceLogin();
            
            if($token=$this->get("fb_token")){
                $facebook=$core->getApp("facebook");
                $token=$facebook->renewToken($token);
                $this->submitToken($token);
            }
        }
    }
  }
  public function set($param, $value){
    global $core;
    switch($param){
        case "telefono":
            return $this->setTelefono(0,$value);
            break;
        case "telefono1":
            return $this->setTelefono(1,$value);
            break;
        case "telefono2":
            return $this->setTelefono(2,$value);
            break;
        case "telefonotag":
            return $this->setTelefonoTag(0,$value);
            break;
        case "telefonotag1":
            return $this->setTelefonoTag(1,$value);
            break;
        case "telefonotag2":
            return $this->setTelefonoTag(2,$value);
            break;
        case "avatar":
            return parent::set($param, $this->moveAvatar($value));
            break;
        case "clave":
            return parent::set($param, md5($value."espacio"));
            break;
        case "clave2":
            return parent::set($param, md5($value."espacio"));
            break;
        default:
            return parent::set($param, $value);
            break;
    }
  }
  public function  getFB_login_link(){
    global $config;
    $url="http://www.facebook.com/dialog/oauth?client_id=YOUR_APP_ID&redirect_uri=YOUR_REDIRECT_URI&state=SOME_ARBITRARY_BUT_UNIQUE_STRING&scope=email";
    $p=array(
      "YOUR_APP_ID"=>$config->defaults["facebook"]->app_id,
      "YOUR_REDIRECT_URI"=>"http://www.e-spacios.com/app/facebook/log",
      "SOME_ARBITRARY_BUT_UNIQUE_STRING"=>md5($this->get("username"))
     );
    $url=str_replace(array_keys($p),$p,$url);
	$urlactual="http".($_SERVER["HTTPS"]?"s":"")."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	setcookie("actualURL",$urlactual,0,"/");
    return $url;
  }
  public function forceLogin(){
  	$q="select `id_cliente`,`usuario`, `clave`, `nombre_pant`
	      from `usuarios`
	      where `id_cliente`='".mysql_real_escape_string($this->id)."'
	      limit 1";
	$r=mysql_query($q);
	if(mysql_num_rows($r)){
	    $i=mysql_fetch_array($r,MYSQL_ASSOC);
	    $_SESSION['MM_Username'] = $i["usuario"];
		$_SESSION['MM_nombre_pant'] = $i["nombre_pant"];
		$_SESSION['user_id'] = $i["id_cliente"];
	    $this->logged=true;
	    $this->id=$i["id_cliente"];
            $this->setCookie();
	}
  }
  public function facebookLogged(){
    $logged=false;
    $token=$this->get("fb_token");
    if($token){
      $expire=$this->get("fb_expire");
      if($expire>time()){
	  $logged=true;
      }
    }
    return $logged;
  }
  public function logout($callBack=""){
  	global $core;
   unset($_SESSION['user_id']);
   unset($_SESSION['MM_Username']);
   unset($_SESSION['MM_nombre_pant']);
   unset($_SESSION['MM_UserGroup']);
   unset($_SESSION['MM_Mobile_Browser']);
	  //$actividades=$core->getApp("actividades");
	  //$actividades->report($this,'$$has_logout$$',$this->getLink());
    $this->removeCookie();
    //header("location:/");
    header("location:".$callBack);
    exit;
  }
  public function getAvatar(){
  	global $config;
  	$avatar=$this->get("avatar");
	if(!$avatar){
		if(file_exists('galeria/perfil/'.$this->get("usuario").'.jpg')){
			$avatar='galeria/perfil/'.$this->get("usuario").'.jpg';
		}
		else {
			$avatar='galeria/perfil/avatar.png';
		}
		$this->set("avatar",$avatar);
	}
    if(!file_exists($config->paths["base"].$avatar)){
            $avatar='galeria/perfil/avatar.png';
        }
  	return $avatar;
  }
  public function register($datos){
    global $core;
    $db=&$core->getDB();
    $con=&$db->getConexion();
    $q="insert into `usuarios`(`usuario`,`clave`,`nombre_pant`,`stayInTouchCheckbox`,`sendProEmailCheckbox`,`fb_token`,`fb_expire`)
	values(
	  '".mysql_real_escape_string($datos["email"])."',
	  '".md5($datos["password"]."espacio")."',
	  '".mysql_real_escape_string($datos["screenName"])."',
	  '".($datos["stayInTouchCheckbox"]?"1":"0")."',
	  '".($datos["proCheckbox"]?"1":"0")."',
	  '".mysql_real_escape_string($datos["fbtoken"])."',
	  '".(time()+6000)."')";
    $r=mysql_query($q,$con);
    $this->id=mysql_insert_id($con);
    if($this->id){

	  $actividades=$core->getApp("actividades");
	  $actividades->report($this,'$$has_register$$',$this->getLink());

    $q="insert into `bounds_stock` (`user`,`bound`,`cantidad`,`expire`)
	values(
	  '".mysql_real_escape_string($this->id)."',
	  '1',
	  '1',
	  now()+ INTERVAL 1 year)";
    mysql_query($q);
      $this->forceLogin();
      if($token=$this->get("fb_token")){
                $facebook=$core->getApp("facebook");
                $token=$facebook->renewToken($token);
                $this->submitToken($token);
            }
    }
    else {
      $q="select `u`.`id_cliente` from `usuarios` as `u` where `u`.`usuario`='".mysql_real_escape_string($datos["emailAddr"])."'";
      $r=mysql_query($q,$con);
      if($r&&mysql_num_rows($r)){
	$core->addAlert('$$User_already_exists$$');
      }
      else {
	$core->addAlert('$$User_unknow_error$$');
      }
    }
  }
  public function getLink(){
  	return "/app/user/".$this->id."/".  preg_replace('/%[A-Z0-9][A-Z0-9]/','_',urlencode(str_replace(" ","_",$this->get("nombre_pant"))));
  }
  public function login($user="",$pass="",$pass2=""){
    global $core;
      if (!isset($_SESSION)) {
	session_start();
      }
    $db=&$core->getDB();
    $con=&$db->getConexion();
    $q="SELECT id_cliente,usuario, clave, nombre_pant
      FROM usuarios
      WHERE usuario='".mysql_real_escape_string($user)."' 
        
        and (clave = '".mysql_real_escape_string($pass)."'
          or clave = '".md5($pass."espacio")."'
          or clave2 = '".md5($pass."espacio")."'
          )
      limit 1";
    $r=mysql_query($q);
    if(mysql_num_rows($r)){
      $i=mysql_fetch_array($r,MYSQL_ASSOC);
      $this->id=$i["id_cliente"];
      $this->forceLogin();
      if($token=$this->get("fb_token")){
                $facebook=$core->getApp("facebook");
                $token=$facebook->renewToken($token);
                $this->submitToken($token);
            }
        return true;
      }
        return false;
  }
  public function loginWithCookie(){
      
    global $core;
    $db=$core->getDB(0,2);
    $q="select `u`.`id_cliente` as 'id' from `usuarios` as `u` where `u`.`token_cook`='".$db->real_escape_string($_COOKIE["esp_l_334"])."' limit 1";
    if($r=$db->query($q)){
        if($r->num_rows&&$i=$r->fetch_assoc()){
            $this->id=intval($i["id"]);
            $this->forceLogin();
            if($token=$this->get("fb_token")){
                $facebook=$core->getApp("facebook");
                $token=$facebook->renewToken($token);
                $this->submitToken($token);
            }
        }
    }
  }
  public function loginFacebook($code){
    global $core;
      if (!isset($_SESSION)) {
	session_start();
      }
    $db=$core->getDB(0,2);
    $q="select `u`.`id_cliente`
	,`u`.`usuario`, `u`.`nombre_pant`
      from `usuarios` as `u`
      where `u`.`fb_token`='".$db->real_escape_string($code["access_token"])."'
      limit 1";
    $r=$db->query($q);
    if($r->num_rows&&$i=$r->fetch_assoc()){
      $this->id=$i["id_cliente"];
      $this->forceLogin();
      return true;
      }
    else{
          $facebook=&$core->getApp("facebook");
          $udata=json_decode($facebook->get("/me",$code["access_token"]));
          $FBID=$udata->id;
          
            $q="select `u`.`id_cliente`
                ,`u`.`usuario`, `u`.`nombre_pant`
              from `usuarios` as `u`
              where `u`.`fb_id`='".$db->real_escape_string($udata->id)."'
                  or `u`.`usuario`='".$db->real_escape_string($udata->email)."'
              limit 1";
            $r=$db->query($q);
            if($r->num_rows&&$i=$r->fetch_assoc()){
              $this->id=$i["id_cliente"];
              $this->submitToken($code);
              $this->set("fb_id",$udata->id);
              $this->forceLogin();
              return true;
              }
             else{
                 $this->register(array(
                     "email"=>$udata->email,
                     "screenName"=>$udata->name
                 ));
              $this->set("fb_id",$udata->id);
              $this->submitToken($code);
                 
             }
      }
    return false;
  }
  public function submitToken($code){
    if($this->logged){
      $this->set("fb_token",$code["access_token"]);
      $this->set("fb_expire",time()+$code["expires"]);
    }
  }
 public function getFavoritos(){
     global $core;
     $core->loadClass("inmueble");
     $manager=$core->getApp("inmueblesManager");
 	$q="select `id_clientefavo`,`id_expedientefavo`,`tipo` from `favoritos` where `id_cliente`='".mysql_real_escape_string($this->id)."'";
	$r=mysql_query($q);
	$res=array();
	while($i = mysql_fetch_array($r,MYSQL_ASSOC)){
            $in=$manager->getinmueble($i["id_expedientefavo"],$i["tipo"]);
            if($in){
		$res[]=$in;
            }
	}
	return $res;
 }
  public function countFavoritos(){
  	$q="select count(`id_cliente`) as 'total' from `favoritos` where `id_cliente`='".mysql_real_escape_string($this->id)."' group by `id_cliente`";
	$r=mysql_query($q);
	$i=mysql_fetch_array($r,MYSQL_ASSOC);
	return intval($i["total"]);
  }
  public function countAnuncios(){
  	global $core,$user;
	$search=&$core->getApp("search");
  	$inmuebles=$search->searchByUser($this,($user->id==$this->id));
	return count($inmuebles);
  }
  public function countUnreadMessages(){
  	global $core;
        $db=$core->getDB(0,2);
  	$q="select count(`user2`) as 'total' from `conversaciones` where `user2`='".intval($this->id)."' and `user2read`='0'  group by `user2`";

        if($r=$db->query($q)){
            
            if($i=$r->fetch_assoc()){
                return  intval($i["total"]);
            }
        }
	return 0;
  }
  public function countVendidos(){
      global $core;
        $db=$core->getDB(0,2);
  	$q="select count(`user`) as 'total' from `anuncios` where `user`='".intval($this->id)."' and `vendido`='1'  group by `user`";

	if($r=$db->query($q))
            {
            if($i=$r->fetch_assoc()){
                return intval($i["total"]);
                }
            }
       return 0;
  }
  public function getFreemiumAvaliable(){
  	return $this->getAvaliables(1);
  }
  public function getStandarAvaliable(){
  	return $this->getAvaliables(2);
  }
  public function getBoundAvaliable(){
  	return $this->getAvaliables(3)+$this->getAvaliables(4);
  }
  public function getAvaliables($tip){
      global $core;
      $db=&$core->getDB(0,2);
      $q1="SELECT count(`bui`.`bound`)as'total' FROM `bounds_user_inmueble`  as `bui` left join `anuncios` as `a` on `a`.`id`=`bui`.`anuncio` where `a`.`fecvennormal`>now() and `a`.`user`='".intval($this->id)."' and `bound`='".intval($tip)."' group by `bui`.`bound`";
      $q2="select (sum(`b`.`cantidad`)*sum(`bs`.`cantidad`)) as 'total' from `bounds` as `b` left join `bounds_stock` as `bs` on `bs`.`bound`=`b`.`id` where `b`.`id`='".intval($tip)."' and `bs`.`user`='".intval($this->id)."' group by `b`.`id`";
      
      //echo $q2."<hr>";
      $usados=0;
      $disponibles=0;
      if($sm1=$db->prepare($q1)){
          $sm1->bind_result($usad);
          if($sm1->execute()){
              while ($sm1->fetch()) {
                   $usados=$usad+$usados;
                }
          }
      }
      if($sm2=$db->prepare($q2)){
          $sm2->bind_result($usad);
          if($sm2->execute()){
              while ($sm2->fetch()) {
                   $disponibles=$usad+$disponibles;
                }
          }
      }
     // echo $disponibles-$usados;
      return $disponibles-$usados;
  }
  static function reminderPassword($email){
  	global $core;
    $db=&$core->getDB();
    $con=&$db->getConexion();
    $q="select `id_cliente`,`clave`,`usuario`,`nombre_pant` from `usuarios` where `usuario`like'".  mysql_real_escape_string($email)."' limit 1";
    
    $r=mysql_query($q);
    $i=mysql_fetch_array($r,MYSQL_ASSOC);
    if($i["id_cliente"]){
        $u=new user($i["id_cliente"]);
        $p=md5($i["id_cliente"]."espacio".rand());
        $u->set("clave2",$p);
        $mailer=$core->getApp("mailer");
        $token=$u->get("token");
        if(!$token){
            $token=md5($email."secreto".rand());
            $u->set("token",$token);
        }
			$mailer->send(array(
				"destinatario"=>$i["usuario"],
				"asunto"=>'[[e-spacios.com]]$$password_retrieve$$',
				"plantilla"=>"html/password_retrieve.html",
				"variables"=>array(
					"NOMBRE"=>$i["nombre_pant"],
					"LINK"=>$u->getLink(),
					"TOKEN"=>$token
					)
				));
       return true;
    }
    return false;
    }
  public function sendPasswordtoEmail(){
  	global $core;
        $mailer=$core->getApp("mailer");
			$mailer->send(array(
				"destinatario"=>$this->get("usuario"),
				"asunto"=>'[[e-spacios.com]]$$password_retrieve$$',
				"plantilla"=>"html/password_retrieve.html",
				"variables"=>array(
					"NOMBRE"=>$this->get("nombre_pant"),
					"PASSWORD"=>$this->get("clave")
					)
				));
  }
}