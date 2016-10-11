<?php
class objeto{
	public $campos=array();
	protected $_idField="id";
        protected $_fieldTypes=array();
        public function set($nombre,$valor){
            global $core;
            $db=&$core->getDB(0,2);
            switch($this->_fieldTypes[$nombre]){
                case "int":
                    $s="ii";
                    break;
                default:
                    $s="si";
            }
            $q="update `".($this->_tabla)."` set `".$db->real_escape_string($nombre)."`=? where `".($this->_idField)."`=? limit 1";
            
            if($sm=$db->prepare($q)){
                $sm->bind_param($s, $valor, $this->id);
                if($sm->execute()){
                    $this->campos[$nombre]=$valor;
                    return true;
                }
            }
        }
	public function get($nombre){
		if(!array_key_exists($nombre, $this->campos)){
			$this->reload($nombre);
		}
		return $this->campos[$nombre];
	}
	protected function reload($nombre){
            global $core;
            $db=&$core->getDB(0,2);
            $q="select `".$db->real_escape_string($nombre)."` as 'valor' from `".($this->_tabla)."` where `".($this->_idField)."`='".intval($this->id)."' limit 1";
            
            
            
	    if($r=$db->query($q)){
              if($i=$r->fetch_assoc()){
                    $this->campos[$nombre]=$i["valor"];
              }
            }
	}
        protected function borrar(){
            global $core;
            $db=&$core->getDB(0,2);
            $q="delete from `".($this->_tabla)."` where `".($this->_idField)."`= ".intval($this->id)." limit 1";
            return($db->query($q));
        }
}
