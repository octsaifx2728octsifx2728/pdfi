<?php



global $config,$root_path, $core,$user,$document,$result, $vr;
if($_GET["debug"]){
error_reporting(E_ALL);
ini_set('display_errors', '1');
}
else {
error_reporting(0);
ini_set('display_errors', '0');
}
$config=(object)array();

include_once 'resources/php-local-browscap.php';
include_once 'config/defaults.config.php';
include_once 'config/paths.config.php';
include_once 'config/db.config.php';

include_once $config->paths["core"]."pay_core.php";



$cleanPaths=array(
	array(
		"path"=>"cache",
		"recursive"=>true
		),
	array(
		"path"=>"preload",
		"recursive"=>true
		)
	);

  
    $db=&$core->getDB();
    $con=&$db->getConexion();
	
	$q="UPDATE `inmuebles` SET `fecvenpremium`=0  WHERE  `fecvenpremium`<now()";
	//mysql_query($q);
	$total=mysql_affected_rows();
	//echo "Reindexados ".$total." anuncios premium caducados \n";
	
foreach($config->paths["clean"] as $path){
	$p=new ruta($path["path"]);
	$p->clean($path["recursive"]);
}



class ruta{
	var $path;
	function ruta($path){
		$this->path=$path;
	}
	function clean($recursive=false){
		if(file_exists($this->path)){
			if(is_dir($this->path)){
				$this->vaciar($this->path,$recursive);
			}
			else {
				unlink($this->path);
			}
		}
	}
	function vaciar($path,$recursive=false){
		$elementos=scandir($path);
		if(is_array($elementos)){
			foreach($elementos as $e){
				if($e!="."&&$e!=".."){
					$e=$path."/".$e;
					if(is_dir($e)){
						if($recursive){
							$this->vaciar($e,$recursive);
						}
					}
					else{
						unlink($e);
						echo "*Se ha eliminado '".$e."'\n";
					}
				}
			}
		}
	}
}


$q="select `avatar` from `usuarios` where `avatar` IS NOT NULL and `avatar`<>''";
$db=$core->getDB(0,2);
$imagenes=scandir($config->paths["base"]."galeria/perfil");
if($r=$db->query($q)){
	while($i=$r->fetch_assoc()){
                chmod($i["avatar"],0774);
		$i["avatar"]=str_replace("galeria/perfil/","",$i["avatar"]);
		$key=array_search($i["avatar"], $imagenes);
		unset($imagenes[$key]);
	}
}

$key=array_search("avatar.png", $imagenes);
unset($imagenes[$key]);

foreach($imagenes as $img){
	if(!is_dir($config->paths["base"]."galeria/perfil/".$img)){
		echo "borrando:".$img."<br>";
		unlink($config->paths["base"]."galeria/perfil/".$img);
	}
}



$q="select `path` from `imagenes_inmuebles` where `path` IS NOT NULL and `path`<>''";
$imagenes=scandir($config->paths["base"]."galeria/imagenes");
if($r=$db->query($q)){
	while($i=$r->fetch_assoc()){
                chmod($i["path"],0774);
		$i["path"]=str_replace("galeria/imagenes/","",$i["path"]);
		$key=array_search($i["path"], $imagenes);
		unset($imagenes[$key]);
	}
}

$key=array_search("sinimagen.jpg", $imagenes);
unset($imagenes[$key]);
$key=array_search("sinimagen2.jpg", $imagenes);
unset($imagenes[$key]);

foreach($imagenes as $img){
	if(!is_dir($config->paths["base"]."galeria/imagenes/".$img)){
		echo "borrando:".$img."<br>";
		unlink($config->paths["base"]."galeria/imagenes/".$img);
	}
}
//--------------------------------------------Notificaciones

$app=$core->getApp("inmueblesManager");
//$notificaciones=$app->autonotificar();
//echo "****Se han enviado ".intval($notificaciones)." notificaciones";
