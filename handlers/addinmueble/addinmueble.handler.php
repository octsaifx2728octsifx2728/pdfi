<?php
class addinmueble_handler implements handler{
	function run($task,$params=array()){
		global $document,$core, $user_view,$user;
				 $user_view=$user;
		switch($task){
			case "freemium";
				$document=$core->getDocument("freemiumform.html");
				break;
			
		}
	}
}