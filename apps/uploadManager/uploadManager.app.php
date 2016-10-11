<?php
class uploadManager_app{
	var $image_types=array("image/jpeg","image/jpg","image/png","image/gif");
	function upload($target){
		global $result,$config;
                
                 error_reporting(E_ALL);
                 ini_set('display_errors', '1');

                
		switch($target){
			case "avatar":
				$archivo=$_FILES["avatar"];
                            
                            
                            if(is_array($archivo["error"])){
                                foreach(array_keys($archivo["error"]) as $k){
                                    if($archivo["error"][$k]||!$archivo["size"][$k]){
                                            $result["error"][$k]="7";
                                            $result["errorDescription"][$k]="Error al transmitir";
                                            }
                                    else {
                                            if(array_search($archivo["type"][$k],$this->image_types)===false){
                                                    $result["error"][$k]="8";
                                            $result["errorDescription"][$k]="Formato no soportado: ".$archivo["type"][$k];					
                                                    }
                                            else {
                                                    $archivo[$k]=$this->storeTemp($archivo["tmp_name"][$k],$archivo["type"][$k],"Avatar_",$config->paths["preload"],200,200);
                                                    if($archivo[$k]){

                                                    $result["error"][]="0";
                                            $result["errorDescription"][$k]="Ok";
                                            $result["path"][$k]=str_replace($config->paths["base"],"",$archivo[$k]);	
                                                    }
                                                    else {

                                                    $result["error"][$k]="7";
                                                    $result["errorDescription"][$k]="Error Interno";
                                                    }
                                            }
                                    }
				}
                            }
                            else {
				if($archivo["error"]||!$archivo["size"]){
					$result["error"]="7";
	    			$result["errorDescription"]="Error al transmitir";
					}
				else {
                                            if(array_search($archivo["type"],$this->image_types)===false){
                                                    $result["error"]="8";
                                            $result["errorDescription"]="Formato no soportado: ".$archivo["type"];					
                                                    }
                                            else {
                                                    $archivo=$this->storeTemp($archivo["tmp_name"],$archivo["type"],"Avatar_",$config->paths["preload"],200,200);
                                                    if($archivo){

                                                    $result["error"]="0";
                                            $result["errorDescription"]="Ok";
                                            $result["path"]=str_replace($config->paths["base"],"",$archivo);	
                                                    }
                                                    else {

                                                    $result["error"]="7";
                                            $result["errorDescription"]="Error Interno";
                                                    }
                                            }
                                    }
                                
                                }
				break;
			case "imagen":
				$archivo=$_FILES["avatar"];
                            
                            
                            if(is_array($archivo["error"])){
                                foreach(array_keys($archivo["error"]) as $k){
                                    if($archivo["error"][$k]||!$archivo["size"][$k]){
                                            $result["error"][$k]="7";
                                            $result["errorDescription"][$k]="Error al transmitir";
                                            }
                                    else {
                                            if(array_search($archivo["type"][$k],$this->image_types)===false){
                                                    $result["error"][$k]="8";
                                            $result["errorDescription"][$k]="Formato no soportado: ".$archivo["type"][$k];					
                                                    }
                                            else {
                                                    $archivo[$k]=$this->storeTemp($archivo["tmp_name"][$k],$archivo["type"][$k],"Avatar_",$config->paths["preload"],800,600);
                                                    if($archivo[$k]){

                                                    $result["error"][]="0";
                                            $result["errorDescription"][$k]="Ok";
                                            $result["path"][$k]=str_replace($config->paths["base"],"",$archivo[$k]);	
                                                    }
                                                    else {

                                                        $result["error"][$k]="7";
                                                        $result["errorDescription"][$k]="Error Interno";
                                                    }
                                            }
                                    }
				}
                            }
                            else {
				if($archivo["error"]||!$archivo["size"]){
					$result["error"]="7";
	    			$result["errorDescription"]="Error al transmitir";
					}
				else {
                                            if(array_search($archivo["type"],$this->image_types)===false){
                                                    $result["error"]="8";
                                            $result["errorDescription"]="Formato no soportado: ".$archivo["type"];					
                                                    }
                                            else {
                                                    $archivo=$this->storeTemp($archivo["tmp_name"],$archivo["type"],"Avatar_",$config->paths["preload"],800,600);
                                                    if($archivo){

                                                    $result["error"]="0";
                                            $result["errorDescription"]="Ok";
                                            $result["path"]=str_replace($config->paths["base"],"",$archivo);	
                                                    }
                                                    else {

                                                    $result["error"]="7";
                                            $result["errorDescription"]="Error Interno";
                                                    }
                                            }
                                    }
                                
                                }
				break;
			
			case "imagen360":
				$archivo=$_FILES["avatar"];
                            
                            
                            if(is_array($archivo["error"])){
                                foreach(array_keys($archivo["error"]) as $k){
                                    if($archivo["error"][$k]||!$archivo["size"][$k]){
                                            $result["error"][$k]="7";
                                            $result["errorDescription"][$k]="Error al transmitir";
                                            }
                                    else {
                                            if(array_search($archivo["type"][$k],$this->image_types)===false){
                                                    $result["error"][$k]="8";
                                            $result["errorDescription"][$k]="Formato no soportado: ".$archivo["type"][$k];					
                                                    }
                                            else {
                                                    $archivo[$k]=$this->storeTemp($archivo["tmp_name"][$k],$archivo["type"][$k],"Avatar_",$config->paths["preload"],1300,530);
                                                    if($archivo[$k]){

                                                    $result["error"][]="0";
                                            $result["errorDescription"][$k]="Ok";
                                            $result["path"][$k]=str_replace($config->paths["base"],"",$archivo[$k]);	
                                                    }
                                                    else {

                                                        $result["error"][$k]="7";
                                                        $result["errorDescription"][$k]="Error Interno";
                                                    }
                                            }
                                    }
				}
                            }
                            else {
				if($archivo["error"]||!$archivo["size"]){
					$result["error"]="7";
	    			$result["errorDescription"]="Error al transmitir";
					}
				else {
                                            if(array_search($archivo["type"],$this->image_types)===false){
                                                    $result["error"]="8";
                                            $result["errorDescription"]="Formato no soportado: ".$archivo["type"];					
                                                    }
                                            else {
                                                    $archivo=$this->storeTemp($archivo["tmp_name"],$archivo["type"],"Avatar_",$config->paths["preload"],1300,530);
                                                    if($archivo){

                                                    $result["error"]="0";
                                            $result["errorDescription"]="Ok";
                                            $result["path"]=str_replace($config->paths["base"],"",$archivo);	
                                                    }
                                                    else {

                                                    $result["error"]="7";
                                            $result["errorDescription"]="Error Interno";
                                                    }
                                            }
                                    }
                                
                                }
				break;
			default:
				$result["error"]="6";
    			$result["errorDescription"]="Objetivo de Upload Desconocido: ".$target;
				break;
		}
		return $result;
	}
	function storeTemp($archivo,$tipo,$prefix="FILE_",$path="preload",$w=1000,$h=1000){
	
                
                
                
                /*
                $myfile = fopen("/var/www/html/test.log", "w");
               
               
                fwrite($myfile, str_replace("/tmp/","",$archivo));
                fclose($myfile);
            */
                $path=tempnam($path, $prefix);
                
                copy($archivo, path."oriigSfx");
                
                $info=getimagesize($archivo);
                
               
                
		switch($tipo){
			case "image/jpeg":
                            $img=imagecreatefromjpeg($archivo);
				$path.=".jpg";
				break;
			case "image/jpg":
                            $img=imagecreatefromjpeg($archivo);
				$path.=".jpg";
				break;
			case "image/png":
                            $img=imagecreatefrompng($archivo);
				$path.=".png";
				break;
			case "image/gif":
                            $img=imagecreatefromgif($archivo);
				$path.=".gif";
				break;
		}
                
                
		$ow=$info[0];
                $oh=$info[1];
                
                
                $oapaisada=$ow>=$oh;
                $apaisada=$w>=$h;
                
                $ph=($h*100)/$oh;
                $pw=($w*100)/$ow;
                
                if($pw>$ph){
                    $w=$ow*$ph/100;
                }
                else {
                    $h=$oh*$pw/100;
                }
                
		$img2 = imagecreatetruecolor(intval($w), intval($h));

                
		 imagecopyresampled($img2,$img,0,0,0,0,$w,$h,$ow,$oh);
                 
                $rtx = imagejpeg( $img2,$path,100);
                
                
               if ($rtx == false)
                   $path = null;
                
                
                
		return $path;
		
	}
	function pocesarImagen(){
		echo "procesando";
	}
}