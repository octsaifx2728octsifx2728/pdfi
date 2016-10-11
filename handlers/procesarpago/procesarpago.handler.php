<?php
class procesarpago_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$document,$factura,$user;
        $core->loadClass("user");
        $app=$core->getApp("inmueblesManager");
        $factura=$app->getFacturaByToken($_POST["Token"]?$_POST["Token"]:$task);
        if(!$factura->id){
           $document=$core->getDocument("banorte_error.html");
           $document->addVariable("#PAYMENTERROR#", '$$factura_inexistente$$');
            $document->addVariable("#USRLINK#", $user->getLink());
           return false;
        }
        $user=new user($factura->get("cliente"));
        $user->forceLogin();
        switch($task){
            case "3dSecure":
                /**/
                if($_POST["Status"]==200){
                    $banorte=$core->getApp("banorte");
                    if($banorte->checkout($_POST,$factura)){
                        $document=$core->getDocument("banorte_success.html");
                    }
                    else {
                        $document=$core->getDocument("banorte_error.html");
                        //print_r($_POST);
                        $document->addVariable("#PAYMENTERROR#",  htmlentities($_POST["Text"]));
                    }
                }
                else {
                    $document=$core->getDocument("banorte_error.html");
                        //print_r($_POST);
                    $document->addVariable("#PAYMENTERROR#",  htmlentities($_POST["Message"]));
                }
                /**/
        $document->addVariable("#USRLINK#", $user->getLink());
                break;
            default:
                if(!is_a($factura,"factura")){
                    header("location:http://www.e-spacios.com");
                    exit;
                    }
                $facuser=$factura->getUser();
                if($facuser->id!=$user->id){
                    $user=$facuser;
                    $user->forceLogin();
                    }
                    $document=$core->getDocument("formularioBanorte.html");
                    $document->addStyle("/css/style.css");
                    $document->addStyle("css/formularioPago.css");
                    $document->addScript("js/jquery.js");
                    $document->addScript("js/jquery.mtz.monthpicker.js");
                    $document->addScript("js/creditcard.js");
                    $document->addScript("js/formularioPago.js");
                    break;
		}
    }
}
