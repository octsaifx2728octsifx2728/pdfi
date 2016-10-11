<?php
class tokenAccess_hook extends hook_base implements hook{
    public $depends=array("system/ready/userLogin_hook");
    public function fire(){
        global $core,$config,$user;
        if($_GET["usertoken"]){
            $user->loginWithToken($_GET["usertoken"]);
        }
    }

}
