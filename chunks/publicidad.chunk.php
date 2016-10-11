<?php
class publicidad_chunk{
	var $params;
	function publicidad_chunk($params=array()){
		$this->params=$params;
	}
	function out($params=array()){
		global $core;
		$params=($params&&is_array($params)&&count($params))?$params:$this->params;
		
		$posicion=$params["pos"];
		
		$q="select `contenido` 
			from `publicidad` 
			where `lugar`='".mysql_real_escape_string($posicion)."'
				and `fecha_alta`<=now()
				and `fecven`>=now()
			order by rand()
			limit 1";
		$r=mysql_query($q);
		$i=mysql_fetch_array($r,MYSQL_ASSOC);
		return $i["contenido"];
	}
}
