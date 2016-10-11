<?php
class favoritos_lista_chunk{
	function out(){
		global $user_view,$user,$core;
		if($user_view->id==$user->id){
			$favoritos=$user_view->getFavoritos();
			$parced=$core->getChunk("parsedResults");
			$resultados=$parced->out($favoritos);
		return $resultados;
		}
	}
}
