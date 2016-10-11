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
include_once 'config/vo.php';




include_once $config->paths["core"]."pay_core.php";




$app=$_POST["app"]?$_POST["app"]:$_GET["app"];





    
        switch ($app) {
        case 'Mensajes'://consulta
            include_once 'controller/MensajesController.php';
            $mensajesController = new MensajesController();
            $mensajesController->API();
        break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }


/*
    $handler=$core->getHandler($app);
	  if($handler){
	  	$handler->run($task);
	  }
	  else {
            //print_r($_GET);
            $result["error"]="3";
            $result["errorDescription"]="Aplicacion desconocida: ".$app;
	  }
 

header("content-type:application/json");

$respuesta = json_encode($result);



echo $respuesta;
*/
 $myfile = fopen("/var/www/vhosts/e-spacios.com/httpdocs1/test.log", "w");               
                fwrite($myfile, json_encode( $result));
                fclose($myfile);


$respuesta = json_encode( $result);


$request_body = file_get_contents('php://input');


                
                
$data = json_decode($request_body);




    


echo $respuesta;


?>
