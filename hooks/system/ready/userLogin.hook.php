<?php

class userLogin_hook extends hook_base implements hook{
    public function fire(){
        global $core,$config,$user;
        if($_COOKIE["esp_l_334"]&&!$user->id){
            $user->loginWithCookie();
        }
    }

}