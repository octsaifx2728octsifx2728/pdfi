<?php
class login_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/nologged.html");
  protected $_selfpath="chunks/login/";
  protected $_scripts=array("js/login.js");
  protected $_styles=array("css/login.css");
  public function out($params=array()){
    global $user;
    if($user->id){
      $plantilla=$this->loadPlantilla(1);
      }
    else {
      $plantilla=$this->loadPlantilla(0);      
     }
    $p=array("FACEBOOKLINK"=>"/app/facebook/login");
    return parent::out($plantilla,$p);
  }
}
