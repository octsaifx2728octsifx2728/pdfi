<?php
class userProfile_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/profile.html");
  protected $_selfpath="chunks/userProfile/";
  protected $_scripts=array("js/loginForm.js");
  protected $_styles=array("css/userProfile.css");
  public function out($params=array()){
    global $user;
    if($user->id){
      $plantilla=$this->loadPlantilla(0);
      $p=array(
          "AVATAR"=>$user->getAvatar(),
          "NOMBRE"=>$user->get("nombre_pant"),
          "LINK"=>$user->getLink(),
          "KEY"=>md5("aaa"+rand()),
          "UNREADMESSAGES"=>($user->countUnreadMessages()?"1":"0")
      );
      return parent::out($plantilla,$p);
      }
  }
}
