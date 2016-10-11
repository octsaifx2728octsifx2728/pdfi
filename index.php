<?PHP
date_default_timezone_set("America/Mexico_City") ;
/*
if($_GET["debug"]||$_POST["debug"]){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

}else {
    error_reporting(1);
    ini_set('display_errors', '1');
} 
*/

error_reporting(0);
ini_set('display_errors', '0');


global $config, $root_path, $core, $user, $document, $vr, $inmueble, $user_view, $factura;

$config = (object)array();

include_once 'resources/php-local-browscap.php';

include_once 'config/defaults.config.php';
include_once 'config/paths.config.php';
include_once 'config/db.config.php';
if (!isset($_SESSION)) {
  session_start();
}
include_once $config->paths["core"]."pay_core.php";
$vrs = (array_key_exists("vr", $_GET) or array_key_exists("vr", $_POST) ? ($_GET["vr"] ? $_GET["vr"] : $_POST["vr"]) : 1);
$vr  = $_SESSION["vr"];

if(!array_key_exists("vr", $_SESSION) or array_key_exists("vr", $_GET)){
    $_SESSION["vr"] = $vrs;
    $vr             = $vrs;
}
//include ("valida_usuario.php");
$_SESSION['MM_ADS']      = '';
$_SESSION['MM_espacios'] = '2';

$document = $core->getDocument("index.html");
$app      = "mainpage";
$task     = "defaultTask";
$app      = $core->getApp($app);
$document->addScript("js/jquery.js");
$document->addScript("js/jquery-ui.js");

if(is_object($app) and method_exists($app, $task)){
    $app->$task($params);
    $document->addAlerts();
}

$app  =      $_POST["app"]  ? $_POST["app"]  : $_GET["app"];
$task = trim($_POST["task"] ? $_POST["task"] : $_GET["task"], "/");
switch($app){
    case "ads":
        $document = $core->getDocument("ads.html");
        break;
    case "terminos":
        $document = $core->getDocument("toc.html");
        break;
    case "privacidad":
        $document = $core->getDocument("privacidad.html");
        break;
    case "uploadFile":
        switch($task){
            case "Avatar":
                $document = $core->getDocument("uploadAvatar.html");

                $document->addVariable("#ID#",$_GET["id"]);
                $document->addVariable("#CALLBACK#",$_GET["callback"]);
                $document->addVariable("#TARGET#",$_GET["target"]);
                break;
            case "Image":
                $document = $core->getDocument("uploadImage.html");

                $document->addVariable("#ID#",$_GET["id"]);
                $document->addVariable("#CALLBACK#",$_GET["callback"]);
                $document->addVariable("#TIPO#"    ,$_GET["tipo"]);
                $document->addVariable("#TARGET#"  ,$_GET["target"]);
                break;
        }
        break;
    case "cache":
        $app = $core->getApp("cache");
        if($app){
          $app->serve($_GET);
        }
        exit;
        break;
    case "search":
        $app = $core->getApp("search");
        if($app){
          $app->search($task);
        }
        break;
    case "searchbounds":

        $app = $core->getApp('search');
        if($app){
            if($_GET['tipoobjeto']){
                $core->setFilter('tipoobjeto', $_GET['tipoobjeto']);
            }
            if($_GET['tipovr']){
                $core->setFilter('tipovr', $_GET['tipovr']);
            }
            $app->searchbounds($task);
        }
        break;
    case "paypal":
        switch($task){
            case "cancel":
                $document=$core->getDocument("paypal_cancel.html");
                break;
            case "checkout":
                $app=$core->getApp("paypal");
                $resp=$app->submitToken($_GET["token"],$_GET["PayerID"]);
                if($resp){
                    $document=$core->getDocument("paypal_success.html");
                    }
                else {
                    $document=$core->getDocument("paypal_error.html");
                    $document->addVariable("#ErrorNumber#",$resp[""]);
                }
                break;
        }
        $document->addStyle("/css/paypal.css");
        break;
    default:
        $handler = $core->getHandler($app);

        if($handler){
            if($handler->run($task)=="noConsumido"){
                $document->addStyle("css/index.css");
            }
        }else {
            $result["error"]            = "2";
            $result["errorDescription"] = "Aplicacion Desconocida";
        }
        break;
}
$document->addStyle("css/jquery-ui.css");
$document->addStyle("css/stylesheet.css");
$document->addStyle("css/clean.css");
$document->addStyle('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', true);

$document->addScript("js/jquery.js");
$document->addScript("js/jquery-ui.js");
$document->addScript("js/jquery.colorbox.js");

//$document->addScript('http'.($_SERVER["HTTPS"]?"s":"").'://maps.googleapis.com/maps/api/js?key=AIzaSyA0FwolJRwWrmjr_YlzUGuZJ8gisiNGrrU&sensor=true&libraries=places&amp;language=$$_languajeKey$$');
$document->addScript('http'.($_SERVER["HTTPS"]?"s":"").'://maps.googleapis.com/maps/api/js?key=AIzaSyCl8QC-B8y6AanXpA-a9Lm8m6JSyU240bA&sensor=true&libraries=places&amp;language=$$_languajeKey$$');
//$document->addScript('http'.($_SERVER["HTTPS"]?"s":"").'://maps.googleapis.com/maps/api/js?key=AIzaSyClz8fUDLnzu5zkdNw78P1xquhovF_hoBo&sensor=true&libraries=places&amp;language=$$_languajeKey$$');




        
$document->addScript("js/map.core.js");
$document->addScript("js/wait.js");
$document->addScript("js/alertSystem.js");
$document->addScript('js/utils.js');
switch($core->browser->browser){
    case "Android":
        $document->addStyle("/css/android.css");
        break;
}

$document->addVariable("#BASE#", $core->getEnviromentVar("domain")->base);
$core    ->fireEvent("document/beforerender");
$document->out();

$_aux = $_GET['doLogout'];
if($_aux){
   unset($_SESSION['user_id']);
   unset($_SESSION['MM_Username']);
   unset($_SESSION['MM_nombre_pant']);
   unset($_SESSION['MM_UserGroup']);
   unset($_SESSION['MM_Mobile_Browser']);
}