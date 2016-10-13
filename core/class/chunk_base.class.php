<?php

class  chunk_base{
        protected $_plantillas=array();
        protected $_selfpath="";
        protected $_scripts=array();
        protected $_styles=array();
        protected $_adminMode=false;
        function loadPlantilla($plantilla){
                if(is_array($this->_plantillasAdmin)&&$this->_adminMode){
                    $this->_plantillas=&$this->_plantillasAdmin;
                }
                if(file_exists($this->_selfpath.$this->_plantillas[$plantilla])){
                    
                    
                    
                        return file_get_contents($this->_selfpath.$this->_plantillas[$plantilla]);
                }
        }
        function parse($plantilla, $valores){
                foreach(array_keys($valores) as $k){
                        $plantilla=str_replace("#$k#",$valores[$k],$plantilla);
                }
                $plantilla=preg_replace('/#[a-z0-9]#/i', "", $plantilla);
                
                $plantilla=$this->parsechunks($plantilla);
                
             
                return $plantilla;
        }
        function loadScripts(&$document){
            if(is_a($document,"document")){
                foreach($this->_scripts as $s){
                        $document->addScript($s);
                }
            }
        }
        function loadStyles(&$document){
            if(is_a($document,"document")){
                foreach($this->_styles as $s){
                        $document->addStyle($s);
                }
            }
        }
        function out($plantilla,$valores){
          global $document;
           
          $this->loadScripts($document);
          $this->loadStyles($document);
          
         
          return $this->parse($plantilla,$valores);
        }
  public function parsechunks($doc){
    global $core;
    $chunk_keys=array();
    preg_match_all("/\[\[.*\]\]/i",$doc,$chunk_keys);
    $chunk_keys=$chunk_keys[0];
    foreach($chunk_keys as $k){
      $chunk=$core->getChunk($k);
      if($chunk){
	$text=$chunk->out();
      }
      else {
	$text="";
      }
      $doc=str_replace($k,$text,$doc);
    }
    return $doc;
  }
}