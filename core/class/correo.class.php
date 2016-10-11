<?php
class correo
	{
	function correo($plantilla=false)
		{
		$this->contenido=(file_exists($plantilla))?file_get_contents($plantilla):"";
		$this->plantilla=(file_exists($plantilla))?true:false;
		$this->setFrom();
		}
	function addDestinatario($destinatario)
		{
		$destinatario=(preg_match('/.*@.*\..*/',$destinatario))?$destinatario.",":"";
		$this->destinatario.=htmlentities($destinatario);
		}
	function addContenido($Contenido)
		{
		if(is_array($Contenido))
			{
			foreach(array_keys($Contenido) as $cont)
				{
				$this->contenido=str_replace("#".$cont."#",$Contenido[$cont],$this->contenido);
				}
			}
		else
			{
			$this->contenido.=	$Contenido;
			}
		}
	function setAsunto($asunto)
		{
		$this->asunto=htmlentities($asunto)?htmlentities($asunto):"Sin Asunto";
		}
	function setFrom($from=false)
		{
		$this->from["Nombre"]=$from?$from["Nombre"]:"Auto_Mail";
		$this->from["Mail"]=$from?$from["Mail"]:"mktg1@thermolab.mx";
		}
	function parsear($variables)
		{
		foreach(array_keys($variables) as $var)
			{
			$this->contenido=str_replace("#".$var."#",$variables[$var],$this->contenido);
			}
		}
	function enviar()
		{
			global $core;
		if(strlen($this->destinatario)<5)
			{
			return(false);
			}
		else
			{
			$this->asunto=$this->asunto?$this->asunto:"Sin Asunto";
			
				$lexicon=$core->getLexicon();
				if($lexicon){
					$this->contenido=$lexicon->traduce($this->contenido);
					$this->asunto=$lexicon->traduce($this->asunto);
				}
			
			$this->destinatario=trim($this->destinatario,",");
			//$this->destinatario="pacheco.saurom@gmail.com";
			$this->from=$this->from?$this->from:array("Nombre"=>"Auto_Mail","Mail"=>"support@e-spacios.com");
			$headers  = 'MIME-Version: 1.0' . "\n";
			$headers .= 'Content-Type: text/html;'."\n";
			$headers .= 'From: "'.$this->from["Nombre"].'" <'.$this->from["Mail"].'>'.'' . "\n";
			$mail=@mail($this->destinatario,$this->asunto,$this->contenido,$headers);
			return($mail);
			}
		}
	}