<?php
class registerForm_chunk extends chunk_base implements chunk{
  protected $_plantillas=array(

  		"html/form.html",
  		"",
  "html/form_1.html"
  );
  protected $_selfpath="chunks/registerForm/";
  protected $_scripts=array("js/registerForm.js");
  protected $_styles=array("css/registerForm.css");
  public function registerForm_chunk($params){
  	$this->_params=(array)$params;
  }
  public function out($params=array()){
    global $user;
    if($user->id){
      $plantilla=$this->loadPlantilla(1);
      }
    else {
      $plantilla=$this->loadPlantilla($this->_params["plantilla"]?intval($this->_params["plantilla"]):0);      
     }
    $p=array(
        "KEY"=>md5("aaa"+rand()),
        "RETURN"=>$this->_params["return"]?urlencode($this->_params["return"]):"/"
    );
    return parent::out($plantilla,$p);
  }
}
