<?php
class userData_chunk {
	var $params=array();
	function userData_chunk($params){
		$this->params=$params;
	}
	function out(){
		global $user_view;
		if($user_view){
			return $user_view->get($this->params["name"]);
			}
	}
}
