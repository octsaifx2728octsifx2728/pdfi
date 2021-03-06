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

$config->paths["html"]=$config->paths["base"]."html_mailer/";

if (!isset($_SESSION)) {
  session_start();
}
include_once $config->paths["core"]."pay_core.php";

if(!$document=$core->getDocument($_GET["plantilla"].".html")){
	$document=$core->getDocument("index.html");
}

    $app="mainpage";
    $task="defaultTask";
    $app=$core->getApp($app);


    if(is_object($app)&&method_exists($app,$task)){

      $app->$task($params);
      $document->addAlerts();
    }

$app=$_POST["app"]?$_POST["app"]:$_GET["app"];
$task=trim($_POST["task"]?$_POST["task"]:$_GET["task"],"/");

switch($app){
    case "send-master":
        $q="select `id`,`email_address_1` from `mailers` where `campana1`=0 and(
            `State_1`='NY' or 
            `State_1`='CA' or 
            `State_1`='TX' or 
            `State_1`='FL' or 
            `State_1`='IL' 
            ) order by rand() limit 60";
        
        
    	$db=$core->getDB(0,2);
		$mailer=$core->getApp("mailer");
    	if($r=$db->query($q)){
			/*$mailer->send(array(
				"destinatario"=>"cxescalona@gmail.com",
				"asunto"=>"[start]".$_GET["subject"],
				"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
				"variables"=>array(
						"BASE"=>$config->paths["urlbase"],
						"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
					)
					
				));*/
    				$core->setLanguaje("en");
    		while($i=$r->fetch_assoc()){

					$mailer->send(array(
						"destinatario"=>$i["email_address_1"],
						"asunto"=>"".$_GET["subject"],
						"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
						"variables"=>array(
								"BASE"=>$config->paths["urlbase"],
								"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
							)
							
						));
					echo "enviado:".$i["id"]." ".$i["email_address_1"]."::en<br>";
                                        $q="update `mailers` set `campana1`=1 where `id`=".$i["id"];
                                        $db->query($q);
                                        echo $q;
    				
    		}
                
			/*$mailer->send(array(
				"destinatario"=>"cxescalona@gmail.com",
				"asunto"=>"[end]".$_GET["subject"],
				"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
				"variables"=>array(
						"BASE"=>$config->paths["urlbase"],
						"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
					)
					
				));*/
    	}
    	exit;
        
        break;
    case "send-prev";
      $destinatarios=array(
		array(
				"nombre"=>"carlos",
				"email"=>"cxescalona@gmail.com",
				"lang"=>"es"
      		),
		array(
				"nombre"=>"carlos",
				"email"=>"cxescalona@gmail.com",
				"lang"=>"en"
      		),
		array(
				"nombre"=>"carlos",
				"email"=>"cpatriciosuarezf@gmail.com",
				"lang"=>"es"
      		),
		array(
				"nombre"=>"carlos",
				"email"=>"patriciosuarezf@gmail.com",
				"lang"=>"en"
      		)
      		
		);
      
			$mailer=$core->getApp("mailer");
		foreach($destinatarios as $d){
			$core->setLanguaje($d["lang"]);
			$mailer->send(array(
				"destinatario"=>$d["email"],
				"asunto"=>"[E-spacios.com] ".$_GET["subject"],
				"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
				"variables"=>array(
						"BASE"=>$config->paths["urlbase"],
						"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
					)
					
				));
			echo "enviado:".$d["email"];
		}
      exit;
      break;
    case "send":
    	$q="select `usuario`,`nombre_pant`,`languaje` from `usuarios`";
    	$db=$core->getDB(0,2);
		$mailer=$core->getApp("mailer");
    	if($r=$db->query($q)){
			$mailer->send(array(
				"destinatario"=>"cxescalona@gmail.com",
				"asunto"=>"[E-spacios.com] ".$_GET["subject"],
				"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
				"variables"=>array(
						"BASE"=>$config->paths["urlbase"],
						"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
					)
					
				));
    		while($i=$r->fetch_assoc()){
    			if($i["languaje"]){

    				$core->setLanguaje($i["languaje"]);
					$mailer->send(array(
						"destinatario"=>$i["usuario"],
						"asunto"=>"[E-spacios.com] ".$_GET["subject"],
						"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
						"variables"=>array(
								"BASE"=>$config->paths["urlbase"],
								"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
							)
							
						));
					echo "enviado:".$i["usuario"]."::".$i["languaje"]."<br>";
    			}
    			else{

    				$core->setLanguaje("en");
					$mailer->send(array(
						"destinatario"=>$i["usuario"],
						"asunto"=>"[E-spacios.com] ".$_GET["subject"],
						"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
						"variables"=>array(
								"BASE"=>$config->paths["urlbase"],
								"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
							)
							
						));
					echo "enviado:".$i["usuario"]."::en<br>";

    				$core->setLanguaje("es");
					$mailer->send(array(
						"destinatario"=>$i["usuario"],
						"asunto"=>$_GET["subject"],
						"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
						"variables"=>array(
								"BASE"=>$config->paths["urlbase"],
								"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
							)
							
						));
					echo "enviado:".$i["usuario"]."::es<br>";
    				
    			}
    		}
                
			$mailer->send(array(
				"destinatario"=>"cxescalona@gmail.com",
				"asunto"=>$_GET["subject"],
				"plantilla"=>"html_mailer/".$_GET["plantilla"].".html",
				"variables"=>array(
						"BASE"=>$config->paths["urlbase"],
						"MESSAGELINK"=>$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]
					)
					
				));
    	}
    	exit;
    	break;
    default:
      $handler=$core->getHandler($app);
            if($handler){
                if($handler->run($task)=="noConsumido"){
                  $document->addStyle("css/index.css");
                }
            }
            else {
              $result["error"]="2";
              $result["errorDescription"]="Aplicacion Desconocida";
              $document->addStyle("css/index.css");
            }
      break;
}
          
$document->addVariable("#BASE#",$core->getEnviromentVar("domain")->base);
$document->addVariable("#MESSAGELINK#",$config->paths["urlbase"]."/mailer.php?plantilla=".$_GET["plantilla"]);

$mensaje=$document->out(true);

$document=$core->getDocument("index.html");
$document->addStyle("css/index.css");
$document->addVariable("#BASE#",$core->getEnviromentVar("domain")->base);
$document->addVariable("#MENSAJE#",$mensaje);

$document->out();
?>