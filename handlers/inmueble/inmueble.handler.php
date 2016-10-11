<?php
class inmueble_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$document,$inmueble,$user_view,$result,$user;
        $app=$core->getApp("inmueblesManager");
        $core->loadClass("inmueble");
        switch ($task){
            case "get":
                $id=explode("_",$_GET["id"]);
                $core->loadClass("inmueble");
                $u=new user($id[1]);
                $manager=&$core->getApp("inmueblesManager");
                if($inmueble=$manager->getInmueble($id[0],$id[2])){
                    $result["error"]="0";
                    $result["errorDescription"]='ok';
                    
                    
                    if(count($imagen=$inmueble->getImage(0,1))){
                      $imgpath=$imagen[0]->path;
                      if(!$imagen[0]->path||!file_exists($imgpath)){
                        $imgpath="galeria/imagenes/".$inmueble->cliente."_".$inmueble->id."_1.jpg";
                        if(!file_exists($imgpath)){
                          $imgpath="galeria/imagenes/sinimagen.jpg";
                          }
                        }
                    }
                    else {
                      $imgpath="galeria/imagenes/sinimagen.jpg";
                    }
                                   
                    $items=$inmueble->getItemsGeneral();
                    $itemsp=array();
                    foreach($items as $k=>$v){
                        if($v->valor){
                            $itemsp[]=$k;
                        }
                       }
                   
                    $result["inmueble"]=
                        array(
                            "id"=>$inmueble->id,
                            "titulo"=>htmlentities($inmueble->get("titulo"),null,"UTF-8"),
                            "imagen"=>$imgpath,
                            "imagenes"=>$inmueble->getImage(0,5),
                            "descripcion"=>htmlentities($inmueble->get("descripcion"),null,"UTF-8"),
                            "precio"=>number_format($inmueble->getPrecio($core->getEnviromentVar("currency")),2),
                            "m2"=>$inmueble->get("m2"),
                            "metrica"=>$inmueble->get("metrica"),
                            "m2s"=>$inmueble->get("m2s"),
                            "habitaciones"=>strval($inmueble->get("habitaciones")),
                            "banos"=>strval($inmueble->get("banos")),
                            "estacionamientos"=>strval($inmueble->get("estacionamientos")),
                            "estacionamientos2"=>strval($inmueble->get("estacionamientos2")),
                            "anio"=>strval($inmueble->get("anio")),
                            "email"=>$u->get("usuario"),
                            "telefono"=>$u->get("telefono"),
                            "servicios"=>$itemsp
                            );
                }
                
                break;
            
				case "add":
					$inmueble=$app->create($_GET);
					if($inmueble===INMUEBLE_ERROR_LOGINREQUIRED){
						$result["error"]="8";
						$result["errorDescription"]='$$login_required$$';
						break;
					}
					elseif(is_a($inmueble,"inmueble")) {
							      $result["error"]="0";
							      $result["errorDescription"]='ok';
							      $result["inmuebleKey"]=$inmueble->id."-".$inmueble->cliente;
					}
					break;
    case "add2":
            if($inmueble=$this->verifyUser($_GET["id"], $_GET["tipo"], $user))
            {
                if($inmueble->isActive()){
                    $lexicon=$core->getLexicon();
                   // header("location:".$inmueble->getURL());
                   //exit;
                }
                
                //$token=$app->getPaymentToken($inmueble,explode(",",$_GET["productos"]),$_GET["method"]);
                $token = $app->getPaymentToken($inmueble, explode(",", $_GET["productos"]), $_GET["method"],isset($_GET["newAndPremium"]));
		$result["error"]="0";
		$result["errorDescription"]='ok';
		$result["token"]=$token;
                
                
                
                if($document){
                  //  print_r($result);
                   // exit;
                    
                    if($token["total"]){
                        if($result["follow"]){
                            /*
                            header("location:".$result["follow"]);
                            echo "<script>location.href='".$result["follow"]."'</script>";*/
                            
                            
                            echo $result["follow"];
                            exit;
                        }
                    }
                    
                    
                   echo "/app/inmueble/fremiumsucc";
                    exit;
                    /*
                    $document=$core->getDocument("freemiumsuccess.html");
                    $document->addStyle("/css/paypal.css");*/
                }
                
            }
            else {
                $result["error"]="8";
                $result["errorDescription"]='$$login_required$$';
            }
    break;
    case "fremiumsucc":
            $document = $core->getDocument("freemiumsuccess.html");
            $document->addStyle("/css/paypal.css");
    break;    
    case "borrar":
        if(!$user->id){
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$1';
            break;
        }
        if($inmueble=$this->verifyUser($_GET["id"], $_GET["tipo"], $user)){
            if($inmueble->borrar()){
                $result["error"]="0";
               $result["id"]=$inmueble->id;
               $result["tipo"]=$_GET["tipo"];
                $result["errorDescription"]='ok';
                }
        }
        else {
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$2';
            break;
         }
    break;
    case "update":
        $app=&$core->getApp("inmueblesManager");
        $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
        if(!$inmueble){
            $result["error"]="9";
            $result["errorDescription"]='$$inexistente$$';
            break;
        }
        if($inmueble->get("id_cliente")!=$user->id){
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$';
            break;
            }
       else {
            if(!$user->id){
                $anuncio=$inmueble->getAnuncio();
                if(!($anuncio&&intval($anuncio->get("huerfano")))){
                    $result["error"]="8";
                    $result["errorDescription"]='$$login_required$$';
                    break;
                }
            }
           switch($_GET["atributo"]){
                default:
                     if($resp=$inmueble->set($_GET["atributo"],$_GET["valor"])){
                         $result["error"]="0";
                         $result["errorDescription"]='ok';
                         $result["atributo"]=$_GET["atributo"];
                         $result["valor"]=$resp;
                         $result["id"]=$inmueble->id;
                         $result["tipo"]=$_GET["tipo"];
                         }
                     else{
                         $result["error"]="9";
                         $result["errorDescription"]='$$operacion_no_permitida$$';
                         }
           }
      }
      break; 
      case "getFotos":
            $app=&$core->getApp("inmueblesManager");
            $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
            
        if($inmueble){
                $anuncio=$inmueble->getAnuncio();
                if($anuncio){
                    if(!$user->id){
                        if($anuncio->get("huerfano")!="1"){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                        }
                    }
                    elseif($anuncio->get("user")!=$user->id){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                    }
        $result["error"]="0";
        $result["errorDescription"]='ok';
        $result["id"]=$_GET["id"];
        $result["tipo"]=$_GET["tipo"];
        $result["fotos"]=$inmueble->getImages($user->id==$inmueble->get("id_cliente")||$anuncio->get("huerfano"));
        $result["videos"]=$inmueble->getVideos($user->id==$inmueble->get("id_cliente")||$anuncio->get("huerfano"));
        }
        }
        break;
    case "addFoto":
            $app=&$core->getApp("inmueblesManager");
            $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
            
        if($inmueble){
                $anuncio=$inmueble->getAnuncio();
                if($anuncio){
                    if(!$user->id){
                        if($anuncio->get("huerfano")!="1"){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                        }
                    }
                    elseif($anuncio->get("user")!=$user->id){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                    }
            $result["error"]="0";
            $result["errorDescription"]='ok';
            $result["id"]=$_GET["id"];
            $result["tipo"]=$_GET["tipo"];
            $result["foto"]=$inmueble->addFoto($_GET["path"]);
            $result["oldfoto"]=$_GET["path"];
            }
        else {
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$';
            break;
            }
        }
    break;
    case "addFoto360":
            $app=&$core->getApp("inmueblesManager");
            $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
            
        if($inmueble){
                $anuncio=$inmueble->getAnuncio();
                if($anuncio){
                    if(!$user->id){
                        if($anuncio->get("huerfano")!="1"){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                        }
                    }
                    elseif($anuncio->get("user")!=$user->id){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                    }
            $result["error"]="0";
            $result["errorDescription"]='ok';
            $result["id"]=$_GET["id"];
            $result["tipo"]=$_GET["tipo"];
            $result["foto"]=$inmueble->addFoto($_GET["path"],true);
            $result["oldfoto"]=$_GET["path"];
            }
        else {
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$';
            break;
            }
        }
    break;
    case "delFoto":
            $app=&$core->getApp("inmueblesManager");
            $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
            
        if($inmueble){
                $anuncio=$inmueble->getAnuncio();
                if($anuncio){
                    if(!$user->id){
                        if($anuncio->get("huerfano")!="1"){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                        }
                    }
                    elseif($anuncio->get("user")!=$user->id){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                    }
            $result["error"]="0";
            $result["errorDescription"]='ok';
            $result["foto"]=$inmueble->delFoto($_GET["foto"]);
            }
        else {
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$';
            break;
            }
        }
    break;
    case "addVideo":
        
            $app=&$core->getApp("inmueblesManager");
            $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
            
        if($inmueble){
                $anuncio=$inmueble->getAnuncio();
                if($anuncio){
                    if(!$user->id){
                        if($anuncio->get("huerfano")!="1"){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                        }
                    }
                    elseif($anuncio->get("user")!=$user->id){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                    }
            $result["error"]="0";
            $result["errorDescription"]='ok';
            $result["id"]=$_GET["id"];
            $result["tipo"]=$_GET["tipo"];
            $result["foto"]=$inmueble->addVideo($_GET["key"],$_GET["index"]);
            }
        else {
            $result["error"]="8";
            $result["errorDescription"]='$$login_required$$';
            break;
            }
        }
        break;
        case "getAnuncioPretend":
            
            $app=&$core->getApp("inmueblesManager");
            $inmueble=$app->getInmueble($_GET["id"],$_GET["tipo"], $user);
            
            if($inmueble){
                $anuncio=$inmueble->getAnuncio();
                if($anuncio){
                    if(!$user->id){
                        if($anuncio->get("huerfano")!="1"){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                        }
                    }
                    elseif($anuncio->get("user")!=$user->id){
                            $result["error"]="8";
                            $result["errorDescription"]='$$login_required$$';
                            break;
                    }
                    
                        
                        $result["error"]="0";
                        $result["errorDescription"]='ok';
                        $result["id"]=$_GET["id"];
                        $result["tipo"]=$_GET["tipo"];
                        $result["pretend"]=$inmueble->getPretendAnuncio();
                        
                
                        
                  
                        
                        $result["fotos"]=$inmueble->getImages(true);
                        $result["videos"]=$inmueble->getVideos(true);
                    
                    
                }
                else{
                    break;
                }
            }
            
        break;
        case "getNewForm":
            
            
            $app=&$core->getApp("inmueblesManager");
            
            $id=0;
            $tipo=0;
            $u=intval($user->id);
            
          
            
            if($_GET["id"]){
                $id=intval($_GET["id"]);
                $tipo=intval($_GET["tipo"]);
            }
            elseif($_SESSION["PreInmueble"]){
                $preid=explode("_",$_SESSION["PreInmueble"]);
                $id=intval($preid[0]);
                $tipo=intval($preid[2]);
            }
            $inm=$app->getInmueble($id,$tipo,$user);
            
             
            
            
            
            if($inm){
                $anuncio=$inm->getAnuncio();
                if($anuncio){
                    if(intval($anuncio->get("user"))!=$u){
                        $inm=false;
                    }
                    if(intval($anuncio->get("incompleto"))!=1){
                        $inm=false;
                    }
                    if(intval($anuncio->get("activo"))!=0){
                        $inm=false;
                    }
                }
                else {
                    $inm=false;
                }
            }
            
            if(!$inm){
                
                $prot=$app->getPrototypes();
                foreach($prot as $p){
                    if($p->typeid==$tipo){
                       $inm=$p; 
                       $inm->build($user);
                    }
                }
            }
            if($inm){
                if(!$u){
                    $_SESSION["PreInmueble"]=$inm->getID();
                }
                $anuncio=$inm->getAnuncio();
                $anuncio->set("huerfano_since",date("Y-m-d H:i:s"));
                $result["error"]="0";
                $result["errorDescription"]='ok';
                $result["id"]=$inm->id;
                $result["user"]=$user->id;
                $result["user2"]=$inm->get("id_cliente");
                $result["tipo"]=$inm->typeid;
                $result["form"]=$inm->getNewForm();
                $result["okText"]='$$continuar$$';
                $result["cancelText"]='$$cancelar$$';
            }
            else{
                
               $result["error"]="9";
               $result["errorDescription"]='$$no_Prototype_found$$';
               break;
            }
            break;
          default:
            
              
            
            
              
            $task=explode("/",$task);
            $id=explode("-",$task[0]);
            $core->loadClass("inmueble");
            switch(count($id)){
                case 2:
                    $db=&$core->getDB(0,2);
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: /app/inmueble/".$id[0]."-".$id[1]."-1/".$task[1]);
                    exit;
                case 3:
                    $u=new user($id[1]);
                    $manager=&$core->getApp("inmueblesManager");
                    
                    
                    
                    $inmueble=$manager->getInmueble($id[0],$id[2],$u);
                    break;
                default:
                    header("HTTP/1.0 404 Not Found");
                    header("location:/");
                    break;
            }

            if(!$inmueble){
                        header("location:/");
                    exit;
                    }
            if(!$inmueble->get("id_cliente")){
                  header("location:/");
                    exit;
            }
            if(!$inmueble->isActive()&&$inmueble->get("id_cliente")!=$user->id){
                
                
               
                header("location:/");
                    exit;
            }


            
            
            
            
            $user_view=new user($id[1]);
            if($document){
                $document=$core->getDocument("inmueble.html");
                $document->addStyle("css/style.css");
                $document->addStyle("css/searchmap.css");
                $document->addStyle("css/inmueble.css");
                $document->addScript("js/searcher.js");
                $document->addVariable("#OFERTA#",(strtotime($inmueble->get("fecvenoferta"))>time()?"1":"0"));
                
                $document->addVariable("#PREMIUM#",(strtotime($inmueble->get("fecvenpremium"))>time()?"1":"0"));
                $document->addVariable("#VENDIDO#",(intval($inmueble->get("vendido"))?"1":"0"));
                $document->addVariable("#TIPOOBJETO#",$inmueble->get("tipoobjeto"));
            }
        }
        
    }
    private function verifyUser($id,$tipo,$user){
        global $core;
        
            if(!$user->id){
                $result["error"]="8";
                $result["errorDescription"]='$$login_required$$';
                return false;
            }
        $app=$core->getApp("inmueblesManager");
        $inmueble=$app->getInmueble($id, $tipo);
        return $app->getInmueble($id, $tipo, $user);
    }
}