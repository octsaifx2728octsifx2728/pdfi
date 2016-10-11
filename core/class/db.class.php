<?php
class db {
	var $conf;
	var $conexion;
	function db($conf){
		$this->conf=$conf;
	}
	function query($q) {
	  $c=$this->getConexion();
	}
	function getConexion(){
	  if(!$this->conexion){
	    $this->connect();
	  }
	  return $this->conexion;
	}
	function connect(){
	  $this->conexion=mysql_connect($this->conf->host,$this->conf->user,$this->conf->password);
	  if($this->conexion){
	    mysql_select_db($this->conf->database);
	  }
	}

}