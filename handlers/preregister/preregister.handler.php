<?php
class preregister_handler implements handler{
	public function run($task, $params = array()) {
		global $core,$document;
		switch($task){
			case "promociones";
				$document=$core->getDocument("promociongratis.html");
				$document->addStyle("css/registerForm.css");
				$document->addStyle("/css/promociongratis.css");
			break;
			default:

				$document=$core->getDocument("precios.html");
				$document->addStyle("/css/precios.css");
		}
	}
}