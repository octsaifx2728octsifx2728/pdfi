<?php
class loginForm_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/form.html");
  protected $_selfpath="chunks/loginForm/";
  protected $_scripts=array("js/loginForm.js");
  protected $_styles=array("css/loginForm.css");
  public function loginForm_chunk($params){
  	$this->_params=(array)$params;
  }
  public function out($params=array()){
    global $user;
    if($user->id){
      $plantilla=$this->loadPlantilla(1);
      }
    else {
      $plantilla=$this->loadPlantilla(0);      
     }
    $p=array(
        "KEY"=>md5("aaa"+rand()),
        "RETURN"=>$this->_params["return"]?urlencode($this->_params["return"]):"/"
    );
    return parent::out($plantilla,$p);
  }
}
