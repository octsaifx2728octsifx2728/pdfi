<?php
class currencyconverter_handler implements handler{
	function run($task,$params=array()){
            global $document,$core, $user_view,$user,$result;
            $app=$core->getApp("currencyconverter");
            if($app){
              switch($task){
                case "convert":
                  $result["error"]="0";
                  $result["errorDescription"]="OK";
                  $result["resultado"]=$app->convert($_GET["id"],$_GET["moneda"]);
                  break;
                case "convert2":


                  $currency=&$core->getApp("currencyconverter");

                  $divisaDestino=$currency->getDivisa($_GET["moneda"]);
                  $divisaOrigen=$currency->getDivisa($_GET["monedaOrig"]);

                  $precio=(1/$divisaOrigen)*$divisaDestino*intval($_GET["valor"]);
                  if($precio){
                      $result["error"]="0";
                      $result["errorDescription"]="OK";
                      $result["moneda"]=$_GET["moneda"];
                      $result["extra"]=$_GET["extra"];
                      $result["valor"]=number_format($precio,0);
                      $result["valorraw"]=($precio);
                      }
                  else {
                      $result["error"]="1";
                      $result["errorDescription"]="imposible convertir";
                      $result["valor"]=number_format($_GET["valor"],0);
                      $result["valorraw"]=($_GET["valor"]);
                      $result["extra"]=$_GET["extra"];
                      $result["moneda"]=$_GET["monedaOrig"];
                    }
                  break;
                case "setDefaultCurrency":
                  $result["error"]="0";
                  $result["errorDescription"]="OK";
                  $core->setCurrency($_GET["currency"]);

                  break;
                case "updatecurrency":
                    //session_start();
                    if ( $_GET["currency"] == "3594ciosUPda73CURR3nc135" ){
                        try {
                            $app->updateCurrency($_GET["currency"]);
                            exit;
                        } catch (CodeExchangeException $e) {
                        } catch (NoUserIdException $e) {
                        }
                    }
                    break;
              }
            }
	}
}