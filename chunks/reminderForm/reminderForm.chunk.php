<?php
class reminderForm_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/form.html");
  protected $_selfpath="chunks/reminderForm/";
  protected $_scripts=array("js/loginForm.js");
  protected $_styles=array("css/reminderForm.css");
  public function out($params=array()){
    global $user;
    if($user->id){
      $plantilla=$this->loadPlantilla(1);
      }
    else {
      $plantilla=$this->loadPlantilla(0);      
     }
    $p=array(
        "KEY"=>md5("aaa"+rand())
    );
    return parent::out($plantilla,$p);
  }
}
