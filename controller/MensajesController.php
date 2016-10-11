<?php
class MensajesController {    
    public function API(){
        global $config,$root_path, $core,$user,$document,$result, $vr;
        header('Content-Type: application/JSON');                
        
        $task=trim($_POST["task"]?$_POST["task"]:$_GET["task"],"/");
        
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            
            
             $this->evaluarTarea($task);
           
            break;     
        case 'POST'://inserta
            
            $this->evaluarTarea($task);
            break;                
        case 'PUT'://actualiza
            $result=  'PUT';
            break;      
        case 'DELETE'://elimina
            $result =  'DELETE';
            break;
        default://metodo NO soportado
            $result= 'METODO NO SOPORTADO';
            break;
        }
    }
    public function evaluarTarea($task){
        
       global $config,$root_path, $core,$user,$document,$result, $vr;
        
         $myfile = fopen("/var/www/vhosts/e-spacios.com/httpdocs1/test.log", "w");               
                fwrite($myfile, $task);
                fclose($myfile);
           
        
        switch ($task) {
            case 'ConsultarUsuarios'://consulta
                
                $myfile = fopen("/var/www/vhosts/e-spacios.com/httpdocs1/test.log", "w");               
                fwrite($myfile, "consulta");
                fclose($myfile);
                
                $app=$core->getApp("usuarios");
                
                $usuarios = $app->get(0,"desc");
                
                 
                
                $result= $usuarios;
                
                break;     

            default://metodo NO soportado
                $result= 'METODO NO SOPORTADO';
                break;
        }
        
    }
    
}//end class