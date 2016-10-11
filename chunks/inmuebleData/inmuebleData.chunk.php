<?php
class inmuebleData_chunk extends chunk_base implements chunk{
	var $params=array();
	function inmuebleData_chunk($params){
		$this->params=$params;
	}
	function out($params=array()){
		global $inmueble,$core;

                
                
                $val="";
                if($this->params["id"]&&$this->params["tipo"]){
                    $im=$core->getApp("inmueblesManager");
                    $inm=$im->getInmueble($this->params["id"],$this->params["tipo"]);
                }
                else {
                    $inm=$inmueble;
                }
		if($inm){
                        switch($this->params["name"]){
                            case "direccion":
                            case "descripcion":
                                $val=$inm->get($this->params["name"]);
                                
                                $val=  nl2br(htmlentities(($val), NULL, "UTF-8"));
                                $val=preg_replace('/\\*([^\\*_]*)\\*/i','<b>$1</b>',$val);
                                $val=preg_replace('/_([^\\*_]*)_/i','<u>$1</u>',$val);
                                $val=preg_replace('/#([^\\*_]*)#/i','<i>$1</i>',$val);
                                
                                if(strlen($val)>700){
                                    $val=substr($val , 0, 700 );
                                }
                                break;
                            default:
                                $val=$inm->get($this->params["name"]);
                            }
			}
              return $val;
	}
}
