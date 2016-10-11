<?php
class messages_chunk extends chunk_base implements chunk{
    protected $_selfpath   = 'chunks/messages/',
              $_plantillas = array('container'    => 'html/container.html',
                                   'conversation' => 'html/conversation.html');

    var $params = array();

    function messages_chunk($params = array()){
        $this->params           = $params;
        $this->_plantillasAdmin = $this->_plantillas;
    }

    function out($params = array()){
        global $user, $user_view;
        
        $plantilla = $this->loadPlantilla('container');
        
     
        $this->_adminMode = ($user->id == $user_view->id);
        $p                = array();
        
      
        
       
                    error_reporting(1);
    ini_set('display_errors', '1');
                
               $plantilla = $this->loadPlantilla('container');
                $p         = array('user_id'            =>123,
                                   'user_name'          => 'hola',
                                   'conversation_title' => '');
                
                      
                
       $fichero = '/var/www/vhosts/e-spacios.com/httpdocs1/test.log';
       file_put_contents($fichero, $plantilla);
                
       
       
        
        
        return parent::out($plantilla, $p);
    }
}
