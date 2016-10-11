<?php
class mailer_app{
	function send($params){
		global $core,$config;
		$core->loadClass("correo");
		$correo=new correo($params["plantilla"]);
		$correo->addDestinatario($params["destinatario"]);
		$correo->addContenido($params["variables"]);
		$correo->setFrom($config->defaults["mail"]);
		$correo->setAsunto($params["asunto"]);
		$correo->enviar();
	}
}