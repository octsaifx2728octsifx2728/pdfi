<?php

class cache_app {
    public function storeData($key,$data,$expiration){
        global $core;
        $db=$core->getDB(0,2);
        $q="replace into `cache`(`key`,`val`,`expire`) 
            values('".$db->real_escape_string($key)."',
                '".$db->real_escape_string($data)."',
                    '".date("Y-m-d H:i:s",intval($expiration))."')";
        
        $db->query($q);
    }
    public function loadData($key){
        global $core;
        $db=$core->getDB(0,2);
        $q="select `val` from `cache` where `key`='".$db->real_escape_string($key)."' and `expire`>NOW()";
        if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
                return $i["val"];
            }
        }
    }
  function serve($params){
      global $core;
      
   /*
    $pathsfx = "/var/www/vhosts/e-spacios.com/httpdocs/".$params["path"];
    
    $params["path"] = $pathsfx;
     */ 
   if(file_exists($params["path"])){
       $path=$params["path"];
       
       
   
   }
   elseif(file_exists(str_replace(".jpg","",$params["path"]))){
       $path=str_replace(".jpg","",$params["path"]);
   }
   elseif(file_exists(str_replace(".jpg.jpg",".jpg",$params["path"]))){
       $path=str_replace(".jpg.jpg",".jpg",$params["path"]);
   }
   else {
       $path="galeria/imagenes/sinimagen.jpg";
   }
   if($path){
       
       
       
      $info=getimagesize($path);
      
      
      
      
      switch($info["mime"]){
	case "image/jpeg":
	  $img=imagecreatefromjpeg($path);
	  break;
	case "image/png":
	  $img=imagecreatefrompng($path);
	  break;
      }
      if($img){
          
        
          
      	if(!$params["height"]){
      		$h=$this->calcularProporcion($params["width"],$info[0],$info[1]);
      	}
		else{
			$h=$params["height"];
		}
      	if(!$params["width"]){
      		$w=$this->calcularProporcion($params["height"],$info[1],$info[0]);
      	}
		else{
			$w=$params["width"];
		}
                
               
	 
	if($params["x2"]){
		if($info[0]<$params["x2"]){
			$x2=$info[0];
		}
		else {
			$x2=$params["x2"];
		}
		if($info[0]<$params["x1"]){
			$x1=$info[0];
		}
		else {
			$x1=$params["x1"];
		}
		if($info[1]<$params["y1"]){
			$y1=$info[1];
		}
		else {
			$y1=$params["y1"];
		}
		if($info[1]<$params["y2"]){
			$y2=$info[1];
		}
		else {
			$y2=$params["y2"];
		}
		if($x2<$x1){
			$x2=$x1;
		}
		if($y2<$y1){
			$y2=$y1;
		}
		
		
		$pw=($w*100)/$info[0];
		$ph=($h*100)/$info[1];
		
		$w2=(($x2-$x1)*$pw)/100;
		$h2=(($y2-$y1)*$ph)/100;
		
		$img2 = imagecreatetruecolor(intval($w2), intval($h2));
                
                $blanco=imagecolorallocate($img2, 255, 255, 255);
                imagefill($img2, 0, 0, $blanco);
		 imagecopyresampled($img2,$img,0,0,$x1,$y1,$w2,$h2,$x2,$y2);
                 
              
            if(strrpos($path,"galeria/imagenes")!==false){
                
                
                
                $icon=imagecreatefrompng("images/e.png");
                $iconInfo=getimagesize("images/e.png");
                $iwp=(5*$w2)/100;
                $iw=($iwp*$iconInfo[0])/100;
                $ih=($iwp*$iconInfo[1])/100;
                imagecopyresampled($img2,$icon,($w2-$iw),($h2-$ih),0,0,$iw,$ih,$iconInfo[0],$iconInfo[1]);
                
                
            }
           
                
	}
	else {
            
            $pw=($w*100)/$info[0];
                $w1=$w;
                $h1=($info[1]*$pw)/100;
                if($h1>$h){
                    $pw=($h*100)/$info[1];
                    $w1=($info[0]*$pw)/100;
                    $h1=$h;
                }
                $x=($w-$w1)/2;
                $y=($h-$h1)/2;
		$img2 = imagecreatetruecolor(intval($w), intval($h));
                
                $blanco=imagecolorallocate($img2, 255, 255, 255);
                imagefill($img2, 0, 0, $blanco);
                
                   
                
		imagecopyresampled($img2,$img,$x,$y,0,0,intval($w1),intval($h1),intval($info[0]),intval($info[1]));
                
                if($params["stamp"]){
                            
                            
                
                         $stamppath="img/lang/".$core->getEnviromentVar("languaje")."/stamps/".$params["stamp"].".png";
                         if(file_exists($stamppath)){
                             $stamp=imagecreatefrompng($stamppath);
                             $stampInfo=getimagesize($stamppath);
                             $sw=intval($w)*1;
                             $sh=((($sw*100)/$stampInfo[0])*$stampInfo[1])/100;
                             imagecopyresampled($img2,$stamp,(($w-$sw)/2),(($h-$sh)/2),0,0,intval($sw),intval($sh),$stampInfo[0],$stampInfo[1]);

                         }
                     }
                 
                   
            if(strrpos($path,"galeria/imagenes")!==false){
                   
                            
                $icon=imagecreatefrompng("images/e.png");
                $iconInfo=getimagesize("images/e.png");
                $iwp=(5*$w)/100;
                $iw=($iwp*$iconInfo[0])/100;
                $ih=($iwp*$iconInfo[1])/100;
                imagecopyresampled($img2,$icon,($w-$iw),($h-$ih),0,0,$iw,$ih,$iconInfo[0],$iconInfo[1]);
                
                
                
            }
                     
	}
	if($params["x2"]){
		
		$path=$params["x1"]."/".$params["y1"]."/".$params["x2"]."/".$params["y2"]."/".$path;
		
	}
	//$this->storeImage($img2,intval($params["width"]),intval($params["height"]),$path,$params["stamp"]);
        
        
        
	header("content-type:image/jpeg");
	imagejpeg( $img2,null,100);
	imagedestroy($img);
	imagedestroy($img2);
        
        
        
        
      }
   }
  }
  
  
  
	

	function calcularProporcion($base, $final, $valor){
		$porcentaje=($base*100)/$final;
		return ($valor*$porcentaje)/100;
		
	}
  function storeImage(&$img2,$w,$h,$file,$stamp=""){
      
      
      
      global $core,$config;
      if($stamp){
          $file="stamp/".$stamp."/".$file;
      }
      
      $path=$config->paths["base"]."cache/".($_GET["forceLang"]?$core->getEnviromentVar("languaje")."/":"").$w."/".$h."/".str_replace($config->paths["base"],"",dirname($file)."/");
      
     
      
        
      @mkdir($path,0777,true);
      
     $path=$path.basename($file);
     
    
     
    imagejpeg( $img2,$path,100);
    
       
     
 }

    
    
}