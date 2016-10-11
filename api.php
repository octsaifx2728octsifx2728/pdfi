<?PHP

global $config,$root_path, $core,$user,$document,$result, $vr;
/*
if($_GET["debug"]){
error_reporting(E_ALL);
ini_set('display_errors', '1');
}
else {
error_reporting(0);
ini_set('display_errors', '0');
}
 
 */
error_reporting(0);
ini_set('display_errors', '0');

$config=(object)array();

include_once 'resources/php-local-browscap.php';
include_once 'config/defaults.config.php';
include_once 'config/paths.config.php';
include_once 'config/db.config.php';

include_once $config->paths["core"]."pay_core.php";


    $result["error"]="1";
    $result["errorDescription"]="Error desconocido";


$vrs= (array_key_exists("vr", $_GET)||array_key_exists("vr", $_POST)?($_GET["vr"]?$_GET["vr"]:$_POST["vr"]):1);

$vr=$_SESSION["vr"];


if(!array_key_exists("vr", $_SESSION)||array_key_exists("vr", $_GET)){
	$_SESSION["vr"]=$vrs;
	$vr=$vrs;
}

$app=$_POST["app"]?$_POST["app"]:$_GET["app"];
$task=trim($_POST["task"]?$_POST["task"]:$_GET["task"],"/");

switch($app){
	case "favoritos":
    	include_once $config->paths["core/class"].'inmueble.class.php';
		$app=$core->getApp("favoritos");
                $manager=$core->getApp("inmueblesManager");
		$inmueble=$manager->getInmueble($_GET["id_inmueble"],$_GET["tipo"]);
		switch($task){
			case "change":
				if($app->change($inmueble,$_GET["valor"])){
							      $result["error"]="0";
							      $result["errorDescription"]='ok';
				}
				break;
		}
		
		break;
	case "banorte":
                $result["error"]="0";
                $result["errorDescription"]="Ok";
                $result["respuesta"]=$_GET;
		break;
	case "procesarpago":
		switch($task){
			case "pagar":
				$app=$core->getApp("banorte");
				if($app->pagar($_GET)){
						$result["error"]="0";
						$result["errorDescription"]='ok';
				}
				break;
		}
		break;
	case "updateUser":
	
		if($user->id){
			$user->set($task,$_GET["value"]);
	      $result["error"]="0";
	      $result["errorDescription"]="Ok";
	      $result[$task]=$_GET["value"];
		}
		else {	
	      $result["error"]="8";
	      $result["errorDescription"]="No tienes permisos para modificar ese usuario";
		}
		break;
	case "upload":
    	$app=$core->getApp("uploadManager");
    		if($app){
    			$app->upload($task);
			}
		break;
	case "actividades":
    	$app=$core->getApp("actividades");
    		if($app){
      			switch($task){
					case "get":
					  $result["error"]="0";
					  $result["errorDescription"]="OK";
	  					$acts=$app->get($_GET["index"],"desc",$_GET["max"]);
	  					krsort($apcts);
	  					$result["resultado"]=array_values($acts);
						break;
					default:
					  $result["error"]="5";
					  $result["errorDescription"]="Tarea desconocida: ".$task;
						break;
				}
			}
			else {
					  $result["error"]="4";
					  $result["errorDescription"]="Error al iniciar actividad";
			}
		break;
  case "searchplaces":
    $app=$core->getApp("searchplaces");
    $resultados=array();
    if($app){
      $resultados=$app->search($task);
      $result["type"]="places";
    }
    break;
  case "search":
    $app=$core->getApp("search");
    $resultados=array();
    if($app){
      $resultados=$app->search($task,null,null,true);
    }
    if(!count($resultados)){
      $result["error"]="2";
      $result["errorDescription"]='$$sin_resultados$$';
      $result["type"]="string";
      $result["query"]=$task;
      }
    else {
      $chunk=$core->getChunk("parsedResults");
      $result["error"]="0";
      $result["errorDescription"]="OK";
      $result["resultCount"]=count($resultados);
      $result["results"]=$chunk->out();
      $result["type"]="string";
      $result["query"]=$task;

    }
    break;
  case "payment":
  		switch($task){
			case "procesar":
				$app=$core->getApp("facturacion");
				$factura=$app->crearFactura(array("cliente"=>$user,"productos"=>array($_GET["id"])));
				$token=$app->getPaymentToken($factura,$_GET["tipopago"]);
		      	$result["error"]="0";
		      	$result["errorDescription"]="OK";
      			$result["token"]=$token;
				break;
  		}
	  break;
  default:
    $handler=$core->getHandler($app);
	  if($handler){
	  	$handler->run($task);
	  }
	  else {
            //print_r($_GET);
            $result["error"]="3";
            $result["errorDescription"]="Aplicacion desconocida: ".$app;
	  }
    break;
}

$lexicon=$core->getLexicon();
if($lexicon){
	$result["errorDescription"]=$lexicon->traduce($result["errorDescription"]);
	if($result["paymentMessage"]){
            $result["paymentMessage"]=$lexicon->traduce($result["paymentMessage"]);
	}
}

header("content-type:text/javascript");

$respuesta = $_GET["callback"]."(".json_encode($result).",'".($_POST["idcallback"]?$_POST["idcallback"]:$_GET["idcallback"])."');";


if($lexicon){
    $respuesta=$lexicon->traduce($respuesta);
}

echo $respuesta;


?>
