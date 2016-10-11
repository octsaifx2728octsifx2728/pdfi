<?php
class user_handler implements handler{
    public function run($task, $params = array()) {
        global $document,$core, $user , $user_view,$result;
        
       
        
        switch($task){
            case "resetPassword":
                if($user->id){
                    if($document){
                        $document=$core->getDocument("changePassword.html");
                    }
                }
                else{
                    if($document){
                        $document=$core->getDocument("resetPassword.html");
                    }
                }
                if($_GET["email"]){
                    $result["error"]=0;
                    $result["errorDescription"]='ok';
                    $core->loadClass("user");
                    user::reminderPassword(trim($_GET["email"]));
                }
                break;
            case "getProfile":
                if($user->id){
                    $result["error"]=0;
                    $result["errorDescription"]='ok';
                    $result["userData"]=array("id"=>$user->id);
                }
		else{
                    $result["error"]=1;
                    $result["errorDescription"]='userNotFound';
		}
                break;
            case "get":
                $usuario=$_GET["id"]?new user($_GET["id"]):$user;
                $search=&$core->getApp("search");
                if($usuario->get("id_cliente")){
                    $result["error"]=0;
                    $result["errorDescription"]='ok';
                    $inmuebles=$search->searchByUser($usuario,($usuario->id==$user->id));
                    $inms=array();
                    foreach($inmuebles as $inmueble)
                      {
                        
                        if(count($imagen=$inmueble->getImage())){
                                        $imgpath=$imagen[0]->path;
                                        if(!$imagen[0]->path||!file_exists($imgpath)){
                                                        $imgpath="galeria/imagenes/sinimagen.jpg";
                                        }
                              }
                              else {
                                        $imgpath="galeria/imagenes/sinimagen.jpg";
                              }
                      
                        $inms[]=array(
                            "id"=>$inmueble->id."_".$usuario->id."_".$inmueble->get("tipoobjeto"),
                            "imagen"=>$imgpath,
                            "titulo"=>$inmueble->get("titulo"),
                            "descripcion"=>$inmueble->get("descripcion"),
                            "precio"=>number_format($inmueble->getPrecio($core->getEnviromentVar("currency")),2),
                            "subtipo"=>$inmueble->nombreS
                        );

                        }
                    $result["usuario"]=array(
                        "id"=>$usuario->get("id_cliente"),
                        "nombre_pant"=>$usuario->get("nombre_pant"),
                        "avatar"=>$usuario->getAvatar(),
                        "telefono"=>$usuario->get("telefono"),
                        "inmuebles"=>$inms
                    );
                    if($usuario->id==$user->id){
                        $result["usuario"]["mensajes"]=$usuario->countUnreadMessages();
                    }
                }
                break;
            default:
                $task=explode("/",$task);
                $user_view=new user($task[0]);
                
                /*
                if($user_view->getTipoUsuario() == 0){ 
                    
                }
                 * 
                 */
                
                $document=$core->getDocument("user.html");
                $document->addVariable("#ID#",$user_view->id);
                $document->addVariable("#ID#"     , $user_view->id);
                $document->addVariable("#OWNER#"  , $user_view->id == $user->id ? ""      : "not_owner");
                $document->addVariable("#owner#"  , $user_view->id == $user->id ? 'owner' : '');
                $document->addVariable('#section#', substr($_GET['task'], strrpos($_GET['task'], '/') + 1));
                $document->addVariable('#countUnreadMessages#', 0);
                
                $document->addStyle("/css/style.css");
                $document->addStyle("/css/user.css");
                $document->addScript("js/jquery.js");
                $document->addScript("/js/user.js");
                $document->addScript("/js/actions.js");
                
                
                
        }
    }

}
