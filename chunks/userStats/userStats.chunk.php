<?php
class userStats_chunk extends chunk_base implements chunk{
    protected $_selfpath   = 'chunks/userStats/',
              $_plantillas = array('html/userStats.html',
                                   'html/userStats_off.html');

    function out($params = array()){
        global $config, $user_view, $user;

        if($user_view){
            //if($user_view->id == $user->id){
            //    $plantilla = $this->loadPlantilla(0);
            //
            //}else {
            //    $plantilla = $this->loadPlantilla(1);
            //}

            $plantilla = $this->loadPlantilla(1);

            $p = array("FAVORITOS"     => $user_view->countFavoritos(),
                      "ANUNCIOS"       => $user_view->countAnuncios(),
                      "MENSAJES"       => $user_view->countUnreadMessages(),
                      "TRATOSCERRADOS" => $user_view->countVendidos(),
                      "USERLINK"       => $user_view->getLink());

            return parent::out($plantilla, $p);
        }
    }
}
