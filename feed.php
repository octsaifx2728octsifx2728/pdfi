<?PHP

if($_GET["debug"]||$_POST["debug"]){
error_reporting(E_ALL);
ini_set('display_errors', '1');
}
else {
error_reporting(0);
ini_set('display_errors', '0');
}

global $config,$root_path, $core,$user,$document,$vr,$inmueble,$user_view,$factura;

$config=(object)array();

include_once 'resources/php-local-browscap.php';
include_once 'config/defaults.config.php';
include_once 'config/paths.config.php';
include_once 'config/db.config.php';

if (!isset($_SESSION)) {
  session_start();
}
include_once $config->paths["core"]."pay_core.php";



$document=$core->getDocument("index.html");

    $app="mainpage";
    $task="defaultTask";
    $app=$core->getApp($app);


    if(is_object($app)&&method_exists($app,$task)){

      $app->$task($params);
      $document->addAlerts();
    }

$app=$_POST["app"]?$_POST["app"]:$_GET["app"];
$task=trim($_POST["task"]?$_POST["task"]:$_GET["task"],"/");
$document=  simplexml_load_string("<root></root>");
switch($app){
  default:
    $handler=$core->getHandler($app);
	  if($handler){
	  	$handler->run($task);
	  }
	  else {
	    $result["error"]="2";
	    $result["errorDescription"]="Aplicacion Desconocida";
	  }
    break;
}
        
header("content-type:text/xml");        
echo $document->asXML();

?>