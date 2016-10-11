<?php
class usuarios_app {
function get($index=0,$order="desc"){
		
	    global $config,$core;
		
           
	    $db=&$core->getDB(0,2);
		
            $q="SELECT * FROM usuarios";
                
                        
                
		$r=mysql_query($q);
		
                    $usuarios=array();
                    
	    while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
	    	
		    
                
	    	$p=array(
	    		"id_cliente"=>$i["id_cliente"],
	    		"usuario"=>$i["usuario"],
                        "imagen"=>$i["avatar"]);
			array_push($usuarios,(object)$p);
		}
                
               
		return $usuarios;
	}
}